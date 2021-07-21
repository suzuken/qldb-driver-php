<?php

namespace Suzuken\QLDBDriver\Tests;

use PHPUnit\Framework\TestCase;
use Suzuken\QLDBDriver\CommandOutput;

class CommunicatorTest extends TestCase
{
    public function testAbortOutput()
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

    public function testExecuteOutput()
    {

        $resp = [
            'ExecuteStatement' => [
                'ConsumedIOs' => [
                    'ReadIOs' => 100,
                    'WriteIOs' => 20,
                ],
                'FirstPage' => [
                    'NextPageToken' => 'foobar',
                    'Values' => [
                        [
                            'IonBinary' => 'foo',
                            'IonText' => 'bar',
                        ],
                    ],
                ],
                'TimingInformation' => [
                    'ProcessingTimeMilliseconds' => 500,
                ],
            ],
        ];
        $output = new CommandOutput($resp);
        $e = $output->ExecuteStatement;
        $this->assertSame(100, $e->ConsumedIOs->ReadIOs);
    }
}
