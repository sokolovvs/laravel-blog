<?php


class ResourceNotFoundException extends RuntimeException
{
    public function __construct($message = "Resource not found", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
