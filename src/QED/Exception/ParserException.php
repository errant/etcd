<?php
namespace QED\Exception;

class ParserException extends \Exception {

    public function __construct($response) 
    {
        parent::__construct('(' . $response['errorCode'] . ') ' . $response['message'] . ': ' . $response['cause']);  
    }
}