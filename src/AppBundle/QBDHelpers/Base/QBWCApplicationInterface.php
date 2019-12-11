<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 9/12/19
 * Time: 3:12 PM
 */

namespace AppBundle\QBDHelpers\Base;


interface QBWCApplicationInterface
{
    //string[] authenticate(string strUserName, string strPassword)
    public function authenticate($object);

    //string clientVersion(string strVersion)
    public function clientVersion($object);

    //string closeConnection(string ticket)
    public function closeConnection($object);

    //string connectionError(string ticket, string hresult, string message)
    public function connectionError($object);

    //string getInteractiveURL(string wcTicket, string sessionID)
    //    public function getInteractiveURL(string $wcTicket, string $sessionID);

    //string getLastError(string ticket)
    public function getLastError($object);

    //string getServerVersion(string ticket)
    public function serverVersion($object);

    //string interactiveDone(string wcTicket)
    //    public function interactiveDone(string $wcTicket);

    //string interactiveRejected(string wcTicket, string reason)
    //    public function interactiveRejected(string $wcTicket, string $reason);

    //int receiveResponseXML(string ticket, string response, string hresult, string message)
    public function receiveResponseXML($object);

    public function sendRequestXML($object);
}