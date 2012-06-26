<?php
namespace Pupcake\ResponseRenderJade;

class ConfigurationException extends \Exception
{
    //$message is now not optional, just for the extension.
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
