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
//            $cc2 = $this->serviceContainer->getParameter('mailer_cc_2');

            $msg = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setCc($cc)
//                ->setBcc($cc2)
            ;

            if (array_key_exists('Source',$content) && $content['Source'] === 'FE') {
                $message = "<b>Exception Timing: </b>".$today->format("Y-m-d H:i:s")."<br/>";
                $message .= "<b>Error: </b>".$error."<br/>";
            } else {
                $requestHeader = $content['JWT'];
                $requestBody = $content['RequestContent'];

                // Generate the Body
                $message = "<b>Exception Timing: </b>".$today->format("Y-m-d H:i:s")."<br/>";
                $message .= "<b>ServicerID: </b>".$content['UserInfo']['ServicerID']."<br/>";
                $message .= "<b>CustomerID: </b>".$content['UserInfo']['CustomerID']."<br/>";
                $message .= "<b>JWT: </b>".$requestHeader."<br/>";
                $message .= "<b>Request Body: </b>".$requestBody."<br/>";
                $message .= "<b>Error: </b>".$error."<br/>";

                $today = $today->format('Y-m-d');
                $logPath1 = $this->serviceContainer->getParameter('kernel.logs_dir')."/exception-".$today.".log";
                $logPath2 = $this->serviceContainer->getParameter('kernel.logs_dir')."/apiRequestResponse-".$today.".log";

                if (file_exists($logPath1) && file_exists($logPath2)) {
                    $msg->attach(\Swift_Attachment::fromPath($logPath1)->setFilename("exception-".$today.".log"));
                    $msg->attach(\Swift_Attachment::fromPath($logPath2)->setFilename("apiRequestResponse-".$today.".log"));
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