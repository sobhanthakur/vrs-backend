<?php
namespace AppBundle\QBDHelpers\Response;

/**
 * Result class for ->closeConnection() SOAP method
 *
 * @package QBWCServer
 * @author Sobhan Thakur
 */

class CloseConnection
{
    /**
     * A message indicating the connection has been closed/update was successful
     *
     * @var string
     */
    public $closeConnectionResult;

    /**
     * Create a new result object
     *
     * @param string $result A message indicating the connection has been closed
     */
    public function __construct($result)
    {
        $this->closeConnectionResult = $result;
    }
}

