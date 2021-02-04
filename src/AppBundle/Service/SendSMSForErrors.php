<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 4/2/21
 * Time: 12:12 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SendSMSForErrors extends BaseService
{
    public function ErrorSMS($message,$phoneNumber)
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
                "Message" => $message,
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
            $this->logger->error('Failed Sending SMS due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

}