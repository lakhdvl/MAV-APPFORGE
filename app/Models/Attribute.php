<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';

    protected $fillable = ['name', 'entity_type'];

    public function values()
    {
        return $this->hasMany(Value::class);
    }
}
