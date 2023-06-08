<?php

namespace Tamiroh\PhpDataclass\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
class DataClassRule implements Rule
{

    public function getNodeType(): string
    {
        return Node\Expr\New_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $parentClass = get_parent_class($node->class->toString());

        if ($parentClass !== Data::class) {
            return [];
        }

        $args = $node->getRawArgs();

        if (count($args) !== 1) {
            return [
                'Data class must have only one argument',
            ];
        }

        $argValue = $args[0]->value;

        if (get_class($argValue) !== Node\Expr\Array_::class) {
            return [
                'Data class argument must be array',
            ];
        }

        $values = [];
        foreach ($argValue->items as $item) {
            $key = $item?->key;
            $value = $item?->value;
            if (!$key instanceof Node\Scalar\String_) {
                return [
                    'Data class argument must be array with string key',
                ];
            }
            $values[$key->value] = $scope->getType($value)->accepts(new \PHPStan\Type\NullType(), true);
        }

        var_dump($values);

        return [];
    }
}