<?php

namespace Suzuken\QLDBDriver\Tests;

use PHPUnit\Framework\TestCase;
use Suzuken\QLDBDriver\CommandOutput;

class CommunicatorTest extends TestCase
{
    public function testOutput()
    {
        $resp = [
            'AbortTransaction' => [
                'TimingInformation' => [
                    'ProcessingTimeMilliseconds' => 100
                ]
            ]
        ];
        $output = new CommandOutput($resp);
        $this->assertSame(100, $output->AbortTransaction->TimingInformation->ProcessingTimeMilliseconds);
    }
}
