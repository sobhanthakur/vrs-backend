<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbbatches
 *
 * @ORM\Table(name="IntegrationQBBatches", indexes={@ORM\Index(name="IDX_6CC1D73B854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbbatchesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Integrationqbbatches
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBBatchID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbbatchid;

    /**
     * @var bool
     *
     * @ORM\Column(name="BatchType", type="boolean", nullable=false, options={"comment"="0=Blling & 1=TimeTracking"})
     */
    private $batchtype;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false)
     */
    private $createdate;

    /**
     * @var \Integrationstocustomers
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Integrationstocustomers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IntegrationToCustomerID", referencedColumnName="IntegrationToCustomerID")
     * })
     */
    private $integrationtocustomer;



    /**
     * Get integrationqbbatchid.
     *
     * @return int
     */
    public function getIntegrationqbbatchid()
    {
        return $this->integrationqbbatchid;
    }

    /**
     * Set batchtype.
     *
     * @param bool $batchtype
     *
     * @return Integrationqbbatches
     */
    public function setBatchtype($batchtype)
    {
        $this->batchtype = $batchtype;

        return $this;
    }

    /**
     * Get batchtype.
     *
     * @return bool
     */
    public function getBatchtype()
    {
        return $this->batchtype;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrationqbbatches
     */
    public function setCreatedate($createdate)
    {
        $this->createdate = $createdate;

        return $this;
    }

    /**
     * Get createdate.
     *
     * @return \DateTime
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->getCreatedate() == null) {
            $datetime = new \DateTime('now', new \DateTimeZone('UTC'));
            $this->setCreatedate($datetime);
        }
    }

    /**
     * Set integrationtocustomer.
     *
     * @param \AppBundle\Entity\Integrationstocustomers|null $integrationtocustomer
     *
     * @return Integrationqbbatches
     */
    public function setIntegrationtocustomer(\AppBundle\Entity\Integrationstocustomers $integrationtocustomer = null)
    {
        $this->integrationtocustomer = $integrationtocustomer;

        return $this;
    }

    /**
     * Get integrationtocustomer.
     *
     * @return \AppBundle\Entity\Integrationstocustomers|null
     */
    public function getIntegrationtocustomer()
    {
        return $this->integrationtocustomer;
    }
}
