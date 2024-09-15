<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;
use App\Models\Wallet as WalletModel;

class Wallet
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
    
    public function listWallets()
    {
        $wallets = WalletModel::where('user_id', $this->user->id)->get();

        return $wallets;
    }
}
