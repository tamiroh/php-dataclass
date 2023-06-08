<?php

namespace Tamiroh\PhpDataclass\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MissingPropertyFromReflectionException;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Tamiroh\PhpDataclass\Data;

/**
 * @implements Rule<Node\Expr\New_>
 */
class DataClassRule implements Rule
{
    private ReflectionProvider $reflectionProvider;

    public function __construct(
        ReflectionProvider $reflectionProvider
    ) {
        $this->reflectionProvider = $reflectionProvider;
    }

    public function getNodeType(): string
    {
        return Node\Expr\New_::class;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     * @throws MissingPropertyFromReflectionException
     */
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

        $reflectionProperties = (new ReflectionClass($class->toString()))->getProperties(ReflectionProperty::IS_PUBLIC);
        $nonNullReflectionProperties = array_filter(
            $reflectionProperties,
            fn (ReflectionProperty $property) => !$property->getType()?->allowsNull(),
        );
        $nonNullAndUninitializedReflectionProperties = array_filter(
            $nonNullReflectionProperties,
            fn (ReflectionProperty $property) => !$property->hasDefaultValue(),
        );
        $nonNullAndUninitializedReflectionPropertyNames = array_map(
            fn (ReflectionProperty $property) => $property->getName(),
            $nonNullAndUninitializedReflectionProperties,
        );

        $propertyNameDiff = count(array_diff($nonNullAndUninitializedReflectionPropertyNames, array_keys($argValueTypes)));

        if ($propertyNameDiff !== 0) {
            return [
                'Data class argument names must match with class properties',
            ];
        }

        $classReflection = $this->reflectionProvider->getClass($class->toString());
        foreach ($argValueTypes as $argName => $type) {
            $classPropertyType = $classReflection->getProperty($argName, $scope)->getWritableType();
            if ($classPropertyType->accepts($type, true)->no()) {
                return [
                    "Data class argument type mismatch for $argName",
                ];
            }
        }

        return [];
    }
}