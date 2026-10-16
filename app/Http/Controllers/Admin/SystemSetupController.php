<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSystemSetupRequest;
use App\Models\SystemSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemSetupController extends Controller
{
    public function index()
    {
        return view('admin.system_setup.index');
    }

    public function store(StoreSystemSetupRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $image_path = null;

                if ($request->hasFile('image')) {
                    $image_path = time().'.'.$request->file('image')->getClientOriginalExtension();
                    $request->file('image')->move(public_path('banner/'.$request->image_type), $image_path);
                }

                SystemSetup::where('image_type', $request->image_type)->update(['default' => 0]);

                SystemSetup::create([
                    'name' => $request->name,
                    'image_type' => $request->image_type,
                    'image' => $image_path,
                    'default' => 1,
                ]);
            });

            return redirect()->back()->with('success', __('message.system_setup_created'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('message.system_setup_failed'));
        }
    }

    public function show(Request $request)
    {
        $type = $request->image_type;
        $bannerImages = SystemSetup::where('image_type', $request->image_type)->get();

        return view('admin.system_setup.show', compact('bannerImages', 'type'));
    }

    public function destroy($id)
    {
        $systemSetup = SystemSetup::findOrFail($id);

        if ($systemSetup->default) {
            return redirect()->back()->with('error', __('message.system_setup_delete_error'));
        }

        if ($systemSetup->image) {
            $imagePath = public_path('banner/'.$systemSetup->image_type.'/'.$systemSetup->image);
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }

        $systemSetup->delete();

        return redirect()->back()->with('success', __('message.system_setup_deleted'));
    }

    public function makeDefault($id)
    {
        $systemSetup = SystemSetup::findOrFail($id);

        try {
            DB::transaction(function () use ($systemSetup) {
                if ($systemSetup->default) {
                    throw new \Exception(__('message.system_setup_already_default'));
                }

                SystemSetup::where('image_type', $systemSetup->image_type)->update(['default' => 0]);
                $systemSetup->update(['default' => 1]);
            });

            return redirect()->back()->with('success', __('message.system_setup_make_default_success'));
        } catch (\Exception $e) {
            $message = $e->getMessage();

            return redirect()->back()->with('error', __($message) ?? __('message.system_setup_make_default_failed'));
        }
    }
}
