<?php
namespace AMP\Exception\MusicPage;

class EnableUniqueConstraintFailedException extends \PDOException implements \AMP\Exception\ExceptionInterface
{
    use \AMP\Exception\ExceptionTrait;
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        $this->userMessage = 'We were unable to sort your music properly. Please try again later. ';
        parent::__construct($message, $code, $previous);
    }
}
