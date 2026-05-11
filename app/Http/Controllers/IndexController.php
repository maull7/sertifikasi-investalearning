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
            ->orderBy('title', 'asc') // alfabet nama paket
            ->where('status', 'active')
            ->get()
            ->groupBy('id_master_types');

        $books = Book::paginate(10);

        $schedules = FaceToFaceSchedule::query()
            ->with([
                'package:id,title',
                'sessions' => fn($q) => $q
                    ->orderBy('session_date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->limit(4)
            ])
            ->join('face_to_face_sessions as s', 's.schedule_id', '=', 'face_to_face_schedules.id')
            ->where('face_to_face_schedules.is_active', true)
            ->groupBy('face_to_face_schedules.id')
            ->orderByRaw('MIN(s.session_date) ASC')
            ->orderByRaw('MIN(s.start_time) ASC')
            ->select('face_to_face_schedules.*')
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
