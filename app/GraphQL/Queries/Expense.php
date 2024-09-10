<?php

namespace App\GraphQL\Queries;

use App\Models\Expense as Expenses;
use Illuminate\Support\Facades\Auth;

class Expense
{
    private $user;
    public function __construct()
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('Unauthorized');
        }
        $this->user = $user;
    }
    public function listExpenses()
    {
        $Expenses = Expenses::where('user_id', $this->user->id)->get();
        return $Expenses;
    }
}
