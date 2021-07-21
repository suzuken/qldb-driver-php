<?php

namespace Suzuken\QLDBDriver;

class QLDBHash
{
    // NOTE: hash byte array
    private array $hash;
    private const hashSize = 32;

    public function __construct($value)
    {
        // TODO calculates ion hash from value.
        $this->hash = 'dummy' . $value;
    }
}