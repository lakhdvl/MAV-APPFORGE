<?php

namespace App\GraphQL\Mutations;

use App\Models\Category as Categories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use GraphQL\Error\Error;

final class Category
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */

    /**
     * Create a new Category
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */
    public function CreateCategory($root, array $args)
    {
        $validator = Validator::make($args, [
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,user_defined',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $user = Auth::user();

        $categories =  Categories::create([
            'user_id' => $user->id,
            'name' => $args['name'],
            'type' => $args['type'],
        ]);

        if (!$categories) {
            throw new \Exception('Could not create Category');
        }
        return $categories;
    }
    /**
     * Update a Category
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */

    public function UpdateCategory($root, array $args)
    {
        $validator = Validator::make($args, [
            'id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,user_defined',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $category = Categories::where('id', $args['id'])->where('user_id', Auth::id())->first();

        if (!$category) {
            throw new \Exception('category not found');
        }

        if (isset($args['name'])) {
            $category->name = $args['name'];
        }

        if (isset($args['balance'])) {
            $category->type = $args['type'];
        }

        $category->update([
            'name' => $args['name'],
            'type' => $args['type'],
        ]);

        return $category;
    }

    /**
     * Delete a Category
     * @param  array  $args
     * @return Message
     * @throws \Exception
     */
    public function DeleteCategory($root, array $args)
    {
        $validator = Validator::make($args, [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $category = Categories::where('id', $args['id'])->where('user_id', Auth::id())->first();

        if (!$category) {
            throw new \Exception('Category not found');
        }
        $category->delete();

        return [
            'message' => 'Successfully delete Category'
        ];
    }
}
