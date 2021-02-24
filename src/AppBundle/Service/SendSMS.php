<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 8/12/20
 * Time: 12:57 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class SendSMS
 * @package AppBundle\Service
 */
class SendSMS extends BaseService
{
    /**
     * @param Request $request
     * @return array
     */
    public function SendSMS($request)
    {
        $response = [];
        $aws = $this->serviceContainer->getParameter('aws')['sns'];
        $message = $request->getContent();

        // Parse Phone Number from the content
        $from1 = 'PhoneNumber';
        $to1 = ',';
        $phoneNumber = $this->get_string_between($message,$from1,$to1);
        $phoneNumber = trim(str_replace([':','"'],['',''],$phoneNumber));

        // Parse Message from the content
        $from2 = 'Message"';
        $msg = $this->get_string_between($message,$from2,null);
        $msg = trim(str_replace(
            ['"','}','\n','\"','\\','\/','\b','\f','\r','\t','\u'],
            ['','',' . ','','','','','','','',''],
                substr($msg,strpos($msg,'"'),-1))
        );
        // Sanitize Message


        try {
            $params = array(
                'credentials' => ['key' => $aws['key'], 'secret' => $aws['secret']],
                'region' => $aws['region'], // < your aws from SNS Topic region
                'version' => 'latest'
            );
            $sns = new \Aws\Sns\SnsClient($params);

            $args = array(
                "MessageAttributes" => [
//                    'AWS.SNS.SMS.SenderID' => [
//                        'DataType' => 'String',
//                        'StringValue' => 'VRSCHEDULER'
//                    ],
//                    'AWS.MM.SMS.OriginationNumber' => [
//                        'DataType' => 'String',
//                        'StringValue' => '+18335571169'
//                    ],
                    'AWS.SNS.SMS.SMSType' => [
                        'DataType' => 'String',
                        'StringValue' => 'Transactional'
                    ]
                ],
                "Message" => $msg,
                "PhoneNumber" => $phoneNumber
            );


            $result = $sns->publish($args);

            if ($result && $result->hasKey('MessageId')) {
                $response['MessageId'] = $result->get('MessageId');
            }

            return $response;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $str = $exception->getMessage();
            $from = '<Message>';
            $sub = substr($str, strpos($str,$from),strlen($str));
            $error = $this->get_string_between($sub,"<Message>","</Message>");
            $request->attributes->set('SMS',$error);

            $this->logger->error('Failed Sending SMS due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        if ($end === null) {
            $len = -1;
        }
        return substr($string, $ini, $len);
    }
}