<?php

namespace App\GraphQL\Mutations;

use App\Models\Value as Values;
use Illuminate\Support\Facades\Validator;

final class Value
{
    /**
     * Create a new Value
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */

    public function createValue($root, array $args)
    {
        $validator = Validator::make($args, [
            'attribute_id' => 'required|integer|exists:attributes,id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string|in:expense,income',
            'value' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $value = Values::create([
            'attribute_id' => $args['attribute_id'],
            'entity_id' => $args['entity_id'],
            'entity_type' => $args['entity_type'],
            'value' => $args['value'],
        ]);

        if (!$value) {
            throw new \Exception("Could not create value");
        }

        return $value;
    }
    /**
     * Update a value
     * @param  array  $args
     * @return WalletModel
     * @throws \Exception
     */

    public function updateValue($root, array $args)
    {
        $validator = Validator::make($args, [
            'attribute_id' => 'required|integer|exists:attributes,id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string|in:expense,income',
            'value' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $value = Values::findOrFail($args['id']);

        if (!$value) {
            throw new \Exception('value not found');
        }

        $value->update([
            'attribute_id' => $args['attribute_id'],
            'entity_id' => $args['entity_id'],
            'entity_type' => $args['entity_type'],
            'value' => $args['value'],
        ]);

        return $value;
    }
    /**
     * Delete a value
     * @param  array  $args
     * @return Message
     * @throws \Exception
     */
    public function deleteValue($root, array $args)
    {
        $validator = Validator::make($args, [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $value = Values::findOrFail($args['id']);

        if (!$value) {
            throw new \Exception('value not found');
        }
        $value->delete();

        return [
            'message' => 'Successfully delete value '
        ];
    }
}
