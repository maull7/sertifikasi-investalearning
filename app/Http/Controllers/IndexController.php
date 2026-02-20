<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\MasterType;
use App\Models\Package;

class IndexController extends Controller
{
    public function index()
    {
        $jenis = MasterType::orderBy('id')->get();
        $paketPerJenis = Package::with(['mappedSubjects.materials', 'userJoins'])
            ->get()
            ->groupBy('id_master_types');
        $books = Book::paginate(10);

        return view('index', [
            'jenis' => $jenis,
            'paketPerJenis' => $paketPerJenis,
            'books' => $books,
        ]);
    }
}
