<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 14/10/19
 * Time: 3:58 PM
 */

namespace AppBundle\Service;
use AppBundle\Constants\GeneralConstants;

/**
 * Class FilterService
 * @package AppBundle\Service
 */
class FilterService extends BaseService
{

    /**
     * @param $customerID
     * @return array
     */
    public function PropertyGroupsFilter($customerID)
    {
        $propertyGroup = $this->entityManager->getRepository('AppBundle:Propertygroups')->GetPropertyGroupsRestrictions($customerID);
        return array(
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
            GeneralConstants::DATA => $propertyGroup
        );
    }

    /**
     * @param $customerID
     * @return array
     */
    public function RegionGroupsFilter($customerID)
    {
        $regionGroup = $this->entityManager->getRepository('AppBundle:Regiongroups')->GetRegionGroupsFilter($customerID);
        return array(
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
            GeneralConstants::DATA => $regionGroup
        );
    }

    /**
     * @param $customerID
     * @return array
     */
    public function OwnersFilter($customerID)
    {
        $owners = $this->entityManager->getRepository('AppBundle:Owners')->GetOwners($customerID);
        return array(
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
            GeneralConstants::DATA => $owners
        );
    }

    /**
     * @param $customerID
     * @return array
     */
    public function StaffTagFilter($customerID)
    {
        $staffTags = $this->entityManager->getRepository('AppBundle:Employeegroups')->GetEmployeeGroups($customerID);
        return array(
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
            GeneralConstants::DATA => $staffTags
        );
    }

    /**
     * @param $customerID
     * @return array
     */
    public function DepartmentsFilter($customerID)
    {
        $departments = $this->entityManager->getRepository('AppBundle:Servicegroups')->GetServiceGroups($customerID);
        return array(
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
            GeneralConstants::DATA => $departments
        );
    }

    /**
     * @param $customerID
     * @return array
     */
    public function PropertyFilter($customerID)
    {
        $properties = $this->entityManager->getRepository('AppBundle:Properties')->GetProperties($customerID);
        return array(
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
            GeneralConstants::DATA => $properties
        );
    }

    /**
     * @param $customerID
     * @return array
     */
    public function StaffFilter($customerID)
    {
        $staff = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->StaffFilter($customerID);
        return array(
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
            GeneralConstants::DATA => $staff
        );
    }
}