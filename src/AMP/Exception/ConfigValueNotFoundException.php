<?php
namespace AMP\Exception;

class ConfigValueNotFoundException extends \Exception
{
    protected $message = 'Config value not found.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $this->message = 'Config value not found for key ' . $message;
        parent::__construct($this->message, $code, $previous);
    }
}
