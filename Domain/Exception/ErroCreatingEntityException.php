<?php
namespace General\EmailSending\Domain\Exception;

use Exception;

class ErroCreatingEntityException extends Exception
{
    public function __construct(string $entityName, int $code = 0, Exception $previous = null)
    {
        $message = "Erro ao criar $entityName";
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . "[{$this->code}]: {$this->message} \n";
    }
}
