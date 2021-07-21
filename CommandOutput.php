<?php

namespace Suzuken\QLDBDriver;

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

    public AbortTransactionResponse $AbortTransaction;
    public CommitTransactionResponse $CommitTransaction;
    public EndSessionResponse $EndSession;
    public ExecuteStatementResponse $ExecuteStatement;
    public FetchPageResponse $FetchPage;
    public StartSessionResponse $StartSession;
    public StartTransactionResponse $StartTransaction;
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
