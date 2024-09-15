<?php

namespace App\GraphQL\Queries;

use App\Models\Value as Values;
use Illuminate\Support\Facades\Validator;

class Value
{
    public function listValues($root, array $args)
    {
        return Values::all();
    }
}
