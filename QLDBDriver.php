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

    public function endSession()
    {
        return $this->qldbSession->sendCommand(['EndSession' => []]);
    }

    public function abortTransaction()
    {
        return $this->qldbSession->sendCommand(['AbortTransaction' => []]);
    }

    public function commitTransaction(string $digest, string $transactionId)
    {
        return $this->qldbSession->sendCommand(['CommitTransaction' => [
            'CommitDigest' => $digest, // REQUIRED
            'TransactionId' => $transactionId, // REQUIRED
        ]]);
    }

    /**
     * @param ExecuteStatementRequest[] $parameters
     * @param string $statement
     * @param string $transactionId
     * @return \Aws\Result
     */
    public function executeStatement(array $parameters, string $statement, string $transactionId)
    {
        return $this->qldbSession->sendCommand(['CommitTransaction' => [
            // see: https://stackoverflow.com/questions/4345554/convert-a-php-object-to-an-associative-array
            'Parameters' => json_decode(json_encode($parameters), true),
            'Statement' => $statement, // REQUIRED
            'TransactionId' => $transactionId, // REQUIRED
        ]]);
    }

    public function fetchPage(string $nextPageToken, string $transactionId)
    {
        return $this->qldbSession->sendCommand(['FetchPage' => [
            'NextPageToken' => $nextPageToken,
            'TransactionId' => $transactionId, // REQUIRED
        ]]);
    }

    public function startSession()
    {
        return $this->execute([
            'StartSession' => [
                'LedgerName' => $this->ledgerName,
            ],
        ]);
    }

    public function startTransaction()
    {
        return $this->qldbSession->sendCommand(['StartTransaction' => []]);
    }
};

class ExecuteStatementRequest
{
    public string $ionBinary;
    public string $ionText;
}