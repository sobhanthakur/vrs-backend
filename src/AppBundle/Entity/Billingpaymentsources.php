<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billingpaymentsources
 *
 * @ORM\Table(name="BillingPaymentSources")
 * @ORM\Entity
 */
class Billingpaymentsources
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingPaymentSourceID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billingpaymentsourceid;

    /**
     * @var string
     *
     * @ORM\Column(name="BillingPaymentSource", type="string", length=50, nullable=false)
     */
    private $billingpaymentsource;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';



    /**
     * Get billingpaymentsourceid.
     *
     * @return int
     */
    public function getBillingpaymentsourceid()
    {
        return $this->billingpaymentsourceid;
    }

    /**
     * Set billingpaymentsource.
     *
     * @param string $billingpaymentsource
     *
     * @return Billingpaymentsources
     */
    public function setBillingpaymentsource($billingpaymentsource)
    {
        $this->billingpaymentsource = $billingpaymentsource;

        return $this;
    }

    /**
     * Get billingpaymentsource.
     *
     * @return string
     */
    public function getBillingpaymentsource()
    {
        return $this->billingpaymentsource;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Billingpaymentsources
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
