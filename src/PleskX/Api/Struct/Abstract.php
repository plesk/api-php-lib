<?php

abstract class PleskX_Api_Struct_Abstract
{

    public function __set($property, $value)
    {
        throw new Exception("Try to set an undeclared property '$property'.");
    }

    /**
     * Initialize list of scalar properties by response
     *
     * @param SimpleXMLElement $apiResponse
     * @param array $properties
     * @throws Exception
     */
    protected function _initScalarProperties($apiResponse, array $properties)
    {
        foreach ($properties as $property) {
            $classPropertyName = $this->_underToCamel($property);
            $reflectionProperty = new ReflectionProperty($this, $classPropertyName);
            $docBlock = $reflectionProperty->getDocComment();
            $propertyType = preg_replace('/^.+ @var ([a-z]+) .+$/', '\1', $docBlock);

            $value = $apiResponse->$property;

            if ('string' == $propertyType) {
                $value = (string)$value;
            } else if ('integer' == $propertyType) {
                $value = (int)$value;
            } else {
                throw new Exception("Unknown property type '$propertyType'.");
            }

            $this->$classPropertyName = $value;
        }
    }

    /**
     * Convert underscore separated words into camel case
     *
     * @param string $under
     * @return string
     */
    private function _underToCamel($under)
    {
        $under = '_' . str_replace('_', ' ', strtolower($under));
        return ltrim(str_replace(' ', '', ucwords($under)), '_');
    }

}