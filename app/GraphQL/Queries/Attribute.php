<?php

namespace App\GraphQL\Queries;

use App\Models\Attribute as Attributes;
use Illuminate\Support\Facades\Validator;

class Attribute
{
    public function listAttributes($root, array $args)
    {
        return Attributes::all();
    }
}
