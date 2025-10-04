<?php
declare(strict_types=1);


namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ResourceRepositoryInterface
{
    public function getAll(Collection|array $filters = [], int|string $perPage = 'all', Collection|array $sort =  []): LengthAwarePaginator;
}
