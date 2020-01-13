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
use AppBundle\Entity\Integrationqbdcustomerstoproperties;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class MapPropertiesService
 * @package AppBundle\Service
 */
class MapPropertiesService extends BaseService
{
    /**
     * @param $customerID
     * @param $data
     * @return array
     */
    public function MapProperties($customerID, $data)
    {
        try {
            // Initialize variables
            $filters = null;
            $status = null;
            $propertyTags = null;
            $region = null;
            $owner = null;
            $createDate = null;
            $limit = 10;
            $offset = 1;
            $customersToProperties = null;
            $integrationID = null;
            $count = null;
            $response = null;
            $flag = null;
            $unmatched = null;

            if(!array_key_exists('IntegrationID',$data)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $data['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncBilling is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncBillingEnabled($integrationID,$customerID);
            if(empty($integrationToCustomers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if (!empty($data)) {
                $filters = array_key_exists('Filters', $data) ? $data['Filters'] : [];

                if (array_key_exists('PropertyTag', $filters)) {
                    $propertyTags = $this->entityManager->getRepository('AppBundle:Propertiestopropertygroups')->PropertiestoPropertyGroupsJoinMatched($filters['PropertyTag']);
                }
                if (array_key_exists('Region', $filters)) {
                    $region = $filters['Region'];
                }
                if (array_key_exists('Owner', $filters)) {
                    $owner = $filters['Owner'];
                }
                if (array_key_exists('CreateDate', $filters)) {
                    $createDate = $filters['CreateDate'];
                }
                if (array_key_exists('Pagination', $data)) {
                    $limit = $data['Pagination']['Limit'];
                    $offset = $data['Pagination']['Offset'];
                }

                if (array_key_exists('Status', $filters)) {
                    $status = $filters['Status'];

                    // If status is only set to matched
                    if (in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        !in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        if($offset === 1) {
                            $count = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomerstoproperties')->CountPropertiesJoinMatched($customerID,$propertyTags, $region, $owner, $createDate);
                            if($count) {
                                $count = (int)$count[0][1];
                            }
                        }
                        $response = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomerstoproperties')->PropertiesJoinMatched($customerID,$propertyTags, $region, $owner, $createDate, $limit, $offset);
                        $flag = 1;
                    }

                    // If status is only set to not yet matched
                    if (!in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        $unmatched = true;
                        $flag = 0;
                    }
                }
            }

            // Default Case
            if(!$flag) {
                if($offset === 1) {
                    $count = $this->entityManager->getRepository('AppBundle:Properties')->CountPropertiesMap($customerID,$propertyTags, $region, $owner, $createDate,$unmatched);

                    if($count) {
                        $count = (int)$count[0][1];
                    }
                }
                $response = $this->entityManager->getRepository('AppBundle:Properties')->PropertiesMap($customerID,$propertyTags, $region, $owner, $createDate, $limit, $offset,$unmatched);
            }

            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => array(
                    'Count' => $count,
                    'Details' => $response
                )
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed mapping properties due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $customerID
     * @return array
     */
    public function FetchCustomers($customerID)
    {
        try {
            $customers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->QBDCustomers($customerID);
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => $customers
            );
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching customers due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function MapPropertiesToCustomers($customerID, $content)
    {
        try {
            if(!array_key_exists('IntegrationID',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }

            $integrationID = $content['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncBilling is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncBillingEnabled($integrationID,$customerID);
            if(empty($integrationToCustomers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if(!array_key_exists('Data',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_DATA);
            }

            $data = $content['Data'];

            // Traverse data to create/update mappings
            for ($i = 0; $i < count($data); $i++) {
                $customersToProperties = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomerstoproperties')->findOneBy(
                    array(
                        'propertyid' => $data[$i][GeneralConstants::PROPERTY_ID]
                    )
                );


                if($data[$i][GeneralConstants::INTEGRATION_QBD_CUSTOMER_ID]) {
                    $integrationQBDCustomers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->findOneBy(array(
                            'integrationqbdcustomerid' => $data[$i][GeneralConstants::INTEGRATION_QBD_CUSTOMER_ID]
                        )
                    );

                    // Check if the integration QBD Customer is present. Or if the customer ID is valid or not
                    if(!$integrationQBDCustomers ||
                        ($integrationQBDCustomers !== null?($integrationQBDCustomers->getCustomerid()->getCustomerid() !== $customerID):null)
                    ) {
                        throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATIONQBDCUSTOMERID);
                    }
                }


                // Integration QBD Customers To Properties exist, then simply update the record with the new IntegrationQBDCustomerID
                if (!$customersToProperties) {
                    // Create New Record
                    $property = $this->entityManager->getRepository('AppBundle:Properties')->findOneBy(array(
                            'propertyid' => $data[$i][GeneralConstants::PROPERTY_ID]
                        )
                    );
                    if(!$property ||
                        ($property !== null?($property->getCustomerid()->getCustomerid() !== $customerID):null)
                    ) {
                        throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
                    }

                    $customersToProperties = new Integrationqbdcustomerstoproperties();

                    $customersToProperties->setIntegrationqbdcustomerid($integrationQBDCustomers);
                    $customersToProperties->setPropertyid($property);

                    $this->entityManager->persist($customersToProperties);
                } else {
                    // Update the record
                    if(!$data[$i][GeneralConstants::INTEGRATION_QBD_CUSTOMER_ID]) {
                        $this->entityManager->remove($customersToProperties);
                    } else {
                        $customersToProperties->setIntegrationqbdcustomerid($integrationQBDCustomers);
                        $this->entityManager->persist($customersToProperties);
                    }
                }
            }
            $this->entityManager->flush();

            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message')
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Saving mapped information for Properties due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}
