<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 9/12/19
 * Time: 3:14 PM
 */

namespace AppBundle\QBDHelpers\Base;
use AppBundle\Constants\GeneralConstants;
use AppBundle\QBDHelpers\Response\Authenticate;
use AppBundle\QBDHelpers\Response\ClientVersion;
use AppBundle\QBDHelpers\Response\CloseConnection;
use AppBundle\QBDHelpers\Response\ConnectionError;
use AppBundle\QBDHelpers\Response\Debug;
use AppBundle\QBDHelpers\Response\GetInteractiveURL;
use AppBundle\QBDHelpers\Response\GetLastError;
use AppBundle\QBDHelpers\Response\InteractiveDone;
use AppBundle\QBDHelpers\Response\ServerVersion;
use AppBundle\Service\BaseService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;


/**
 * Class AbstractQBWCApplication
 * @package AppBundle\QBDHelpers\Base
 */
abstract class AbstractQBWCApplication implements QBWCApplicationInterface
{
    /**
     * @var array
     */
    public $_config = [];

    /**
     * @var EntityManager $entityManager
     */
    public $entityManager;

    /**
     * @var Container $serviceContainer
     */
    public $serviceContainer;

    /**
     * @var array
     */
    public $_session = [
        'iteratorId' => '',
    ];
    /**
     * @var
     */
    public $childTest;


    /**
     * @param $object
     * @return mixed
     */
    abstract public function sendRequestXML($object);

    /**
     * @param $object
     * @return mixed
     */
    abstract public function receiveResponseXML($object);

    /**
     * @param array $config
     */
    protected function initConfig($config = [])
    {
        $this->_config = [
            'serverVersion' => 'QBWCServer v1.0',
            'qbxmlVersion' => '13.0',
            'login' => '',
            'password' => '',

            'soapOptions' => [
                'cache_wsdl' => WSDL_CACHE_NONE,
            ],'wsdlPath' => '',
            'iterator' => [
                'maxReturned' => 100,
            ]
        ];

        $this->_config = array_merge($this->_config, $config);
    }

    /**
     * AbstractQBWCApplication constructor.
     * @param array $config
     * @param $entityManager
     */
    public function __construct($config = [], $entityManager,$serviceContainer)
    {
        $this->initConfig($config);
        $this->entityManager = $entityManager;
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param $object
     * @return Authenticate
     * @throws \Exception
     */
    public function authenticate($object)
    {
        $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('username'=>$object->strUserName));
        $wait_before_next_update = null;
        $min_run_every_n_seconds = null;
        $ticket = "";
        $status = "nvu";
        if($integrationToCustomer) {
            $encoder = $this->serviceContainer->get('security.password_encoder');
            $match = $encoder->isPasswordValid($integrationToCustomer,$object->strPassword);
            if($match) {
                $ticket = $this->generateGUID();
                $status = "";
            }
        }
        return new Authenticate($ticket, $status, $wait_before_next_update, $min_run_every_n_seconds);
    }

    /**
     * @param $object
     * @return ClientVersion
     */
    public function clientVersion($object)
    {
        return new ClientVersion('');
    }

    /**
     * @param $object
     * @return CloseConnection
     */
    public function closeConnection($object)
    {
        return new CloseConnection('Complete!');
    }

    /**
     * @param $object
     * @return ConnectionError
     */
    public function connectionError($object)
    {
        return new ConnectionError('Connection Error');
    }

    /**
     * @param $object
     * @return GetLastError
     */
    public function getLastError($object)
    {
        return new GetLastError('Get Last Error');
    }

    /**
     * @param $object
     * @return ServerVersion
     */
    public function serverVersion($object)
    {
        return new ServerVersion($this->_config['serverVersion']);
    }

    /**
     * @param null $iteratorId
     * @return string
     */
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

    /**
     * @return mixed
     */
    protected function getCurrentIteratorId()
    {
        return $_SESSION['iteratorId'];
    }

    /**
     * @param $value
     */
    protected function setCurrentIteratorId($value)
    {
        $_SESSION['iteratorId'] = $value;
    }

    /**
     * @param string $value
     * @return string
     */
    public function manageIteratorId($value = '')
    {
        if (trim($value) !== '') {
            $this->childTest = $value;
        }

        $this->log_this($this->childTest);

        return $this->childTest;
    }

    /**
     * @param $data
     */
    public function log_this($data)
    {
        $file_name = __DIR__ . '/main.log';
        $f = fopen($file_name, "a");
        fwrite($f, "\n ==============================================\n");
        fwrite($f, "[" . date("m / d / Y H:i:s") . "]\n");
        fwrite($f, $this->var_dump_to_string($data) . "\n");
    }

    /**
     * @param $var
     * @return false|string
     */
    public function var_dump_to_string($var)
    {
        ob_start();
        var_dump($var);
        return ob_get_clean();
    }

    /**
     * @param bool $surround
     * @return string
     */
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