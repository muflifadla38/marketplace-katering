<?php

namespace App\Traits;

use App\Exceptions\JsonException;
use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Scopes\ExcludeSelfFilterScope;
use App\Models\Scopes\SkpdFilterScope;
use Google\Client as GoogleClient;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait SiteTrait
{
    public function compressImage($image, $folder = 'users/')
    {
        $imagePath = $folder.Str::uuid().'.'.$image->getClientOriginalExtension();

        $compressedImage = Image::make($image);
        $compressedImage->resize(null, 300, function ($constraint) {
            $constraint->aspectRatio();
        });
        $compressedImage->save(public_path('storage/'.$imagePath));

        return $imagePath;
    }

    protected function storeFile($file, $folder, $id = null, $name = false, $isBase64 = false)
    {
        $name = $name ? $name : $folder;
        $filename = $name.'-'.($id ?? rand()).'-'.time().'.';

        if ($isBase64) {
            $filename .= explode('/', mime_content_type($file))[1];
            $path = public_path("storage/{$folder}/").$filename;

            Image::make($file)->save($path);
        } else {
            $filename = ($folder == 'settings') ? $name : ($filename.$file->getClientOriginalExtension());

            $file->storeAs('public/'.$folder, $filename);
        }

        return $filename;
    }

    public function createDirectory($folder = 'users')
    {
        if (! file_exists(public_path('storage/'.$folder))) {
            Storage::disk('local')->makeDirectory('public/'.$folder);
        }
    }

    public function formatDate($date, $format = 'Y-m-d')
    {
        return Carbon::createFromFormat($format, $date)->setTimeFromTimeString('00:00:00');
    }

    public function getAttendance($employeeId, $date)
    {
        return Attendance::findByEmployeeDate($employeeId, $date)->first();
    }

    public function createAttendance($employee, $date, $data = []): void
    {
        Attendance::create(array_merge([
            'date' => $date,
            'office_status_id' => $employee->office_status_id,
            'employee_id' => $employee->id,
            'skpd_id' => $employee->skpd_id,
            'rule_id' => $employee->rule_id,
        ], $data));
    }

    public function deleteBatchJob($id, $connection = 'default'): void
    {
        $cursor = 0;
        $key = 'queues:'.$connection.':delayed';

        $redis = Redis::connection();
        [$cursor, $jobs] = $redis->zscan($key, $cursor, ['match' => '*'.$id.'*']);

        foreach ($jobs as $job => $time) {
            $redis->zrem($key, $job);
        }

        $batches = Bus::findBatch($id);
        if ($batches) {
            $batches->delete();
        }
    }

    public function generateAbsence()
    {
        $date = now()->format('Y-m-d');
        $absentEmployees = Employee::withoutGlobalScopes([SkpdFilterScope::class, ExcludeSelfFilterScope::class])->absentEmployees($date)->get();

        foreach ($absentEmployees as $employee) {
            $this->createAttendance($employee, $date);
        }

        Log::info('Berhasil generate data absen pegawai!');
    }

    public function addLog($subject, $table = null, $table_id = null, $interface = 'web')
    {
        $request = app('request');

        $type = match ($request->method()) {
            'POST' => $request->is('login') ? 'login' : ($request->is('logout') ? 'logout' : 'add'),
            'PUT' => 'edit',
            default => 'delete'
        };

        if (strpos($table_id, ',')) {
            $table_id = '"'.$table_id.'"';
        }

        ActivityLog::create([
            'subject' => $subject,
            'interface' => $interface ?? ($request->header('authorization') ? 'mobile' : 'web'),
            'url' => $request->url(),
            'type' => $type,
            'table' => $table,
            'table_id' => $table_id,
            'user_id' => auth()->user()?->id ?? 1,
        ]);
    }

    protected function sendResponse($status, $message, $data = null)
    {
        $response = [
            'metadata' => [
                'status' => $status,
                'message' => $message,
            ],
        ];

        if ($data) {
            $response['data'] = $data;
        }

        if ($status != 200) {
            throw new JsonException($response);
        }

        return response()->json($response, $status);
    }

    protected function addCache(string $key, mixed $value, $hours = null)
    {
        $hours = $hours ? ($hours * 3600) : null;

        Cache::put($key, $value, $hours);
    }

    protected function setCache(string $key, mixed $value, $hours = null)
    {
        $hours = $hours ? ($hours * 3600) : null;

        Cache::put($key, $value, $hours);
    }

    protected function getCache(string $key)
    {
        return Cache::get($key);
    }

    protected function hasCache(string $key)
    {
        return Cache::has($key);
    }

    protected function timeCounter($time, $timeLimit)
    {
        $minutes = (strtotime($time) - strtotime($timeLimit)) / 60;

        return (int) abs($minutes);
    }

    private function getAccessToken()
    {
        $serviceAccountPath = config('firebase.service_account_path'); // Path to your service account file

        $client = new GoogleClient;
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        // $client->useApplicationDefaultCredentials();
        $token = $client->fetchAccessTokenWithAssertion();

        return $token['access_token'];
    }

    protected function firebaseNotification($message, $title = null, $topic = 'all')
    {
        $client = new Client;

        return $client->post(config('firebase.url'), [
            'headers' => [
                'Authorization' => "Bearer {$this->getAccessToken()}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'message' => [
                    'topic' => $topic,
                    'notification' => [
                        'title' => $title ?? env('APP_NAME'),
                        'body' => $message,
                    ],
                ],
            ],
        ]);
    }
}
