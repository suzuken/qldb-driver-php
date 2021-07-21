<?php

namespace Suzuken\QLDBDriver;

class Transaction
{
    private Communicator $communicator;
    private ?string $id;
    private $commitHash;

    public function execute(string $statement, array $parameters = [])
    {
        $valueHolders = [];
        foreach ($parameters as $k => $v) {
            // TODO calculate parameter hash
            // TODO calculate execute hash
            // TODO marshal value to ion
            $bin = $v;
            $valueHolders[] = new ValueHolder(['IonBinary' => $bin]);
        }

        // TODO set commit hash

        // TODO verify hash
        return $this->communicator->executeStatement($valueHolders, $statement, $this->id);
    }
}