<?php


namespace App\Exceptions\Command;


use RuntimeException;
use Throwable;

final class CommandException extends RuntimeException
{
    public function __construct($message = "", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function fromError(Throwable $previous = null)
    {
        return new static('Command  was failed', $previous);
    }
}
