<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Traits\SiteTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use SiteTrait;

    public function index()
    {
        $data['title'] = 'Profile Details';

        return view('dashboard.profiles.index', $data);
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $user = auth()->user();
            if ($request->hasFile('image')) {
                Storage::delete('public/users/'.$user->image);
                $data['image'] = $this->storeFile($request->image, 'users', $user->id);
            }

            if ($request->filled('newpassword')) {
                $data['password'] = $request->newpassword;
            }

            $user->fill($data)->save();
            DB::commit();

            $result = ['status' => 'success', 'message' => 'Profile Berhasil Diubah!'];
        } catch (\Exception $e) {
            DB::rollback();
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($result);
    }
}
