<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Performingimports
 *
 * @ORM\Table(name="PerformingImports", indexes={@ORM\Index(name="NonClusteredIndex-20181217-160400", columns={"PropertyID"}), @ORM\Index(name="NonClusteredIndex-20181217-160444", columns={"LastPropertyID"})})
 * @ORM\Entity
 */
class Performingimports
{
    /**
     * @var int
     *
     * @ORM\Column(name="PerformingImportID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $performingimportid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDAte", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Note", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $note;

    /**
     * @var int|null
     *
     * @ORM\Column(name="LastPropertyID", type="integer", nullable=true)
     */
    private $lastpropertyid;

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
     * Get performingimportid.
     *
     * @return int
     */
    public function getPerformingimportid()
    {
        return $this->performingimportid;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Performingimports
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
     * Set note.
     *
     * @param string|null $note
     *
     * @return Performingimports
     */
    public function setNote($note = null)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string|null
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set lastpropertyid.
     *
     * @param int|null $lastpropertyid
     *
     * @return Performingimports
     */
    public function setLastpropertyid($lastpropertyid = null)
    {
        $this->lastpropertyid = $lastpropertyid;

        return $this;
    }

    /**
     * Get lastpropertyid.
     *
     * @return int|null
     */
    public function getLastpropertyid()
    {
        return $this->lastpropertyid;
    }

    /**
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Performingimports
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
}
