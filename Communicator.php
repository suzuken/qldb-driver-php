<?php

namespace Suzuken\QLDBDriver;

class Communicator
{
    private QLDBSessionAPIInterface $service;
    private string $sessionToken;

    /**
     * Communicator constructor.
     * @param string $ledgerName
     * @param QLDBSessionAPIInterface $service
     *
     * also creates new session for this communication process.
     */
    public function __construct(string $ledgerName, QLDBSessionAPIInterface $service)
    {
        $this->service = $service;
        $resp = $this->execute([
            'StartSession' => [
                'LedgerName' => $ledgerName,
            ],
        ]);
        $this->sessionToken = $resp['SessionToken'];
    }

    public function endSession()
    {
        return $this->execute(['EndSession' => []]);
    }

    public function abortTransaction()
    {
        return $this->execute(['AbortTransaction' => []]);
    }

    public function commitTransaction(string $digest, string $transactionId)
    {
        return $this->execute(['CommitTransaction' => [
            'CommitDigest' => $digest, // REQUIRED
            'TransactionId' => $transactionId, // REQUIRED
        ]]);
    }

    /**
     * @param ValueHolder[] $parameters
     * @param string $statement
     * @param string $transactionId
     * @return \Aws\Result
     */
    public function executeStatement(array $parameters, string $statement, string $transactionId)
    {
        return $this->execute(['CommitTransaction' => [
            // see: https://stackoverflow.com/questions/4345554/convert-a-php-object-to-an-associative-array
            'Parameters' => json_decode(json_encode($parameters), true),
            'Statement' => $statement, // REQUIRED
            'TransactionId' => $transactionId, // REQUIRED
        ]]);
    }

    public function fetchPage(string $nextPageToken, string $transactionId)
    {
        return $this->execute(['FetchPage' => [
            'NextPageToken' => $nextPageToken,
            'TransactionId' => $transactionId, // REQUIRED
        ]]);
    }

    public function startTransaction()
    {
        return $this->execute(['StartTransaction' => []]);
    }

    public function execute($args)
    {
        $resp = $this->service->sendCommand($args);
        return new CommandOutput($resp);
    }
}
