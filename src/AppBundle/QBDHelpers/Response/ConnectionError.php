<?php
namespace AppBundle\QBDHelpers\Response;

/**
 * Result container object for the SOAP ->connectionError() method call
 *
 * @package QBWCServer
 * @author Sobhan Thakur
 */

class ConnectionError
{
    /**
     * An error message
     *
     * @var string
     */
    public $connectionErrorResult;

    /**
     * Create a new result object
     *
     * @param string $err An error message describing the problem
     */
    public function __construct($err)
    {
        $this->connectionErrorResult = $err;
    }
}
