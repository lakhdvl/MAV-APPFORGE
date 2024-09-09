<?php 
namespace App\GraphQL\Mutations;

use App\Models\Wallet as WalletModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Wallet
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
     * Create a new Wallet
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
    */
    public function createWallet($_, array $args)
    {
        $validator = Validator::make($args, [
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|between:0,999999999999.99',
            'currency' => 'required|string|max:6',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $wallet = WalletModel::create([
            'user_id' => $this->user->id,
            'name' => $args['name'],
            'balance' => $args['balance'],
            'currency' => $args['currency'],
        ]);

        if (!$wallet) {
            throw new \Exception('Could not create wallet');
        }

        return $wallet;
    }
    
    /**
     * Update a Wallet
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
    */
    public function updateWallet($_, array $args)
    {
        $validator = Validator::make($args, [
            'id' => 'required|numeric',
            'name' => 'string|max:255',
            'balance' => 'numeric|between:0,999999999999.99',
            'currency' => 'string|max:6',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $wallet = WalletModel::where('id', $args['id'])->where('user_id', $this->user->id)->first();

        if (!$wallet) {
            throw new \Exception('Wallet not found');
        }

        if (isset($args['name'])) {
            $wallet->name = $args['name'];
        }

        if (isset($args['balance'])) {
            $wallet->balance = $args['balance'];
        }

        if (isset($args['currency'])) {
            $wallet->currency = $args['currency'];
        }

        $wallet->save();

        return $wallet;
    }

    /**
     * Delete a Wallet
     * @param  array  $args
     * @return Message
     * @throws \Exception
    */
    public function deleteWallet($_, array $args)
    {
        $validator = Validator::make($args, [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $wallet = WalletModel::where('id', $args['id'])->where('user_id', $this->user->id)->first();

        if (!$wallet) {
            throw new \Exception('Wallet not found');
        }

        $wallet->delete();

        return [
            'message' => 'Successfully delete Wallet'
        ];
    }
}
