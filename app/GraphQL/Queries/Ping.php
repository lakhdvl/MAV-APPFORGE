<?php

namespace App\GraphQL\Queries;

class Ping
{
    public function __invoke($rootValue, array $args, $context, $resolveInfo)
    {
        return 'ping';
    }
}
