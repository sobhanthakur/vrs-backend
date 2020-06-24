<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/6/20
 * Time: 2:03 PM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UnscheduledTask extends BaseService
{
    public function GetProperties($servicerID)
    {
        try {
            $properties = $this->entityManager->getRepository('AppBundle:Properties')->GetPropertiesForUnscheduledTask($servicerID);

            if (empty($properties)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }
            
            return array('Properties' => $properties);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to fetch properties ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

    }

    public function PropertyTab($servicerID, $content)
    {
        try {
            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->PropertyTabUnscheduledTasks($servicerID);

            if (empty($servicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            $servicers[0]['AllowAdminAccess'] = $servicers[0]['AllowAdminAccess'] ? 1 : 0;

            $propertyID = $content['PropertyID'];

            $properties = $this->entityManager->getRepository('AppBundle:Properties')->PropertyTabUnscheduledTasks($propertyID);
            if (empty($properties)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }

            return array(
                'PropertyDetails' => $properties[0],
                'ServicerDetails' => $servicers[0]
                );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to fetch properties ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

    }
}