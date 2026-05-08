<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\FaceToFaceSchedule;
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
        $schedules = FaceToFaceSchedule::query()
            ->with(['package:id,title', 'sessions' => fn($q) => $q->orderBy('session_date')->orderBy('start_time')->limit(3)])
            ->where('is_active', true)
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('index', [
            'jenis' => $jenis,
            'paketPerJenis' => $paketPerJenis,
            'books' => $books,
            'schedules' => $schedules,
        ]);
    }
}
