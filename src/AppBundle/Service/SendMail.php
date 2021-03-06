<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 28/6/20
 * Time: 8:05 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use AppBundle\Constants\GeneralConstants;

/**
 * Class SendMail
 * @package AppBundle\Service
 */
class SendMail extends BaseService
{

    /**
     * @param $content
     * @return array | JsonResponse
     */
    public function SendMailFunction($content, $sendToDev = null)
    {
        $sent = null;
        try {
            $subject = $content['Subject'];
            $error = $content['Error'];
            $message = null;
            $today = new \DateTime('now');

            $to = $this->serviceContainer->getParameter('mailer_to');
            $from = $this->serviceContainer->getParameter('mailer_from');
            $cc = $this->serviceContainer->getParameter('mailer_cc');
            $cc2 = $this->serviceContainer->getParameter('mailer_cc_2');

            // Send only to Devs if SendToDev = 1
            if ($sendToDev) {
                $to = $cc;
            }

            $msg = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setCc([$cc,$cc2]);

            if (array_key_exists('Source',$content) && $content['Source'] === 'FE') {
                $message = "<b>Exception Timing: </b>".$today->format("Y-m-d H:i:s")."<br/>";
                $message .= "<b>Error: </b>".$error."<br/>";

                // Send SMS for FE Errors
                $textMessage = (new \DateTime('now'))->format('Y-m-d H:i:s')." - ".$error;
                $phoneNumbers = $this->serviceContainer->getParameter('PhoneNumbers');
                foreach ($phoneNumbers as $phoneNumber) {
                    $this->serviceContainer->get('vrscheduler.sms_service_for_errors')->ErrorSMS($textMessage,$phoneNumber);
                }
            } else {
                $requestHeader = $content['JWT'];
                $requestBody = $content['RequestContent'];
                $uri = $content['URI'];
                $content['Content-Length'] && (int)$content['Content-Length'] > 0 ? $contentLength = $content['Content-Length'] : $contentLength = 0;

                // Generate the Body
                $message = "<b>Exception Timing: </b>".$today->format("Y-m-d H:i:s")."<br/>";
                $message .= "<b>ServicerID: </b>".$content['UserInfo'][GeneralConstants::SERVICERID]."<br/>";
                $message .= "<b>CustomerID: </b>".$content['UserInfo'][GeneralConstants::CUSTOMER_ID]."<br/>";
                $message .= "<b>JWT: </b>".$requestHeader."<br/>";
//                $message .= "<b>Request Body: </b>".$requestBody."<br/>";
                $message .= "<b>URI: </b>".$uri."<br/>";
                $message .= "<b>Method: </b>".$content['Method']."<br/>";
                $message .= "<b>Content-Length: </b>".$contentLength." bytes<br/>";
                $message .= "<b>Content-Type: </b>".$content['Content-Type']." <br/>";
                $message .= "<b>User-Agent: </b>".$content['User-Agent']."<br/>";
                $message .= "<b>AWS SMS Response: </b>".$content['SMS']."<br/>";

                // Offline Header
                if ($content[GeneralConstants::OFFLINE] !== null) {
                    $message .= "<b>Offline: </b>".$content[GeneralConstants::OFFLINE]."<br/>";
                }
                $message .= "<b>Error: </b>".$error."<br/>";

                $today = $today->format('Y-m-d');
                $logPath1 = $this->serviceContainer->getParameter('kernel.logs_dir')."/exception-".$today.".log";

                // Read Last 5 Line of exception Log
                $file = file($logPath1);
                $exceptionMsg = '';
                for ($i = max(0, count($file)-5); $i < count($file); $i++) {
                    $exceptionMsg .= $file[$i];
                }

                if (file_exists($logPath1)) {
                    $msg->attach(\Swift_Attachment::newInstance($exceptionMsg,"exception-".$today.".log","text/plain"));
                    $msg->attach(\Swift_Attachment::newInstance($requestBody,"apiRequest-".$today.".log","text/plain"));
                }
            }

            $msg->setBody($message,'text/html');

            $sent = $this->serviceContainer->get('mailer')->send($msg);

            if (!$sent) {
                return new JsonResponse(['Message' => 'Mail Not Sent'],500);
            }

            return $this->serviceContainer->get('vrscheduler.api_response_service')->GenericSuccessResponse();

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Sending Mail due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}