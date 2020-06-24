<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 14/10/19
 * Time: 6:12 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
use function Couchbase\defaultDecoder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class PropertiesRepository
 * @package AppBundle\Repository
 */
class PropertiesRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function GetProperties($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.propertyid as PropertyID, p.propertyname as PropertyName')
            ->where('p.customerid= :CustomerID')
            ->andWhere('p.active=1')
            ->setParameter('CustomerID', $customerID)
            ->orderBy('p.propertyname','ASC')
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @param $propertyTags
     * @param $region
     * @param $owner
     * @param $createDate
     * @param $limit
     * @param $offset
     * @param $unmatched
     * @return mixed
     */
    public function PropertiesMap($customerID, $propertyTags, $region, $owner, $createDate, $limit, $offset, $unmatched)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('p')
            ->select('p.propertyid AS PropertyID, IDENTITY(m.integrationqbdcustomerid) AS IntegrationQBDCustomerID,p.propertyname AS PropertyName, p.propertyabbreviation as PropertyAbbreviation, r.region AS RegionName, o.ownername AS OwnerName')
            ->leftJoin('AppBundle:Integrationqbdcustomerstoproperties', 'm', Expr\Join::WITH, 'm.propertyid=p.propertyid')
            ->innerJoin('p.regionid', 'r')
            ->leftJoin('p.ownerid', 'o')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->orderBy('p.propertyname','ASC')
            ->andWhere('p.active=1');

        $result = $this->TrimMapProperties($result, $unmatched, $region, $owner, $propertyTags, $createDate);

        $result
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $propertyTags
     * @param $region
     * @param $owner
     * @param $createDate
     * @param $unmatched
     * @return mixed
     */
    public function CountPropertiesMap($customerID, $propertyTags, $region, $owner, $createDate, $unmatched)
    {
        $result = $this
            ->createQueryBuilder('p')
            ->select('count(p.propertyid)')
            ->leftJoin('AppBundle:Integrationqbdcustomerstoproperties', 'm', Expr\Join::WITH, 'm.propertyid=p.propertyid')
            ->innerJoin('p.regionid', 'r')
            ->innerJoin('p.ownerid', 'o')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('p.active=1');

        $result = $this->TrimMapProperties($result, $unmatched, $region, $owner, $propertyTags, $createDate);

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetPropertiesID($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.propertyid')
            ->where('p.customerid= :CustomerID')
            ->andWhere('p.active=1')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @param $properties
     * @return mixed
     */
    public function SearchPropertiesByID($customerID, $properties)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.propertyid')
            ->where('p.customerid= :CustomerID')
            ->andWhere('p.active=1')
            ->andWhere('p.propertyid IN (:Properties)')
            ->setParameter('Properties', $properties)
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $managersToProperties
     * @return mixed
     */
    public function GetRegionByID($managersToProperties)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('IDENTITY(p.regionid)')
            ->where('p.active=1')
            ->andWhere('p.propertyid IN (:Properties)')
            ->setParameter('Properties', $managersToProperties)
            ->getQuery()
            ->execute();
    }

    /**
     * @param QueryBuilder $result
     * @param $unmatched
     * @param $region
     * @param $owner
     * @param $propertyTags
     * @param $createDate
     * @return mixed
     */
    public function TrimMapProperties($result, $unmatched, $region, $owner, $propertyTags, $createDate)
    {
        if ($unmatched) {
            $result->andWhere('m.integrationqbdcustomerid IS NULL');
        }

        if ($region) {
            $result->andWhere('r.regiongroupid IN (:Regions)')
                ->setParameter('Regions', $region);
        }
        if ($owner) {
            $result->andWhere('p.ownerid IN (:Owners)')
                ->setParameter('Owners', $owner);
        }
        if ($propertyTags) {
            $result->andWhere('p.propertyid IN (:PropertyTags)')
                ->setParameter('PropertyTags', $propertyTags);
        }
        if ($createDate) {
            $result->andWhere('p.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }

        return $result;
    }

    /**
     * Function to parse and fetch property details according to query parameter
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $propertyID
     * @param $offset
     *
     * @return array
     */
    public function fetchProperties($customerDetails, $queryParameter, $propertyID, $offset, $query, $limit = null)
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('p');

        $result->select($query);

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('p.' . $field);
            }
        }

        //condition to filter by property id
        if (isset($propertyID)) {
            $result->andWhere('p.propertyid IN (:PropertyID)')
                ->setParameter('PropertyID', $propertyID);
        }

        //condition to filter by owner id
        if (isset($queryParameter['ownerid'])) {
            $result->andWhere('p.ownerid IN (:Owners)')
                ->setParameter('Owners', $queryParameter['ownerid']);
        }

        //condition to filter by region id
        if (isset($queryParameter['regionid'])) {
            $result->andWhere('p.regionid IN (:Region)')
                ->setParameter('Region', $queryParameter['regionid']);
        }

        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('p.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //condition to filter by customer details
        if (isset($limit)) {
            $result->setMaxResults($limit);
        }

        //return property details
        return $result
            ->innerJoin('p.ownerid', 'o')
            ->innerJoin('p.regionid', 'r')
            ->andWhere('p.active=1')
            ->setFirstResult(($offset - 1) * $limit)
            ->getQuery()
            ->execute();
    }

    /**
     * Function to fetch property details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $propertyID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $propertyID, $restriction, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all properties field
        $propertiesField = GeneralConstants::PROPERTIES_MAPPING;

        //Get properties restrict field
        $propertiesRestrictField = GeneralConstants::PROPERTIES_RESTRICTION;

        //Checking restrict personal data
        $restrictionPersonalData = $restriction->restrictPersonalData;

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $propertiesField[$field];
            }
        } else {
            if ($restrictionPersonalData) {
                $propertiesField = array_diff_key($propertiesField, array_flip($propertiesRestrictField));
            }
            $query .= implode(',', $propertiesField);
        }
        $query = trim($query, ',');

        return $this->fetchProperties($customerDetails, $queryParameter, $propertyID, $offset, $query, $limit);

    }

    /**
     * Function to get no. of properties of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $propertyID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $propertyID, $offset)
    {
        $query = "p.propertyid";
        return $this->fetchProperties($customerDetails, $queryParameter, $propertyID, $offset, $query);

    }

    /**
     * @param $propertyID
     * @return mixed
     */
    public function GetPropertyNameByID($propertyID)
    {
        return $this->createQueryBuilder('p')
            ->select('p.propertyname AS PropertyName')
            ->where("p.propertyid=".$propertyID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $servicerID
     * @return mixed
     */
    public function GetPropertiesForUnscheduledTask($servicerID)
    {
        return $this->createQueryBuilder('p')
            ->select('p.propertyname AS PropertyName,p.propertyid AS PropertyID')
            ->leftJoin('AppBundle:Servicerstoproperties', 'sp', Expr\Join::WITH, 'sp.propertyid=p.propertyid')
            ->where('sp.servicerid='.$servicerID)
            ->andWhere('p.active=1')
            ->orderBy('p.propertyname')
            ->getQuery()
            ->execute();
    }

    /**
     * @param $propertyID
     * @return mixed
     */
    public function PropertyTabUnscheduledTasks($propertyID)
    {
        return $this->createQueryBuilder('p')
            ->select('p.propertyid AS PropertyID')
            ->addSelect('p.propertyfile AS PropertyFile')
            ->addSelect('p.description AS Description')
            ->addSelect('p.address AS Address')
            ->addSelect('p.doorcode AS DoorCode')
            ->addSelect('p.propertyname AS PropertyName')
            ->addSelect('p.internalnotes AS InternalPropertyNotes')
            ->addSelect('p.staffdashboardnote AS StaffDashboardNote')
            ->where('p.propertyid='.$propertyID)
            ->getQuery()
            ->execute();

    }
}