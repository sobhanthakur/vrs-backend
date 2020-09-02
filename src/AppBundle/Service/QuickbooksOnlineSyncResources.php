<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 17/2/20
 * Time: 11:05 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Entity\Customers;
use AppBundle\Entity\Integrationqbdcustomers;
use AppBundle\Entity\Integrationqbdemployees;
use AppBundle\Entity\Integrationqbditems;
use AppBundle\Entity\Integrationstocustomers;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\ServiceException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
    public function SyncResources($customerID, $quickbooksConfig,$integrationID)
    {
        $customerObj = $this->entityManager->getRepository('AppBundle:Customers')->findOneBy(array('customerid'=>$customerID));

        $integrationsToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('customerid'=>$customerID,'integrationid'=>$integrationID));
        if(!$integrationsToCustomers) {
            throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
        }

        $authService = $this->serviceContainer->get('vrscheduler.quickbooksonline_authentication');
        $integrationQBOTokens = $this->entityManager->getRepository('AppBundle:Integrationqbotokens')->findOneBy(array('customerid'=>$customerID));
        $dataService = null;
        try {
            if(!$integrationQBOTokens) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            // If Access and refresh tokens are not present in the table
            if(!$integrationQBOTokens->getRefreshToken() || !$integrationQBOTokens->getAccessToken()) {
                throw new UnprocessableEntityHttpException(ErrorConstants::OAUTH_FAILED);
            }

            // Authenticate
            $dataService = $authService->Authenticate($integrationQBOTokens, $quickbooksConfig);
            if(!$dataService) {
                throw new ServiceException('Unauthorized');
            }

            $this->PerformSyncOperations($dataService,$integrationsToCustomers,$customerObj);

        } catch (ServiceException $exception) {
            /*
             * This occurs when authentication fails using the existing access token.
             * Re-connect to QBO using the refresh token to get a new pair of tokens
             */
            $authService->RefreshAccessToken($dataService,$integrationQBOTokens);

            // Re-Login with new Updated Tokens
            $dataService = $authService->Authenticate($integrationQBOTokens, $quickbooksConfig);

            $this->PerformSyncOperations($dataService,$integrationsToCustomers,$customerObj);
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
     * @param Customers $customerObj
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function StoreCustomers($customers, $customerObj)
    {
        $incomingListIDs = [];
        foreach ($customers as $customer) {
            $integrationQBDCustomers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->findOneBy(array('qbdcustomerlistid'=>$customer->Id,'customerid' => $customerObj->getCustomerid()));
            if(!$integrationQBDCustomers) {
                $integrationQBDCustomers = new Integrationqbdcustomers();
            }
            $incomingListIDs[] = $customer->Id;
            $integrationQBDCustomers->setCustomerid($customerObj);
            $integrationQBDCustomers->setActive($customer->Active);
            $integrationQBDCustomers->setQbdcustomerfullname($customer->FullyQualifiedName);
            $integrationQBDCustomers->setQbdcustomerlistid($customer->Id);
            $this->entityManager->persist($integrationQBDCustomers);
        }

        // Fetch available Customers to keep track of customers that are made inactive in QBO
        $qbdListIDs = [];
        $qbdCustomers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->GetAllCustomers($customerObj->getCustomerid());
        foreach ($qbdCustomers as $val) {
            $qbdListIDs[] = $val['QBDCustomerListID'];
        }

        // Check if any record is deleted or not.
        $diffArray = array_diff($qbdListIDs, $incomingListIDs);
        foreach ($diffArray as $key => $value) {
            $qbdCustomers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->findOneBy(array('qbdcustomerlistid' => $value,'customerid' => $customerObj->getCustomerid()));
            $qbdCustomers->setActive(false);
        }
        $this->entityManager->flush();
        return true;
    }

    /**
     * @param $employees
     * @param Customers $customerObj
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function StoreEmployees($employees, $customerObj)
    {
        $incomingListIDs = [];
        foreach ($employees as $employee) {
            $integrationQBDEmployees = $this->entityManager->getRepository('AppBundle:Integrationqbdemployees')->findOneBy(array('qbdemployeelistid' => $employee->Id,'customerid' => $customerObj->getCustomerid()));
            if (!$integrationQBDEmployees) {
                $integrationQBDEmployees = new Integrationqbdemployees();
            }
            $incomingListIDs[] = $employee->Id;
            $integrationQBDEmployees->setCustomerid($customerObj);
            $integrationQBDEmployees->setActive($employee->Active);
            $integrationQBDEmployees->setQbdemployeefullname($employee->DisplayName);
            $integrationQBDEmployees->setQbdemployeelistid($employee->Id);
            $this->entityManager->persist($integrationQBDEmployees);
        }

        // Fetch available Employess to keep track of employees that are made inactive in QBO
        $qbdListIDs = [];
        $qbdEmployees = $this->entityManager->getRepository('AppBundle:Integrationqbdemployees')->GetAllEmployees($customerObj->getCustomerid());
        foreach ($qbdEmployees as $val) {
            $qbdListIDs[] = $val['QBDEmployeeListID'];
        }

        // Check if any record is deleted or not.
        $diffArray = array_diff($qbdListIDs, $incomingListIDs);
        foreach ($diffArray as $key => $value) {
            $qbdEmployees = $this->entityManager->getRepository('AppBundle:Integrationqbdemployees')->findOneBy(array('qbdemployeelistid' => $value,'customerid' => $customerObj->getCustomerid()));
            $qbdEmployees->setActive(false);
        }
        $this->entityManager->flush();
        return true;
    }

    /**
     * @param $items
     * @param Customers $customerObj
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function StoreItems($items, $customerObj)
    {
        $incomingItemListIDs = [];
        foreach ($items as $item) {
            if ($item->Type !== 'Category') {
                $integrationQBDItems = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->findOneBy(array('qbditemlistid'=>$item->Id,'customerid' => $customerObj->getCustomerid()));
                if(!$integrationQBDItems) {
                    $integrationQBDItems = new Integrationqbditems();
                }
                $incomingItemListIDs[] = $item->Id;
                $integrationQBDItems->setCustomerid($customerObj);
                $integrationQBDItems->setActive($item->Active);
                $integrationQBDItems->setQbditemfullname($item->FullyQualifiedName);
                $integrationQBDItems->setQbditemlistid($item->Id);
                $integrationQBDItems->setUnitprice($item->UnitPrice);
                $this->entityManager->persist($integrationQBDItems);
            }
        }


        // Fetch all the Items existing in DB and set active field to 0 for inactive items
        $qbdListIDs = [];
        $qbdItems = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->GetAllItems($customerObj->getCustomerid());
        foreach ($qbdItems as $val) {
            $qbdListIDs[] = $val['QBDItemListID'];
        }
        $diffArray = array_diff($qbdListIDs, $incomingItemListIDs);
        foreach ($diffArray as $key => $value) {
            $qbdItems = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->findOneBy(array('qbditemlistid' => $value,'customerid' => $customerObj->getCustomerid()));
            $qbdItems->setActive(false);
        }
        $this->entityManager->flush();
        return true;
    }


    /**
     * @param DataService $dataService
     * @param Integrationstocustomers $integrationsToCustomers
     * @param $customerObj
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function PerformSyncOperations($dataService, $integrationsToCustomers, $customerObj)
    {
        if (
            ($integrationsToCustomers->getQbdsyncpayroll() && (int)$integrationsToCustomers->getTimetrackingtype() === 1) ||
            $integrationsToCustomers->getQbdsyncbilling()) {
            // Fetch Customers
            $customers = $dataService->Query('select Active,FullyQualifiedName,Id from Customer MAXRESULTS 1000');
            if(!empty($customers)) {
                $this->StoreCustomers($customers, $customerObj);
            }
        }

        if($integrationsToCustomers->getQbdsyncbilling() || $integrationsToCustomers->getTimetrackingtype()) {

            // Fetch Items
            $items = $dataService->Query('select UnitPrice,Active,FullyQualifiedName,Id from Item MAXRESULTS 1000');
            if(!empty($items)) {
                $this->StoreItems($items,$customerObj);
            }
        }

        if($integrationsToCustomers->getQbdsyncpayroll()) {
            // Fetch Customers
            $employees = $dataService->Query('select Active,DisplayName,Id from Employee MAXRESULTS 1000');
            if(!empty($employees)) {
                $this->StoreEmployees($employees,$customerObj);
            }
        }

        return true;
    }
}