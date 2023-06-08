<?php

namespace Tamiroh\PhpDataclass\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use Tamiroh\PhpDataclass\Data;

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
        $class = $node->class;
        $parentClass = get_parent_class($class->toString());

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

        $argValueTypes = [];
        foreach ($argValue->items as $item) {
            $key = $item?->key;
            $value = $item?->value;
            if (!$key instanceof Node\Scalar\String_) {
                return [
                    'Data class argument must be array with string key',
                ];
            }
            $argValueTypes[$key->value] = $scope->getType($value);
        }

        var_dump($argValueTypes);

        return [];
    }
}