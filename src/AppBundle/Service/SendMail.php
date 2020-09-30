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

            $message = "<b>JWT: </b>".$requestHeader."<br/>";
            $message .= "<b>Request Body: </b>".$requestBody."<br/>";
            $message .= "<b>Error: </b>".$error."<br/>";

            $to = $this->serviceContainer->getParameter('mailer_to');
            $from = $this->serviceContainer->getParameter('mailer_from');

            $msg = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setBody($message,'text/html')
            ;
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