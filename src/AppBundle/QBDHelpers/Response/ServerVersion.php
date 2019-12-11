<?php
namespace AppBundle\QBDHelpers\Response;

/**
 * QuickBooks response object for responses to the ->getServerVersion() SOAP method call
 *
 * @package QBWCServer
 * @author Sobhan Thakur
 */

class ServerVersion
{
    /**
     * A string describing the server version
     *
     * @var string
     */
    public $serverVersionResult;

    /**
     * Create a new result object
     *
     * @param string $version
     */
    public function __construct($version)
    {
        $this->serverVersionResult = $version;
    }
}
