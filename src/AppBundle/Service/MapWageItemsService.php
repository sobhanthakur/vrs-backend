<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 1/11/19
 * Time: 2:31 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class MapWageItemsService
 * @package AppBundle\Service
 */
class MapWageItemsService extends BaseService
{
    /**
     * @param $customerID
     * @return array
     */
    public function FetchQBDWageItems($customerID)
    {
        try {
            $items = $this->entityManager->getRepository('AppBundle:Integrationqbdpayrollitemwages')->QBDPayrollItemWages($customerID);
            return array(
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => $this->translator->trans('api.response.success.message'),
                'Details' => $items
            );
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching Payroll item wages due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @param $customerID
     * @return array
     */
    public function UpdatePayrollMapping($customerID, $content)
    {
        try {
            /*
             * Read Request object. Extract attributes and parameters.
             */
            if(!array_key_exists(GeneralConstants::INTEGRATION_ID,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $content[GeneralConstants::INTEGRATION_ID];

            // Check if the record is present or not
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(['customerid'=>$customerID,'integrationid'=>$integrationID, 'qbdsyncpayroll'=>1,'active'=>1]);
            if(!$integrationToCustomer) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INTEGRATION_NOT_PRESENT);
            }

            // Update Pay By Hour if present
            if(array_key_exists(GeneralConstants::PAY_BY_HOUR,$content)) {
                $wageType = $this->entityManager->getRepository('AppBundle:Integrationqbdpayrollitemwages')->findOneBy(['integrationqbdpayrollitemwageid'=>$content[GeneralConstants::PAY_BY_HOUR]]);
                if(!$wageType) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_WAGE_ITEM_ID);
                }
                $integrationToCustomer->setIntegrationqbdhourwagetypeid($wageType);
            }

            // Update Pay By Rate if present
            if(array_key_exists(GeneralConstants::PAY_BY_RATE,$content)) {
                $wageType = $this->entityManager->getRepository('AppBundle:Integrationqbdpayrollitemwages')->findOneBy(['integrationqbdpayrollitemwageid'=>$content[GeneralConstants::PAY_BY_RATE]]);
                if(!$wageType) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_WAGE_ITEM_ID);
                }
                $integrationToCustomer->setIntegrationqbdratewagetypeid($wageType);
            }

            // Persist the record in DB.
            $this->entityManager->persist($integrationToCustomer);
            $this->entityManager->flush();
            return $this->serviceContainer->get('vrscheduler.api_response_service')->GenericSuccessResponse();
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to update Payroll Item Wage Mapping due To : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @param $customerID
     * @return array
     */
    public function GetPayrollMapping($customerID, $content)
    {
        try {
            /*
             * Read Request object. Extract attributes and parameters.
             */
            if(!array_key_exists(GeneralConstants::INTEGRATION_ID,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $content[GeneralConstants::INTEGRATION_ID];

            // Check if the record is present or not
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(['customerid'=>$customerID,'integrationid'=>$integrationID, 'qbdsyncpayroll'=>1,'active'=>1]);
            if(!$integrationToCustomer) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            return array(
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => $this->translator->trans('api.response.success.message'),
                'Details' => array(
                    GeneralConstants::PAY_BY_RATE => array(
                        'IntegrationQBDPayrollItemWageID' => $integrationToCustomer->getIntegrationqbdratewagetypeid()->getIntegrationqbdpayrollitemwageid(),
                        'QBDPayrollItemWageName' => trim($integrationToCustomer->getIntegrationqbdratewagetypeid()->getQbdpayrollitemwagename())
                    ),
                    GeneralConstants::PAY_BY_HOUR => array(
                        'IntegrationQBDPayrollItemWageID' => $integrationToCustomer->getIntegrationqbdhourwagetypeid()->getIntegrationqbdpayrollitemwageid(),
                        'QBDPayrollItemWageName' => trim($integrationToCustomer->getIntegrationqbdhourwagetypeid()->getQbdpayrollitemwagename())
                    ),
                )
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to fetch Payroll Item Wage Mapping due To : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}