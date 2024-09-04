<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Income extends Model
{
    protected $fillable = ['user_id', 'category_id', 'wallet_id', 'amount', 'date', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    public function values()
    {
        return $this->morphMany(Value::class, 'entity');
    }
}