<?php
declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Order\Interfaces\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class OrderRepository implements OrderRepositoryInterface
{
    public function getAll(array|Collection $filters = [], int|string $perPage = 'all', Collection|array $sort = []): LengthAwarePaginator
    {
        $query = Order::query();

        foreach ($sort as $field => $direction) {
            $query->orderBy($field, $direction);
        }

        return $perPage === 'all' ? $query->paginate($query->count()) : $query->paginate($perPage);
    }
}
