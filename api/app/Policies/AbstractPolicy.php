<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbstractPolicy
{
    use HandlesAuthorization;

    public function all(User $user) {

    }
}
