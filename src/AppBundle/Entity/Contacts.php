<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contacts
 *
 * @ORM\Table(name="Contacts")
 * @ORM\Entity
 */
class Contacts
{
    /**
     * @var int
     *
     * @ORM\Column(name="ContactID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $contactid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Name", type="string", length=200, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Phone", type="string", length=200, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Email", type="string", length=200, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Software", type="string", length=200, nullable=true)
     */
    private $software;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Units", type="string", length=200, nullable=true)
     */
    private $units;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BestTime", type="string", length=200, nullable=true)
     */
    private $besttime;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Comments", type="string", length=1000, nullable=true)
     */
    private $comments;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdate = 'CURRENT_TIMESTAMP';



    /**
     * Get contactid.
     *
     * @return int
     */
    public function getContactid()
    {
        return $this->contactid;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Contacts
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return Contacts
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Contacts
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set software.
     *
     * @param string|null $software
     *
     * @return Contacts
     */
    public function setSoftware($software = null)
    {
        $this->software = $software;

        return $this;
    }

    /**
     * Get software.
     *
     * @return string|null
     */
    public function getSoftware()
    {
        return $this->software;
    }

    /**
     * Set units.
     *
     * @param string|null $units
     *
     * @return Contacts
     */
    public function setUnits($units = null)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * Get units.
     *
     * @return string|null
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set besttime.
     *
     * @param string|null $besttime
     *
     * @return Contacts
     */
    public function setBesttime($besttime = null)
    {
        $this->besttime = $besttime;

        return $this;
    }

    /**
     * Get besttime.
     *
     * @return string|null
     */
    public function getBesttime()
    {
        return $this->besttime;
    }

    /**
     * Set comments.
     *
     * @param string|null $comments
     *
     * @return Contacts
     */
    public function setComments($comments = null)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments.
     *
     * @return string|null
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Contacts
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
}
