<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 14/10/19
 * Time: 3:58 PM
 */

namespace AppBundle\Service;


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
            'ReasonCode' => 0,
            'ReasonText' => $this->translator->trans('api.response.success.message'),
            'Data' => $propertyGroup
        );
    }

    /**
     * @param $customerID
     * @return array
     */
    public function RegionGroupsFilter($customerID)
    {
        $regionGroup = $this->entityManager->getRepository('AppBundle:Regiongroups')->GetRegionGroupsRestrictions($customerID);
        return array(
            'ReasonCode' => 0,
            'ReasonText' => $this->translator->trans('api.response.success.message'),
            'Data' => $regionGroup
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
            'ReasonCode' => 0,
            'ReasonText' => $this->translator->trans('api.response.success.message'),
            'Data' => $owners
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
            'ReasonCode' => 0,
            'ReasonText' => $this->translator->trans('api.response.success.message'),
            'Data' => $staffTags
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
            'ReasonCode' => 0,
            'ReasonText' => $this->translator->trans('api.response.success.message'),
            'Data' => $departments
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
            'ReasonCode' => 0,
            'ReasonText' => $this->translator->trans('api.response.success.message'),
            'Data' => $properties
        );
    }

    /**
     * @param $customerID
     * @return array
     */
    public function StaffFilter($customerID)
    {
        $staff = $this->entityManager->getRepository('AppBundle:Servicers')->StaffFilter($customerID);
        return array(
            'ReasonCode' => 0,
            'ReasonText' => $this->translator->trans('api.response.success.message'),
            'Data' => $staff
        );
    }
}