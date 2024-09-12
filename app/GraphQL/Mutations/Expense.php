<?php

namespace App\GraphQL\Mutations;

use App\Models\Expense as Expenses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

final class Expense
{
    private $user;

    /**
     * Authenticate
     */
    public function __construct()
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('Unauthorized');
        }
        $this->user = $user;
    }
    /**
     * Create a new Expense
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */
    public function createExpense($root, array $args)
    {
        $validator = Validator::make($args, [
            'category_id' => 'required|exists:categories,id',
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $expense =  Expenses::create([
            'user_id' => $this->user->id,
            'category_id' => $args['category_id'],
            'wallet_id' => $args['wallet_id'],
            'amount' => $args['amount'],
            'date' => $args['date'],
            'description' => $args['description'] ?? null,
        ]);

        if (!$expense) {
            throw new \Exception('Could not create expense');
        }
        return $expense;
    }
    /**
     * Update a Expense
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */
    public function updateExpense($root, array $args)
    {
        $validator = Validator::make($args, [
            'category_id' => 'exists:categories,id',
            'wallet_id' => 'exists:wallets,id',
            'amount' => 'numeric',
            'date' => 'date',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $expense = Expenses::where('id', $args['id'])->where('user_id', $this->user->id)->first();


        if (!$expense) {
            throw new \Exception('expense not found');
        }

        $expense->update($args);

        return $expense;
    }
    /**
     * Delete a Expense
     * @param  array  $args
     * @return Message
     * @throws \Exception
     */
    public function deleteExpense($root, array $args)
    {
        $validator = Validator::make($args, [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $expense = Expenses::where('id', $args['id'])->where('user_id', $this->user->id)->firstOrFail();
        $expense->update(['del_flag' => true]);

        if (!$expense) {
            throw new \Exception('expense not found');
        }

        return [
            'message' => 'Successfully delete expense'
        ];
    }
}
