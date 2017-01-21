<?php
namespace Spotman\Acl;

class Exception extends \Exception
{
    public function __construct($message = "", array $variables = NULL, $code = 0, \Exception $previous = NULL)
    {
        // Replace key=>value pairs
        $message = empty($values) ? $message : strtr($message, $values);

        parent::__construct($message, $code, $previous);
    }
}
