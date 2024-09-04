<?php

namespace App\Repositories\User;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $modelName = User::class;

    public function register($data)
    {
        $data['role'] = RoleEnum::USER->value;

        return $this->store($data);
    }

    public function deleteMany(array $ids): bool
    {
        DB::beginTransaction();
        foreach ($ids as $id) {
            $query = DB::table('users')->where('id', '=', $id)->update([
                'deleted_at' => Carbon::now(config('app.timezone')),
                'deleted_by' => auth()->user()->email,
            ]);
            if (! $query) {
                DB::rollBack();

                return false;
            }
        }
        DB::commit();

        return true;
    }

    public function profile_by_user(string $id)
    {
        return $this->find($id, ['name', 'address', 'email', 'phone_number']);
    }

    public function profile_by_admin(string $id)
    {
        return $this->find($id);
    }

    public function list_users(int $perPage, string $orderBy, string $direction): LengthAwarePaginator
    {
        return $this->paginate($orderBy, [], $perPage);
    }

    public function update_profile_by_user(string $id, array $data): int
    {
        return $this->update($id, $data);
    }

    public function update_profile_by_admin(string $id, array $data): int
    {
        return $this->update($id, $data);
    }

    public function delete_user(string $id)
    {
        return $this->destroy($id);
    }

    public function create_by_admin(array $data)
    {
        $data['password'] = 'password';
        $data['created_by'] = auth()->user()->email;

        return $this->store($data);
    }

    public function change_password_by_user(array $data)
    {
        $user = auth()->user();
        $currentPassword = $data['current_password'];
        if (password_verify($currentPassword, $user->password)) {
            $newPassword = bcrypt($data['new_password']);
            $result = DB::table('users')
                ->where('email', '=', $user->email)
                ->update([
                    'password' => $newPassword,
                    'updated_at' => Carbon::now(config('app.timezone')),
                    'updated_by' => auth()->user()->email,
                ]);

            return $result;
        } else {
            return 'Current password is incorrect';
        }
    }
}
