<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function register($data);

    public function profile_by_user(string $id);

    public function profile_by_admin(string $id);

    public function list_users(int $perPage, string $orderBy, string $direction): LengthAwarePaginator;

    public function update_profile_by_user(string $id, array $data): int;

    public function update_profile_by_admin(string $id, array $data): int;

    public function delete_user(string $id);

    public function create_by_admin(array $data);

    public function change_password_by_user(array $data);

    public function deleteMany(array $ids): bool;
}
