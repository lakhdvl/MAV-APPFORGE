<?php

namespace App\GraphQL\Mutations;

use App\Models\Income as Incomes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

final class Income
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
     * Create a new Income
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */
    public function createIncome($root, array $args)
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

        $incomes =  Incomes::create([
            'user_id' => $this->user->id,
            'category_id' => $args['category_id'],
            'wallet_id' => $args['wallet_id'],
            'amount' => $args['amount'],
            'date' => $args['date'],
            'description' => $args['description'] ?? null,
        ]);

        if (!$incomes) {
            throw new \Exception('Could not create Income');
        }
        return $incomes;
    }

    /**
     * Update a Income
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */
    public function updateIncome($root, array $args)
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

        $income = Incomes::where('id', $args['id'])->where('user_id', $this->user->id)->first();


        if (!$income) {
            throw new \Exception('income not found');
        }

        $income->update($args);

        return $income;
    }

    /**
     * Delete a Income
     * @param  array  $args
     * @return Message
     * @throws \Exception
     */
    public function deleteIncome($root, array $args)
    {
        $validator = Validator::make($args, [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $income = Incomes::where('id', $args['id'])->where('user_id', $this->user->id)->firstOrFail();

        if (!$income) {
            throw new \Exception('Income not found');
        }
        $income->delete();

        return [
            'message' => 'Successfully delete Category'
        ];
    }
}
