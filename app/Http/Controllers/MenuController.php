<?php

namespace App\Http\Controllers;

use App\Enums\MenuCategories;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;
use App\Traits\SiteTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    use SiteTrait;

    public function table()
    {
        return DataTables::of(Menu::byUser())
            ->addColumn('action', fn ($data) => $data->id)
            ->rawColumns(['action'])
            ->make(true);
    }

    public function index()
    {
        $title = 'Menu';
        $categories = array_column(MenuCategories::cases(), 'value');

        return view('dashboard.menus.index', compact('title', 'categories'));
    }

    public function store(StoreMenuRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['image'] = $this->storeFile($request->image, 'menus');
            $data['user_id'] = auth()->id();

            $menu = Menu::make($data);
            $menu->save();

            DB::commit();

            $result = ['status' => 'success', 'message' => 'Berhasil membuat data menu!'];
        } catch (\Exception $e) {
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($result);
    }

    public function show(Menu $menu)
    {
        return response()->json($menu);
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            if ($request->hasFile('image')) {
                Storage::delete('public/menus/'.$menu->image);
                $data['image'] = $this->storeFile($request->image, 'menus');
            }

            $menu->fill($data)->save();

            DB::commit();

            $result = ['status' => 'success', 'message' => 'Berhasil mengubah data menu!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($result);
    }

    public function destroy(Menu $menu)
    {
        try {
            DB::beginTransaction();

            Storage::delete('public/menus/'.$menu->image);
            $menu->delete();

            DB::commit();

            $result = ['status' => 'success', 'message' => 'Berhasil menghapus data menu!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($result);
    }
}
