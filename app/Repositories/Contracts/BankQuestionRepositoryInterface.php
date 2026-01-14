<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BankQuestionRepositoryInterface
{
    /**
     * Get all bank questions with pagination and search
     */
    public function getAllWithPagination(?string $search = null, int $perPage = 10): LengthAwarePaginator;

    /**
     * Get all bank questions without pagination
     */
    public function getAll(): Collection;

    /**
     * Find bank question by ID
     */
    public function findById(int $id);

    /**
     * Create new bank question
     */
    public function create(array $data);

    /**
     * Update bank question
     */
    public function update(int $id, array $data);

    /**
     * Delete bank question
     */
    public function delete(int $id): bool;

    /**
     * Get bank questions by type
     */
    public function getByType(int $typeId): Collection;

    /**
     * Bulk insert bank questions
     */
    public function bulkInsert(array $questions): bool;
}

