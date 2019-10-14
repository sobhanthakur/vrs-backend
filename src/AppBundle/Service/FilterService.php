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
}