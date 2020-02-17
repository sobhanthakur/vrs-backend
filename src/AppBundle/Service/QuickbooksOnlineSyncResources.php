<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 17/2/20
 * Time: 11:05 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Entity\Integrationqbdcustomers;
use AppBundle\Entity\Integrationqbditems;
use QuickBooksOnline\API\Exception\ServiceException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class QuickbooksOnlineSyncResources
 * @package AppBundle\Service
 */
class QuickbooksOnlineSyncResources extends BaseService
{
    /**
     * @param $customerID
     * @param $quickbooksConfig
     * @return array
     * @throws ServiceException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \QuickBooksOnline\API\Exception\SdkException
     */
    public function SyncResources($customerID, $quickbooksConfig)
    {
        $customerObj = $this->entityManager->getRepository('AppBundle:Customers')->findOneBy(array('customerid'=>$customerID));
        $authService = $this->serviceContainer->get('vrscheduler.quickbooksonline_authentication');
        $integrationQBOTokens = $this->entityManager->getRepository('AppBundle:Integrationqbotokens')->findOneBy(array('customerid'=>$customerID));
        $dataService = null;
        try {
            if(!$integrationQBOTokens) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if(!$integrationQBOTokens->getRefreshToken() || !$integrationQBOTokens->getAccessToken()) {
                throw new UnprocessableEntityHttpException(ErrorConstants::OAUTH_FAILED);
            }

            // Authenticate
            $dataService = $authService->Authenticate($integrationQBOTokens, $quickbooksConfig);
            if(!$dataService) {
                throw new ServiceException('Unauthorized');
            }

            // Fetch Customers
            $customers = $dataService->Query('select Active,FullyQualifiedName,Id from Customer');
            $this->StoreCustomers($customers,$customerObj);

            // Fetch Items
            $items = $dataService->Query('select Active,FullyQualifiedName,Id from Item');
            $this->StoreItems($items,$customerObj);
        } catch (ServiceException $exception) {
            $authService->RefreshAccessToken($dataService,$integrationQBOTokens);

            // Re-Login with new Updated Tokens
            $dataService = $authService->Authenticate($integrationQBOTokens, $quickbooksConfig);

            // Fetch Customers
            $customers = $dataService->Query('select Active,FullyQualifiedName,Id from Customer');
            $this->StoreCustomers($customers,$customerObj);

            // Fetch Items
            $items = $dataService->Query('select Active,FullyQualifiedName,Id from Item');
            $this->StoreItems($items,$customerObj);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching QBO Resources due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $this->serviceContainer->get('vrscheduler.api_response_service')->GenericSuccessResponse();
    }

    /**
     * @param $customers
     * @param $customerObj
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function StoreCustomers($customers, $customerObj)
    {
        foreach ($customers as $customer) {
            $integrationQBDCustomers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->findOneBy(array('qbdcustomerlistid'=>$customer->Id));
            if(!$integrationQBDCustomers) {
                $integrationQBDCustomers = new Integrationqbdcustomers();
            }
            $integrationQBDCustomers->setCustomerid($customerObj);
            $integrationQBDCustomers->setActive($customer->Active);
            $integrationQBDCustomers->setQbdcustomerfullname($customer->FullyQualifiedName);
            $integrationQBDCustomers->setQbdcustomerlistid($customer->Id);
            $this->entityManager->persist($integrationQBDCustomers);
        }
        $this->entityManager->flush();
        return true;
    }

    /**
     * @param $items
     * @param $customerObj
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function StoreItems($items, $customerObj)
    {
        foreach ($items as $item) {
            $integrationQBDItems = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->findOneBy(array('qbditemlistid'=>$item->Id));
            if(!$integrationQBDItems) {
                $integrationQBDItems = new Integrationqbditems();
            }
            $integrationQBDItems->setCustomerid($customerObj);
            $integrationQBDItems->setActive($item->Active);
            $integrationQBDItems->setQbditemfullname($item->FullyQualifiedName);
            $integrationQBDItems->setQbditemlistid($item->Id);
            $this->entityManager->persist($integrationQBDItems);
        }
        $this->entityManager->flush();
        return true;
    }
}