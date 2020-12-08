<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 8/12/20
 * Time: 12:57 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SendSMS extends BaseService
{
    public function SendSMS($content)
    {
        $response = [];
        $aws = $this->serviceContainer->getParameter('aws')['sns'];
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
                        'StringValue' => 'SENDERID'
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
            $this->logger->error('Failed Sending SMS due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}