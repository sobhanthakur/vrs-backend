<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrations
 *
 * @ORM\Table(name="Integrations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationsRepository")
 */
class Integrations
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Integration", type="string", length=50, nullable=true)
     */
    private $integration;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Logo", type="string", length=50, nullable=true)
     */
    private $logo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=true)
     */
    private $sortorder = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdate = 'CURRENT_TIMESTAMP';



    /**
     * Get integrationid.
     *
     * @return int
     */
    public function getIntegrationid()
    {
        return $this->integrationid;
    }

    /**
     * Set integration.
     *
     * @param string|null $integration
     *
     * @return Integrations
     */
    public function setIntegration($integration = null)
    {
        $this->integration = $integration;

        return $this;
    }

    /**
     * Get integration.
     *
     * @return string|null
     */
    public function getIntegration()
    {
        return $this->integration;
    }

    /**
     * Set logo.
     *
     * @param string|null $logo
     *
     * @return Integrations
     */
    public function setLogo($logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo.
     *
     * @return string|null
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set sortorder.
     *
     * @param int|null $sortorder
     *
     * @return Integrations
     */
    public function setSortorder($sortorder = null)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder.
     *
     * @return int|null
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Integrations
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrations
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
