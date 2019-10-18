<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 16/10/19
 * Time: 2:35 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MapBillingService extends BaseService
{
    public function MapProperties($customerID, $data)
    {
        // Initialize variables
        try {
            $filters = null;
            $status = null;
            $propertyTags = null;
            $region = null;
            $owner = null;
            $createDate = null;
            $limit = 10;
            $offset = 1;
            $customersToProperties = null;
            $matchStatus = 2;
            if(!empty($data)) {
                $filters = array_key_exists('Filters',$data)? $data['Filters']:[];
                if(array_key_exists('Status',$filters)) {
                    $status = $filters['Status'];
                    $integrationqbdcustomerstoproperties = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomerstoproperties')->PropertiesJoinMatched($customerID);
                    if(!empty($integrationqbdcustomerstoproperties)) {
                        $customersToProperties = $integrationqbdcustomerstoproperties;

                        // If status is only set to matched
                        if(in_array(GeneralConstants::FILTER_MATCHED,$status) &&
                            !in_array(GeneralConstants::FILTER_NOT_MATCHED,$status)
                        ) {
                            $matchStatus = 1;
                        }

                        // If status is only set to not yet matched
                        if(!in_array(GeneralConstants::FILTER_MATCHED,$status) &&
                            in_array(GeneralConstants::FILTER_NOT_MATCHED,$status)
                        ) {
                            $matchStatus = 0;
                        }
                    }
                }
                if(array_key_exists('PropertyTag',$filters)) {
                    $propertyTags = $filters['PropertyTag'];
                }
                if(array_key_exists('Region',$filters)) {
                    $region = $filters['Region'];
                }
                if(array_key_exists('Owner',$filters)) {
                    $owner = $filters['Owner'];
                }
                if(array_key_exists('CreateDate',$filters)) {
                    $createDate = $filters['CreateDate'];
                }
                if(array_key_exists('Pagination',$data)) {
                    $limit = $data['Pagination']['Limit'];
                    $offset = $data['Pagination']['Offset'];
                }
            }

            $properties = $this->entityManager->getRepository('AppBundle:Properties')->SyncProperties($customersToProperties, $propertyTags, $region, $owner, $createDate, $limit, $offset, $customerID,$matchStatus);
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => $properties
            );
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed mapping properties due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}