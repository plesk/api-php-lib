<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api;

abstract class AbstractStruct
{
    /**
     * @param string $property
     * @param mixed $value
     *
     * @throws \Exception
     */
    public function __set(string $property, $value)
    {
        throw new \Exception("Try to set an undeclared property '$property' to a value: $value.");
    }

    /**
     * Initialize list of scalar properties by response.
     *
     * @param \SimpleXMLElement $apiResponse
     * @param array $properties
     *
     * @throws \Exception
     */
    protected function initScalarProperties($apiResponse, array $properties): void
    {
        foreach ($properties as $property) {
            if (is_array($property)) {
                $classPropertyName = current($property);
                $value = $apiResponse->{key($property)};
            } else {
                $classPropertyName = $this->underToCamel(str_replace('-', '_', $property));
                $value = $apiResponse->$property;
            }

            $reflectionProperty = new \ReflectionProperty($this, $classPropertyName);
            $propertyType = $reflectionProperty->getType();
            if (is_null($propertyType)) {
                $docBlock = $reflectionProperty->getDocComment();
                $propertyType = preg_replace('/^.+ @var ([a-z]+) .+$/', '\1', $docBlock);
            } else {
                /** @psalm-suppress UndefinedMethod */
                $propertyType = $propertyType->getName();
            }

            if ('string' == $propertyType) {
                $value = (string) $value;
            } elseif ('int' == $propertyType) {
                $value = (int) $value;
            } elseif ('bool' == $propertyType) {
                $value = in_array((string) $value, ['true', 'on', 'enabled']);
            } else {
                throw new \Exception("Unknown property type '$propertyType'.");
            }

            $this->$classPropertyName = $value;
        }
    }

    /**
     * Convert underscore separated words into camel case.
     *
     * @param string $under
     *
     * @return string
     */
    private function underToCamel(string $under): string
    {
        $under = '_' . str_replace('_', ' ', strtolower($under));

        return ltrim(str_replace(' ', '', ucwords($under)), '_');
    }
}
