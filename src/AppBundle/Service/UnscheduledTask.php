<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/6/20
 * Time: 2:03 PM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\DatabaseViews\Issues;
use AppBundle\DatabaseViews\Servicers;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class UnscheduledTask
 * @package AppBundle\Service
 */
class UnscheduledTask extends BaseService
{
    /**
     * @param $servicerID
     * @return array
     */
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

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
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

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function ImageTab($servicerID, $content)
    {
        try {
            $propertyID = $content['PropertyID'];
            $images = $this->entityManager->getRepository('AppBundle:Images')->GetImagesForImageTab($propertyID);
            return array(
                'Images' => $images
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

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function UnscheduledTaskDetails($servicerID, $content)
    {
        try {
            $response = [];
            $propertyID = $content['PropertyID'];
            $log = 0;
            $property = 0;
            $image = 0;
            $manage = 1;

            // Get Servicers Details
            $servicers = 'Select ShowIssuesLog,TaskName,IncludeServicerNote,IncludeToOwnerNote,DefaultToOwnerNote FROM ('.Servicers::vServicers.') AS S WHERE  S.ServicerID = '.$servicerID.'
             AND S.CustomerActive = 1 and S.Active = 1';
            $servicers = $this->entityManager->getConnection()->prepare($servicers);
            $servicers->execute();
            $servicers = $servicers->fetchAll();

            if (empty($servicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            // Get PropertyDetails
            $properties = $this->entityManager->getRepository('AppBundle:Properties')->PropertyDetailsForUnscheduledTasks($propertyID);
            if (empty($properties)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }

            $response['Details'] = array_merge($servicers[0],$properties[0]);

            // Get Tab Details

            // Log Tab
            $staffTasks = $this->entityManager->getConnection()->prepare('SELECT TOP 1 CreateDate,Issue,FromTaskID,SubmittedByServicerID,CustomerName,SubmittedByName,TimeZoneRegion,Urgent,IssueType,PropertyID,Notes FROM ('.Issues::vIssues.') AS SubQuery WHERE SubQuery.ClosedDate IS NULL AND SubQuery.PropertyID='.$propertyID.' ORDER BY SubQuery.CreateDate DESC');
            $staffTasks->execute();
            $staffTasks = $staffTasks->fetchAll();

            if (!empty($staffTasks)) {
                $log = 1;
            }

            // Property Tab
            $properties = $this->entityManager->getRepository('AppBundle:Properties')->PropertyTabUnscheduledTasks($propertyID);
            if (!empty($properties)) {
                $property = 1;
            }

            // Images Tab
            $images = $this->entityManager->getRepository('AppBundle:Images')->GetImagesForImageTab($propertyID,null,1);
            if (!empty($images)) {
                $image = 1;
            }


            // Create Tab Response
            $response['Tabs'] = array(
                'Log' => $log,
                'Property' => $property,
                'Image' => $image,
                'Manage' => $manage
            );

            return $response;

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to fetch Unscheduled task details ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}