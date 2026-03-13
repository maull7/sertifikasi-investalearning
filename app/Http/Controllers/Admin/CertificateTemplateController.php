<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCertificateTemplateRequest;
use App\Models\CertificateTemplate;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CertificateTemplateController extends Controller
{
    public function index(): View
    {
        $packages = Package::with('masterType', 'certificateTemplate')
            ->orderBy('title')
            ->get();

        return view('admin.certificate-templates.index', compact('packages'));
    }

    public function edit(Package $package): View
    {
        $package->load('masterType');
        $template = $package->certificateTemplate ?: new CertificateTemplate(['package_id' => $package->id]);

        return view('admin.certificate-templates.edit', compact('package', 'template'));
    }

    public function update(StoreCertificateTemplateRequest $request, Package $package): RedirectResponse
    {
        $package->load('certificateTemplate');

        $data = $request->validated();

        $template = $package->certificateTemplate ?: new CertificateTemplate(['package_id' => $package->id]);

        if ($request->hasFile('front_background')) {
            $path = $request->file('front_background')->store('certificate-templates', 'public');
            $data['front_background_path'] = $path;
        }

        if ($request->hasFile('left_signature_image')) {
            $path = $request->file('left_signature_image')->store('certificate-templates', 'public');
            $data['left_signature_image_path'] = $path;
        }

        if ($request->hasFile('right_signature_image')) {
            $path = $request->file('right_signature_image')->store('certificate-templates', 'public');
            $data['right_signature_image_path'] = $path;
        }

        unset($data['front_background'], $data['left_signature_image'], $data['right_signature_image']);

        $template->fill($data);
        $template->package()->associate($package);
        $template->save();

        return redirect()
            ->route('certificate-templates.edit', $package)
            ->with('success', 'Desain sertifikat berhasil disimpan.');
    }
}

