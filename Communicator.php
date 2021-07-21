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
     * @param ExecuteStatementRequest[] $parameters
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
        return $this->service->sendCommand($args);
    }
}

class AbortTransactionResponse
{
    public TimingInformation $TimingInformation;
}

class CommitTransactionResponse
{
    public string $CommitDigest;
    public IOUsage $ConsumedIOs;
    public TimingInformation $TimingInformation;
    public string $TransactionId;
}

class EndSessionResponse
{
    public TimingInformation $TimingInformation;
}

class ExecuteStatementResponse
{
    public IOUsage $ConsumedIOs;
    public Page $FirstPage;
    public TimingInformation $TimingInformation;
}

class FetchPageResponse
{
    public IOUsage $ConsumedIOs;
    public Page $Page;
    public TimingInformation $TimingInformation;
}

class Page
{
    public string $NextPageToken;
    /**
     * @var ValueHolder[]
     */
    public array $Values;
}

class ValueHolder
{
    // TODO decode ion binary
    public string $IonBinary;
    public string $IonText;
}

class IOUsage
{
    public int $ReadIOs;
    public int $WriteIOs;
}

class StartTransactionResponse
{
    public string $TransactionId;
    public TimingInformation $TimingInformation;
}

class StartSessionResponse
{
    public string $SessionToken;
    public TimingInformation $TimingInformation;
}

class EndStatementResponse
{
    public TimingInformation $TimingInformation;
}

class TimingInformation
{
    public int $ProcessingTimeMilliseconds;
}