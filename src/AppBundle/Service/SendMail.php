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
    public function SendMailFunction($content)
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

            $msg = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setCc($cc);

            if (array_key_exists('Source',$content) && $content['Source'] === 'FE') {
                $message = "<b>Exception Timing: </b>".$today->format("Y-m-d H:i:s")."<br/>";
                $message .= "<b>Error: </b>".$error."<br/>";
            } else {
                $requestHeader = $content['JWT'];
                $requestBody = $content['RequestContent'];
                $uri = $content['URI'];

                // Generate the Body
                $message = "<b>Exception Timing: </b>".$today->format("Y-m-d H:i:s")."<br/>";
                $message .= "<b>ServicerID: </b>".$content['UserInfo']['ServicerID']."<br/>";
                $message .= "<b>CustomerID: </b>".$content['UserInfo']['CustomerID']."<br/>";
                $message .= "<b>JWT: </b>".$requestHeader."<br/>";
//                $message .= "<b>Request Body: </b>".$requestBody."<br/>";
                $message .= "<b>URI: </b>".$uri."<br/>";
                $message .= "<b>Method: </b>".$content['Method']."<br/>";
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
                    $msg->attach(\Swift_Attachment::newInstance($requestBody,"apiRequestResponse-".$today.".log","text/plain"));
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