<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbotokens
 *
 * @ORM\Table(name="IntegrationQBOTokens")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbotokensRepository")
 */
class Integrationqbotokens
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBOTokensID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $integrationqbotokensid;

    /**
     * @var string
     *
     * @ORM\Column(name="AccessToken", type="string", length=500, nullable=true)
     */
    private $accessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="RefreshToken", type="string", length=500, nullable=true)
     */
    private $refreshToken;

    /**
     * @var string
     *
     * @ORM\Column(name="RealmID", type="string", length=50)
     */
    private $realmID;

    /**
     * @var Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CustomerID", referencedColumnName="CustomerID")
     * })
     */
    private $customerid;

    /**
     * Get integrationqbotokensid.
     *
     * @return int
     */
    public function getIntegrationqbotokensid()
    {
        return $this->integrationqbotokensid;
    }

    /**
     * Set accessToken.
     *
     * @param string|null $accessToken
     *
     * @return Integrationqbotokens
     */
    public function setAccessToken($accessToken = null)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set refreshToken.
     *
     * @param string|null $refreshToken
     *
     * @return Integrationqbotokens
     */
    public function setRefreshToken($refreshToken = null)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Get refreshToken.
     *
     * @return string|null
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set realmID.
     *
     * @param string $realmID
     *
     * @return Integrationqbotokens
     */
    public function setRealmID($realmID)
    {
        $this->realmID = $realmID;

        return $this;
    }

    /**
     * Get realmID.
     *
     * @return string
     */
    public function getRealmID()
    {
        return $this->realmID;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Integrationqbotokens
     */
    public function setCustomerid(\AppBundle\Entity\Customers $customerid = null)
    {
        $this->customerid = $customerid;

        return $this;
    }

    /**
     * Get customerid.
     *
     * @return \AppBundle\Entity\Customers|null
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }
}
