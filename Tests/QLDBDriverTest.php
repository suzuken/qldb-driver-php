<?php

namespace Suzuken\QLDBDriver\Tests;

use Aws\QLDBSession\QLDBSessionClient;
use PHPUnit\Framework\TestCase;
use Suzuken\QLDBDriver\QLDBDriver;

class QLDBDriverTest extends TestCase {

    /**
     * @test
     */
    public function testName()
    {
        $qldbSession = new QLDBSessionClient([
            'region' => 'ap-northeast-1',
            'profile' => 'default',
            'version' => '2019-07-11',
        ]);
        $createdDriver = new QLDBDriver("test", $qldbSession);
        $this->assertSame("test", $createdDriver->getLedgerName());
    }
}