<?php

namespace App\Http\Controllers;

use App\Models\MasterType;
use App\Models\Package;

class IndexController extends Controller
{
    public function index()
    {
        $jenis = MasterType::orderBy('id')->get();
        $paketPerJenis = Package::with(['masterType.subjects.materials', 'userJoins'])
            ->get()
            ->groupBy('id_master_types');

        return view('index', [
            'jenis' => $jenis,
            'paketPerJenis' => $paketPerJenis,
        ]);
    }
}
