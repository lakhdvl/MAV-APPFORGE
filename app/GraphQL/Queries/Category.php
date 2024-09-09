<?php

namespace App\GraphQL\Queries;

use App\Models\Category as Categories;
use Illuminate\Support\Facades\Auth;

class Category 
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
    
    public function listCategories()
    {
        $category = Categories::where('user_id', $this->user->id)->get();
        return $category;
    }
}