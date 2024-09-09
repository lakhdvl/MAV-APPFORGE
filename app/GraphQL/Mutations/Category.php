<?php

namespace App\GraphQL\Mutations;

use App\Models\Category as Categories;
use Illuminate\Support\Facades\Auth;

final class Category
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function CreateCategory($root, array $args)
    {
        $user = Auth::user();

        return Categories::create([
            'user_id' => $user->id,
            'name' => $args['name'],
            'type' => $args['type'],
        ]);
    }

    public function UpdateCategory($root, array $args)
    {
        $category = Categories::where('id', $args['id'])->where('user_id', Auth::id())->firstOrFail();

        $category->update([
            'name' => $args['name'],
            'type' => $args['type'],
        ]);

        return $category;
    }

    public function DeleteCategory($root, array $args)
    {
        $category = Categories::where('id', $args['id'])->where('user_id', Auth::id())->firstOrFail();
        $category->delete();

        return [
            'message' => 'Successfully delete Category'
        ];
    }
}
