<?php

namespace Slinpin;

class TypeResolver
{
    /**
     * @param \ReflectionParameter[] $parameters
     * @return string[]
     */
    public function resolveParameters(array $parameters)
    {
        $types = [];

        foreach ($parameters as $parameter)
        {
            $types[] = $parameter->getClass()->getName();
        }

        return $types;
    }

    /**
     * @param \ReflectionFunctionAbstract $value
     * @return \string[]
     */
    public function resolveMethod(\ReflectionFunctionAbstract $value)
    {
        return $this->resolveParameters($value->getParameters());
    }

    /**
     * @param \ReflectionClass $value
     * @return \string[]
     */
    public function resolveConstructor(\ReflectionClass $value)
    {
        $constructor = $value->getConstructor();

        if ($value->getConstructor() === null) {
            return [];
        }

        return $this->resolveMethod($constructor);
    }
}