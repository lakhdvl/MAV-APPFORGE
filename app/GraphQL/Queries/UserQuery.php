<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;

class UserQuery
{
    public function info($rootValue, array $args)
    {
        return Auth::user();
    }
}
