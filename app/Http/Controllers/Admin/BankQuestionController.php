<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequestBankQuestion;
use App\Repositories\Contracts\BankQuestionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\BankQuestionsImport;
use App\Exports\BankQuestionsTemplateExport;
use App\Models\MasterType;
use Maatwebsite\Excel\Facades\Excel;

class BankQuestionController extends Controller
{
    protected $bankQuestionRepository;

    public function __construct(BankQuestionRepositoryInterface $bankQuestionRepository)
    {
        $this->bankQuestionRepository = $bankQuestionRepository;
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $typeId = $request->query('type_id') ? (int) $request->query('type_id') : null;

        $data = $this->bankQuestionRepository->getAllWithPagination($search, $typeId);
        $types = MasterType::orderBy('name_type')->get();

        return view('admin.bank-question.index', [
            'data' => $data,
            'search' => $search,
            'types' => $types,
            'typeId' => $typeId,
        ]);
    }

    public function create()
    {
        $types = MasterType::all();
        return view('admin.bank-question.create', compact('types'));
    }

    public function store(RequestBankQuestion $request)
    {
        $data = $request->validated();

        // Normalize question based on question_type
        if (($data['question_type'] ?? 'Text') === 'Image') {
            $data['question'] = $data['question'] ?? '';
        }

        // Handle image upload (stored into `question` as path)
        if ($request->hasFile('question_file')) {
            $file = $request->file('question_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('questions', $fileName, 'public');
            $data['question'] = $filePath;
        }

        $this->bankQuestionRepository->create($data);
        return redirect()->route('bank-questions.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $data = $this->bankQuestionRepository->findById($id);
        return view('admin.bank-question.show', compact('data'));
    }

    public function edit(string $id)
    {
        $data = $this->bankQuestionRepository->findById($id);
        $types = MasterType::all();
        return view('admin.bank-question.edit', compact('data', 'types'));
    }

    public function update(RequestBankQuestion $request, string $id)
    {
        $question = $this->bankQuestionRepository->findById($id);
        $data = $request->validated();

        // Normalize question based on question_type
        if (($data['question_type'] ?? 'Text') === 'Image') {
            $data['question'] = $data['question'] ?? '';
        }

        // Handle image upload (stored into `question` as path)
        if ($request->hasFile('question_file')) {
            if (($question->question_type ?? 'Text') === 'Image' && $question->question && Storage::disk('public')->exists($question->question)) {
                Storage::disk('public')->delete($question->question);
            }

            $file = $request->file('question_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('questions', $fileName, 'public');
            $data['question'] = $filePath;
        }

        // If switching Image -> Text, delete old image file if any
        if (($data['question_type'] ?? 'Text') === 'Text' && ($question->question_type ?? 'Text') === 'Image') {
            if ($question->question && Storage::disk('public')->exists($question->question)) {
                Storage::disk('public')->delete($question->question);
            }
        }

        $this->bankQuestionRepository->update($id, $data);
        return redirect()->route('bank-questions.index')->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->bankQuestionRepository->delete($id);
        return redirect()->route('bank-questions.index')->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * Download template Excel untuk import
     */
    public function downloadTemplate()
    {
        return Excel::download(new BankQuestionsTemplateExport, 'template_bank_soal.xlsx');
    }

    /**
     * Import soal dari Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120', // max 5MB
        ], [
            'file.required' => 'File Excel wajib diupload.',
            'file.mimes' => 'File harus berformat Excel (.xlsx atau .xls).',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $import = new BankQuestionsImport();
            Excel::import($import, $request->file('file'));

            // Check for failures
            $failures = $import->failures();

            if ($failures->isNotEmpty()) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
                }
                return redirect()->route('bank-questions.index')
                    ->with('error', 'Import gagal dengan error: ' . implode(' | ', $errorMessages));
            }            // Check for custom errors
            $customErrors = $import->getErrors();
            if (!empty($customErrors)) {
                return redirect()->route('bank-questions.index')
                    ->with('error', 'Import gagal: ' . implode(' | ', $customErrors));
            }
            return redirect()->route('bank-questions.index')
                ->with('success', 'Soal berhasil diimport dari Excel!');
        } catch (\Exception $e) {
            return redirect()->route('bank-questions.index')
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}
