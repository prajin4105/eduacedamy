<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    public function view(User $user, Chat $chat): bool
    {
        return $this->isParticipant($user, $chat);
    }

    public function send(User $user, Chat $chat): bool
    {
        return $this->isParticipant($user, $chat);
    }

    protected function isParticipant(User $user, Chat $chat): bool
    {
        return $chat->student_id === $user->id || $chat->instructor_id === $user->id;
    }
}
