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
        $content = json_decode($request->getContent(),true);
        try {
            $params = array(
                'credentials' => ['key' => $aws['key'], 'secret' => $aws['secret']],
                'region' => $aws['region'], // < your aws from SNS Topic region
                'version' => 'latest'
            );
            $sns = new \Aws\Sns\SnsClient($params);

            $args = array(
                "MessageAttributes" => [
                    'AWS.SNS.SMS.SenderID' => [
                        'DataType' => 'String',
//                        'StringValue' => 'SENDERID'
                    ],
                    'AWS.SNS.SMS.SMSType' => [
                        'DataType' => 'String',
                        'StringValue' => 'Transactional'
                    ]
                ],
                "Message" => $content['Message'],
                "PhoneNumber" => $content['PhoneNumber']
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
        return substr($string, $ini, $len);
    }
}