<?php

namespace App\Exception;

class ResourceValidationException extends \Exception
{
    public function __construct($error)
    {
        $message = "The JSON sent contains invalid data. ";
        foreach ($error as $err) {
            $message .= sprintf("Field %s: %s ", $err->getPropertyPath(), $err->getMessage());
        }
        parent::__construct($message);
    }
}
