<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BankQuestionRepositoryInterface
{
    /**
     * Get all bank questions with pagination, search, and optional type filter
     */
    public function getAllWithPagination(?string $search = null, ?int $typeId = null, int $perPage = 10, ?string $sortNo = null): LengthAwarePaginator;

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
