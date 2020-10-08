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
        try {
            $subject = $content['Subject'];
            $requestHeader = $content['JWT'];
            $requestBody = $content['RequestContent'];
            $error = $content['Error'];
            $today = new \DateTime('now');

            $message = "<b>Exception Timing: </b>".$today->format("Y-m-d H:i:s")."<br/>";
            $message .= "<b>ServicerID: </b>".$content['UserInfo']['ServicerID']."<br/>";
            $message .= "<b>CustomerID: </b>".$content['UserInfo']['CustomerID']."<br/>";
            $message .= "<b>JWT: </b>".$requestHeader."<br/>";
            $message .= "<b>Request Body: </b>".$requestBody."<br/>";
            $message .= "<b>Error: </b>".$error."<br/>";

            $to = $this->serviceContainer->getParameter('mailer_to');
            $from = $this->serviceContainer->getParameter('mailer_from');
            $cc = $this->serviceContainer->getParameter('mailer_cc');

            $today = $today->format('Y-m-d');
            $logPath1 = $this->serviceContainer->getParameter('kernel.logs_dir')."/exception-".$today.".log";
            $logPath2 = $this->serviceContainer->getParameter('kernel.logs_dir')."/apiRequestResponse-".$today.".log";

            $msg = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setCc($cc)
                ->setBody($message,'text/html')
            ;

            if (file_exists($logPath1 && $logPath2)) {
                $msg->attach(\Swift_Attachment::fromPath($logPath1)->setFilename("exception-".$today.".log"));
                $msg->attach(\Swift_Attachment::fromPath($logPath2)->setFilename("apiRequestResponse-".$today.".log"));
            }

            $sent = $this->serviceContainer->get('mailer')->send($msg);

            if (!$sent) {
                return new JsonResponse(['Message' => 'Mail Not Sent'],500);
            }

            return array('Message' => 'Mail sent');

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