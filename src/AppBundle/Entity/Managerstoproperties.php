<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Managerstoproperties
 *
 * @ORM\Table(name="ManagersToProperties", indexes={@ORM\Index(name="ManagerServicerID", columns={"ManagerServicerID"}), @ORM\Index(name="PropertyID", columns={"PropertyID"})})
 * @ORM\Entity
 */
class Managerstoproperties
{
    /**
     * @var int
     *
     * @ORM\Column(name="ManagerToPropertyID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $managertopropertyid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdate = 'CURRENT_TIMESTAMP';

    /**
     * @var \Properties
     *
     * @ORM\ManyToOne(targetEntity="Properties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyID", referencedColumnName="PropertyID")
     * })
     */
    private $propertyid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ManagerServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $managerservicerid;



    /**
     * Get managertopropertyid.
     *
     * @return int
     */
    public function getManagertopropertyid()
    {
        return $this->managertopropertyid;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Managerstoproperties
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
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Managerstoproperties
     */
    public function setPropertyid(\AppBundle\Entity\Properties $propertyid = null)
    {
        $this->propertyid = $propertyid;

        return $this;
    }

    /**
     * Get propertyid.
     *
     * @return \AppBundle\Entity\Properties|null
     */
    public function getPropertyid()
    {
        return $this->propertyid;
    }

    /**
     * Set managerservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $managerservicerid
     *
     * @return Managerstoproperties
     */
    public function setManagerservicerid(\AppBundle\Entity\Servicers $managerservicerid = null)
    {
        $this->managerservicerid = $managerservicerid;

        return $this;
    }

    /**
     * Get managerservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getManagerservicerid()
    {
        return $this->managerservicerid;
    }
}
