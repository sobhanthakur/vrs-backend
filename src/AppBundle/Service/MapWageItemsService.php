<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 1/11/19
 * Time: 2:31 PM
 */

namespace AppBundle\Service;


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
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => $items
            );
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching Payroll item wages due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}