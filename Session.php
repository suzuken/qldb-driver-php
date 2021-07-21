<?php

namespace Suzuken\QLDBDriver;

class Session
{
    private Communicator $communicator;

    public function endSession()
    {
        $this->communicator->endSession();
    }
}