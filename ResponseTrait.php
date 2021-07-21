<?php

namespace Suzuken\QLDBDriver;

trait ResponseTrait
{
    private function populate(array $data)
    {
        foreach ($data as $k => $v) {
            $this->setAttr($k, $v);
        }
    }

    private function setAttr(string $attr, $value)
    {
        $class = get_class($this);
        $prop = new \ReflectionProperty($class, $attr);
        $type = $prop->getType();
        if ($type === null) {
            $this->$attr = $value;
        }
        if ($type->allowsNull() && $value === null) {
            $this->$attr = null;
        }
        $name = $type->getName();
        switch ($name) {
            case 'int':
            case 'bool':
            case 'string':
                $this->$attr = $value;
            case 'array':
                $t = $this->readArrayVar($prop);
                if ($t === null) {
                    $this->$attr = $value;
                    break;
                }
                $this->$attr = array_map(fn ($v) => new $t($v), $value);
            default:
                // NOTE: user defined class.
                $this->$attr = new $name($value);
        }
    }

    private function readArrayVar(\ReflectionProperty $property): ?string
    {
        $docComment = $property->getDocComment();
        if (! $docComment) {
            return null;
        }
        // parse `@var Foo[]` as Foo.
        if (preg_match('/@var\s+([^\s]+)\[\]/', $docComment, $matches)) {
            [, $type] = $matches;
            return $type;
        }
        return null;
    }
}