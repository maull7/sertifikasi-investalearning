<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index', [
            'qris_image'       => Setting::get('qris_image'),
            'qris_name'        => Setting::get('qris_name'),
            'qris_description' => Setting::get('qris_description'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'qris_name'        => 'nullable|string|max:255',
            'qris_description' => 'nullable|string|max:500',
            'qris_image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('qris_image')) {
            $path = $request->file('qris_image')->store('qris', 'public');
            Setting::set('qris_image', $path);
        }

        Setting::set('qris_name', $request->input('qris_name'));
        Setting::set('qris_description', $request->input('qris_description'));

        return back()->with('success', 'Pengaturan QRIS berhasil disimpan.');
    }
}
