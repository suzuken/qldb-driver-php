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
        $resp = $this->service->sendCommand($args);
        return new CommandOutput($resp);
    }
}

class Response
{
    use ResponseTrait;

    public function __construct(array $data)
    {
        $this->populate($data);
    }
}

class CommandOutput extends Response
{
    use ResponseTrait;

    public AbortTransactionResponse $AbortTransactionResponse;
    public CommitTransactionResponse $CommitTransactionResponse;
    public EndSessionResponse $EndSessionResponse;
    public ExecuteStatementResponse $ExecuteStatementResponse;
    public FetchPageResponse $FetchPageResponse;
    public StartSessionResponse $StartSessionResponse;
    public StartTransactionResponse $StartTransactionResponse;
}

class AbortTransactionResponse extends Response
{
    public TimingInformation $TimingInformation;
}

class CommitTransactionResponse extends Response
{
    public string $CommitDigest;
    public IOUsage $ConsumedIOs;
    public TimingInformation $TimingInformation;
    public string $TransactionId;
}

class EndSessionResponse extends Response
{
    public TimingInformation $TimingInformation;
}

class ExecuteStatementResponse extends Response
{
    public IOUsage $ConsumedIOs;
    public Page $FirstPage;
    public TimingInformation $TimingInformation;
}

class FetchPageResponse extends Response
{
    public IOUsage $ConsumedIOs;
    public Page $Page;
    public TimingInformation $TimingInformation;
}

class Page extends Response
{
    public string $NextPageToken;
    /**
     * @var ValueHolder[]
     */
    public array $Values;
}

class ValueHolder extends Response
{
    // TODO decode ion binary
    public string $IonBinary;
    public string $IonText;
}

class IOUsage extends Response
{
    public int $ReadIOs;
    public int $WriteIOs;
}

class StartTransactionResponse extends Response
{
    public string $TransactionId;
    public TimingInformation $TimingInformation;
}

class StartSessionResponse extends Response
{
    public string $SessionToken;
    public TimingInformation $TimingInformation;
}

class EndStatementResponse extends Response
{
    public TimingInformation $TimingInformation;
}

class TimingInformation extends Response
{
    public int $ProcessingTimeMilliseconds;
}