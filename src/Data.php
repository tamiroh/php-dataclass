<?php

namespace Tamiroh\PhpDataclass;

use Exception;
use ReflectionClass;
use UnexpectedValueException;

class Data
{
    /**
     * @throws Exception
     */
    final public function __set(string $name, $value): void
    {
        throw new Exception('Dynamically declaring properties is prohibited');
    }

    /**
     * @param array $attributes
     */
    final public function __construct(array $attributes = [])
    {
        $propertyNames = $this->getPropertyNames();

        // Assign values to properties
        // If a property that does not exist in the class is called, UnexpectedValueException is thrown

        foreach ($attributes as $key => $value) {
            if (!in_array($key, $propertyNames)) {
                throw new UnexpectedValueException("$$key is not defined in " . get_class($this));
            }
            $this->$key = $value;
        }

        // Throws UnexpectedValueException if non-null and uninitialized properties exist.
        // Assign null otherwise.

        $props = (new ReflectionClass($this))->getProperties();
        foreach ($props as $prop) {
            $propName = $prop->getName();
            $propType = $prop->getType();
            if (!isset($this->$propName)) {
                if ($propType !== null && !$propType->allowsNull()) {
                    throw new UnexpectedValueException(
                        "$$propName is not initialized even though it is non-null"
                    );
                }
                $this->$propName = null;
            }
        }
    }

    /**
     * @return array
     */
    final public function getAttributes(): array
    {
        return $this->getProperties();
    }

    /**
     * @return array
     */
    private function getProperties(): array
    {
        $propertyNames = [];
        $props = (new ReflectionClass($this))->getProperties();
        foreach ($props as $prop) {
            $propertyName = $prop->getName();
            $propertyNames[$propertyName] = $this->$propertyName ?? null;
        }
        return $propertyNames;
    }

    /**
     * @return array
     */
    private function getPropertyNames(): array
    {
        $propertyNames = [];
        $props = (new ReflectionClass($this))->getProperties();
        foreach ($props as $prop) {
            $propertyNames[] = $prop->getName();
        }
        return $propertyNames;
    }
}