<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class UserService
{

    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    public function getAll(?Collection $filters, string|int $perPage = 'all', Collection|array $sort = []): LengthAwarePaginator
    {
        return $this->userRepository->getAll($filters, $perPage, $sort);
    }
}
