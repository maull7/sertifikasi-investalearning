<?php

namespace App\Repositories;

use App\Models\BankQuestions;
use App\Repositories\Contracts\BankQuestionRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class BankQuestionRepository implements BankQuestionRepositoryInterface
{
    protected $model;

    public function __construct(BankQuestions $model)
    {
        $this->model = $model;
    }

    public function getAllWithPagination(?string $search = null, ?int $typeId = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with('type')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('question', 'like', "%{$search}%")
                        ->orWhere('solution', 'like', "%{$search}%");
                });
            })
            ->when($typeId, function ($query, $typeId) {
                $query->where('type_id', $typeId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getAll(): Collection
    {
        return $this->model->with('type')->get();
    }

    public function findById(int $id)
    {
        return $this->model->with('type')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $question = $this->findById($id);
        $question->update($data);
        return $question;
    }

    public function delete(int $id): bool
    {
        $question = $this->findById($id);
        
        // Delete image file if question is Image and stored in `question` field
        if (($question->question_type ?? 'Text') === 'Image' && $question->question && Storage::disk('public')->exists($question->question)) {
            Storage::disk('public')->delete($question->question);
        }

        return $question->delete();
    }

    public function getByType(int $typeId): Collection
    {
        return $this->model
            ->where('type_id', $typeId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function bulkInsert(array $questions): bool
    {
        return $this->model->insert($questions);
    }
}

