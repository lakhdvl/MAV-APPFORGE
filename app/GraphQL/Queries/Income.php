<?php

namespace App\GraphQL\Queries;

use App\Models\Income as Incomes;
use Illuminate\Support\Facades\Auth;

class Income
{
    private $user;
    public function __construct()
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('Unauthorized!');
        }
        $this->user = $user;
    }
    public function listIncomes()
    {
        $Incomes = Incomes::where('user_id', $this->user->id)->get();
        return $Incomes;
    }
}
