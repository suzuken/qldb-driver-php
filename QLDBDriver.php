<?php
namespace Suzuken\QLDBDriver;

use Aws\QLDBSession\QLDBSessionClient;

class QLDBDriver
{
    private string $ledgerName;
    private int $maxConcurrentTransactions;
    private bool $isClosed;
    private QLDBSessionClient $qldbSession;

    /**
     * QLDBDriver constructor.
     */
    public function __construct(string $ledgerName, QLDBSessionClient $session)
    {
        $this->ledgerName = $ledgerName;
        $this->qldbSession = $session;
    }

    /**
     * @return string
     */
    public function getLedgerName(): string
    {
        return $this->ledgerName;
    }

    /**
     * @return int
     */
    public function getMaxConcurrentTransactions(): int
    {
        return $this->maxConcurrentTransactions;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->isClosed;
    }

}