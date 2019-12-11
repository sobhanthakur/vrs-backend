<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 9/12/19
 * Time: 3:14 PM
 */

namespace AppBundle\QBDHelpers\Base;
use AppBundle\QBDHelpers\Response\Authenticate;
use AppBundle\QBDHelpers\Response\ClientVersion;
use AppBundle\QBDHelpers\Response\CloseConnection;
use AppBundle\QBDHelpers\Response\ConnectionError;
use AppBundle\QBDHelpers\Response\Debug;
use AppBundle\QBDHelpers\Response\GetInteractiveURL;
use AppBundle\QBDHelpers\Response\GetLastError;
use AppBundle\QBDHelpers\Response\InteractiveDone;
use AppBundle\QBDHelpers\Response\ServerVersion;


/**
 * Class AbstractQBWCApplication
 * @package AppBundle\QBDHelpers\Base
 */
abstract class AbstractQBWCApplication implements QBWCApplicationInterface
{
    public $_config = [];

    public $_session = [
        'iteratorId' => '',
    ];
    public $childTest;


    abstract public function sendRequestXML($object);

    abstract public function receiveResponseXML($object);

    /**
     * @param array $config
     */
    protected function initConfig($config = [])
    {
        $this->_config = [
            'serverVersion' => 'QBWCServer v1.0',
            'qbxmlVersion' => '13.0',
            'login' => 'login',
            'password' => 'password',
            'wsdlPath' => __DIR__ . '/../WSDL/qbwebconnectorsvc.wsdl',
            'soapOptions' => [
                'cache_wsdl' => WSDL_CACHE_NONE,
            ],
            'iterator' => [
                'maxReturned' => 100,
            ]
        ];

        $this->_config = array_merge($this->_config, $config);
    }

    public function __construct($config = [])
    {
        $this->initConfig($config);
    }

    public function authenticate($object)
    {
        $wait_before_next_update = null;
        $min_run_every_n_seconds = null;
        if ($object->strUserName == $this->_config['login'] && $object->strPassword == $this->_config['password']) {
            $ticket = $this->generateGUID();
            $status = "";
        } else {
            $ticket = "";
            $status = "nvu";
        }

        return new Authenticate($ticket, $status, $wait_before_next_update, $min_run_every_n_seconds);

    }

    public function clientVersion($object)
    {
        return new ClientVersion('');
    }

    public function closeConnection($object)
    {
        return new CloseConnection('Complete!');
    }

    public function connectionError($object)
    {
        return new ConnectionError('Connection Error');
    }

    public function getLastError($object)
    {
        return new GetLastError('Get Last Error');
    }

    public function serverVersion($object)
    {
        return new ServerVersion($this->_config['serverVersion']);
    }

    protected function _buildIterator($iteratorId = null)
    {
        $xml = "";

        if (empty($this->_config['iterator'])) {
            return $xml;
        }

        if (!empty($iteratorId)) {
            $xml .= ' iterator="Continue" iteratorID="' . $iteratorId . '" ';
        } else {
            $xml .= ' iterator="Start" ';
        }

        $xml .= '>' . "\n";


        $xml .= "\t" . '<MaxReturned>';
        $xml .= $this->_config['iterator']['maxReturned'];
        $xml .= '</MaxReturned';


        return $xml;
    }

    protected function getCurrentIteratorId()
    {
        return $_SESSION['iteratorId'];
    }

    protected function setCurrentIteratorId($value)
    {
        $_SESSION['iteratorId'] = $value;
    }

    public function manageIteratorId($value = '')
    {
        if (trim($value) !== '') {
            $this->childTest = $value;
        }

        $this->log_this($this->childTest);

        return $this->childTest;
    }

    public function log_this($data)
    {
//        $file_name = './log/vardump.log';
        $file_name = __DIR__ . '/../log/main.log';
        $f = fopen($file_name, "a");
//        $f = fopen($file_name, "r + ");
        fwrite($f, "\n ==============================================\n");
        fwrite($f, "[" . date("m / d / Y H:i:s") . "]\n");
        fwrite($f, $this->var_dump_to_string($data) . "\n");
    }

    public function var_dump_to_string($var)
    {
        ob_start();
        var_dump($var);
        return ob_get_clean();
    }

    public function generateGUID($surround = true)
    {
        $ticketId = sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
            mt_rand(0, 65535), mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 4095),
            bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
            mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)
        );
        if ($surround) {
            $ticketId = "{{$ticketId}}";
        }

        return $ticketId;
    }
}