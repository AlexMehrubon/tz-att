<?php
declare(strict_types=1);

namespace App\Services\Order;

use App\Repositories\Order\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository
    )
    {
    }

    public function getAll(?Collection $filters, string|int $perPage = 'all', Collection|array $sort = []): LengthAwarePaginator
    {
        return $this->orderRepository->getAll(
            filters: $filters,
            perPage: $perPage,
            sort: $sort
        );
    }
}
