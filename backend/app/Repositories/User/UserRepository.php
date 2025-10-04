<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class UserRepository implements UserRepositoryInterface
{
    public function getAll(array|Collection $filters = [], int|string $perPage = 'all', Collection|array $sort = []): LengthAwarePaginator
    {
        $filters = collect($filters);

        $query = User::query();

        if ($filters->has('name'))
            $query->where('name', 'like', '%' . $filters['name'] . '%');

        foreach ($sort as $field => $direction) {
            $query->orderBy($field, $direction);
        }

        return $perPage === 'all' ? $query->paginate($query->count()) : $query->paginate($perPage);
    }
}
