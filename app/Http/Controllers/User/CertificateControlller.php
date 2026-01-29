<?php

namespace App\Http\Controllers\User;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CertificateControlller extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $certificates = Certificate::with(['user', 'package', 'type'])
            ->where('id_user', $idUser)->paginate(10);
        return view('user.certificate.index', compact('certificates'));
    }
    public function detail(Certificate $certificate)
    {
        if ((int) $certificate->id_user !== (int) Auth::id()) {
            abort(403);
        }

        $certificate->load([
            'user',
            'package.materials',
            'type',
            'teachers',
        ]);

        return view('user.certificate.show', compact('certificate'));
    }
}
