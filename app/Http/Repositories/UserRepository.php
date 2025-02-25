<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Database\Eloquent\Collection;

class UserRepository {

    public function show(int $id): User {
        if(!User::where('id', $id)->exists()) {
            throw new EntityNotFoundException('User', $id);
        }
        return User::find($id);
    }

    public function store($data): User {
        return User::create($data);
    }

    public function update($user, $data): User {
        $user->update($data);

        return $user;
    }

    public function destroy(int $id): void {
        if(!User::where('id', $id)->exists()) {
            throw new EntityNotFoundException('User', $id);
        }
        User::where('id', $id)->update(array('active' => false));
    }
}
