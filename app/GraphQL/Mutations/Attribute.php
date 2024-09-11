<?php

namespace App\GraphQL\Mutations;

use App\Models\Attribute as Attributes;
use Illuminate\Support\Facades\Validator;

final class Attribute
{
    /**
     * Create a new attribute
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */
    public function createAttribute($root, array $args)
    {
        $validator = Validator::make($args, [
            'name' => 'required|string|max:255',
            'entity_type' => 'required|string|in:expense,income',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $attribute =  Attributes::create([
            'name' => $args['name'],
            'entity_type' => $args['entity_type'],
        ]);

        if (!$attribute) {
            throw new \Exception("Could not create attribute");
        }

        return $attribute;
    }

    /**
     * Update a Category
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */


    public function updateAttribute($root, array $args)
    {
        $validator = Validator::make($args, [
            'name' => 'required|string|max:255',
            'entity_type' => 'required|string|in:expense,income',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $attribute = Attributes::findOrFail($args['id']);

        if (!$attribute) {
            throw new \Exception('attribute not found');
        }

        $attribute->update([
            'name' => $args['name'],
            'entity_type' => $args['entity_type'],
        ]);

        return $attribute;
    }


    /**
     * Delete a attribute
     * @param  array  $args
     * @return Message
     * @throws \Exception
     */
    public function deleteAttribute($root, array $args)
    {
        $validator = Validator::make($args, [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $attribute = Attributes::findOrFail($args['id']);

        if (!$attribute) {
            throw new \Exception('attribute not found');
        }
        $attribute->delete();

        return [
            'message' => 'Successfully delete attribute '
        ];
    }
}
