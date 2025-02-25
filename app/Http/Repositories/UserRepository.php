<?php

namespace App\Http\Repositories;

use App\Models\ConfirmEmailUrlModel;
use App\Models\Scopes\ActiveScope;
use App\Models\User;
use Illuminate\Contracts\Queue\EntityNotFoundException;

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

    public function createConfirmEmailUrl(User $user, string $url): void {
        ConfirmEmailUrlModel::create(array('user_id' => $user->id, 'url' => $url));
    }

    public function confirmEmail(int $id, string $url) {
        $confirmEmailUrl = ConfirmEmailUrlModel::where('user_id', $id)
            ->where('url', $url)
            ->where('is_used', false);

        if(!$confirmEmailUrl->exists()) {
            throw new EntityNotFoundException('EmailUrl', $url);
        }

        $confirmEmailUrl->update(array('is_used' => true));
        $user = User::withoutGlobalScope(ActiveScope::class)->where('id', $id);
        $user->update(array('is_active' => true));
    }

    public function destroy(int $id): void {
        if(!User::where('id', $id)->exists()) {
            throw new EntityNotFoundException('User', $id);
        }
        User::where('id', $id)->update(array('active' => false));
    }
}
