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
        $this->sessionToken = $resp->StartSession->SessionToken;
    }

    public function endSession(): EndSessionResponse
    {
        $resp = $this->execute(['EndSession' => []]);
        return $resp->EndSession;
    }

    public function abortTransaction(): AbortTransactionResponse
    {
        $resp = $this->execute(['AbortTransaction' => []]);
        return $resp->AbortTransaction;
    }

    public function commitTransaction(string $digest, string $transactionId): CommitTransactionResponse
    {
        $resp = $this->execute(['CommitTransaction' => [
            'CommitDigest' => $digest, // REQUIRED
            'TransactionId' => $transactionId, // REQUIRED
        ]]);
        return $resp->CommitTransaction;
    }

    /**
     * @param ValueHolder[] $parameters
     * @param string $statement
     * @param string $transactionId
     * @return \Aws\Result
     */
    public function executeStatement(array $parameters, string $statement, string $transactionId): CommitTransactionResponse
    {
        $resp = $this->execute(['CommitTransaction' => [
            // see: https://stackoverflow.com/questions/4345554/convert-a-php-object-to-an-associative-array
            'Parameters' => json_decode(json_encode($parameters), true),
            'Statement' => $statement, // REQUIRED
            'TransactionId' => $transactionId, // REQUIRED
        ]]);
        return $resp->CommitTransaction;
    }

    public function fetchPage(string $nextPageToken, string $transactionId): FetchPageResponse
    {
        $resp = $this->execute(['FetchPage' => [
            'NextPageToken' => $nextPageToken,
            'TransactionId' => $transactionId, // REQUIRED
        ]]);
        return $resp->FetchPage;
    }

    public function startTransaction(): StartTransactionResponse
    {
        $resp = $this->execute(['StartTransaction' => []]);
        return $resp->StartTransaction;
    }

    public function execute($args): CommandOutput
    {
        $resp = $this->service->sendCommand($args);
        return new CommandOutput($resp);
    }
}
