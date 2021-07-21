<?php
namespace Suzuken\QLDBDriver;

use Aws\CommandInterface;
use Aws\QLDBSession\QLDBSessionClient;
use http\Exception\RuntimeException;

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
        $this->isClosed = false;
        $this->maxConcurrentTransactions = 1;
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

    public function execute(CommandInterface $fn)
    {
        if ($this->isClosed) {
            throw new RuntimeException("QLDBDriver is closed.");
        }

        try {
            $this->qldbSession->execute($fn);
        } catch (\Exception $e) {
        }
    }

};
