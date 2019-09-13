<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicestoservicers
 *
 * @ORM\Table(name="ServicesToServicers", indexes={@ORM\Index(name="IDX_26D9052C3C7E7BEF", columns={"ServicerID"}), @ORM\Index(name="IDX_26D9052C30F6DDC3", columns={"ServiceID"})})
 * @ORM\Entity
 */
class Servicestoservicers
{
    /**
     * @var int
     *
     * @ORM\Column(name="ServiceToServicerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $servicetoservicerid;

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnCheckout", type="integer", nullable=false)
     */
    private $notifyoncheckout = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyIfUrgent", type="integer", nullable=false)
     */
    private $notifyifurgent = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnCompletion", type="integer", nullable=false)
     */
    private $notifyoncompletion = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnDamage", type="integer", nullable=false)
     */
    private $notifyondamage = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnMaintenance", type="integer", nullable=false)
     */
    private $notifyonmaintenance = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnLostAndFound", type="integer", nullable=false)
     */
    private $notifyonlostandfound = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnSupplyFlag", type="integer", nullable=false)
     */
    private $notifyonsupplyflag = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnServicerNote", type="integer", nullable=false)
     */
    private $notifyonservicernote = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnNotYetDone", type="integer", nullable=false)
     */
    private $notifyonnotyetdone = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnNotYetDoneHours", type="integer", nullable=false, options={"default"="2"})
     */
    private $notifyonnotyetdonehours = '2';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnOverdue", type="integer", nullable=false)
     */
    private $notifyonoverdue = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnAccepted", type="integer", nullable=false)
     */
    private $notifyonaccepted = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnDeclined", type="integer", nullable=false)
     */
    private $notifyondeclined = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $servicerid;

    /**
     * @var \Services
     *
     * @ORM\ManyToOne(targetEntity="Services")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServiceID", referencedColumnName="ServiceID")
     * })
     */
    private $serviceid;



    /**
     * Get servicetoservicerid.
     *
     * @return int
     */
    public function getServicetoservicerid()
    {
        return $this->servicetoservicerid;
    }

    /**
     * Set notifyoncheckout.
     *
     * @param int $notifyoncheckout
     *
     * @return Servicestoservicers
     */
    public function setNotifyoncheckout($notifyoncheckout)
    {
        $this->notifyoncheckout = $notifyoncheckout;

        return $this;
    }

    /**
     * Get notifyoncheckout.
     *
     * @return int
     */
    public function getNotifyoncheckout()
    {
        return $this->notifyoncheckout;
    }

    /**
     * Set notifyifurgent.
     *
     * @param int $notifyifurgent
     *
     * @return Servicestoservicers
     */
    public function setNotifyifurgent($notifyifurgent)
    {
        $this->notifyifurgent = $notifyifurgent;

        return $this;
    }

    /**
     * Get notifyifurgent.
     *
     * @return int
     */
    public function getNotifyifurgent()
    {
        return $this->notifyifurgent;
    }

    /**
     * Set notifyoncompletion.
     *
     * @param int $notifyoncompletion
     *
     * @return Servicestoservicers
     */
    public function setNotifyoncompletion($notifyoncompletion)
    {
        $this->notifyoncompletion = $notifyoncompletion;

        return $this;
    }

    /**
     * Get notifyoncompletion.
     *
     * @return int
     */
    public function getNotifyoncompletion()
    {
        return $this->notifyoncompletion;
    }

    /**
     * Set notifyondamage.
     *
     * @param int $notifyondamage
     *
     * @return Servicestoservicers
     */
    public function setNotifyondamage($notifyondamage)
    {
        $this->notifyondamage = $notifyondamage;

        return $this;
    }

    /**
     * Get notifyondamage.
     *
     * @return int
     */
    public function getNotifyondamage()
    {
        return $this->notifyondamage;
    }

    /**
     * Set notifyonmaintenance.
     *
     * @param int $notifyonmaintenance
     *
     * @return Servicestoservicers
     */
    public function setNotifyonmaintenance($notifyonmaintenance)
    {
        $this->notifyonmaintenance = $notifyonmaintenance;

        return $this;
    }

    /**
     * Get notifyonmaintenance.
     *
     * @return int
     */
    public function getNotifyonmaintenance()
    {
        return $this->notifyonmaintenance;
    }

    /**
     * Set notifyonlostandfound.
     *
     * @param int $notifyonlostandfound
     *
     * @return Servicestoservicers
     */
    public function setNotifyonlostandfound($notifyonlostandfound)
    {
        $this->notifyonlostandfound = $notifyonlostandfound;

        return $this;
    }

    /**
     * Get notifyonlostandfound.
     *
     * @return int
     */
    public function getNotifyonlostandfound()
    {
        return $this->notifyonlostandfound;
    }

    /**
     * Set notifyonsupplyflag.
     *
     * @param int $notifyonsupplyflag
     *
     * @return Servicestoservicers
     */
    public function setNotifyonsupplyflag($notifyonsupplyflag)
    {
        $this->notifyonsupplyflag = $notifyonsupplyflag;

        return $this;
    }

    /**
     * Get notifyonsupplyflag.
     *
     * @return int
     */
    public function getNotifyonsupplyflag()
    {
        return $this->notifyonsupplyflag;
    }

    /**
     * Set notifyonservicernote.
     *
     * @param int $notifyonservicernote
     *
     * @return Servicestoservicers
     */
    public function setNotifyonservicernote($notifyonservicernote)
    {
        $this->notifyonservicernote = $notifyonservicernote;

        return $this;
    }

    /**
     * Get notifyonservicernote.
     *
     * @return int
     */
    public function getNotifyonservicernote()
    {
        return $this->notifyonservicernote;
    }

    /**
     * Set notifyonnotyetdone.
     *
     * @param int $notifyonnotyetdone
     *
     * @return Servicestoservicers
     */
    public function setNotifyonnotyetdone($notifyonnotyetdone)
    {
        $this->notifyonnotyetdone = $notifyonnotyetdone;

        return $this;
    }

    /**
     * Get notifyonnotyetdone.
     *
     * @return int
     */
    public function getNotifyonnotyetdone()
    {
        return $this->notifyonnotyetdone;
    }

    /**
     * Set notifyonnotyetdonehours.
     *
     * @param int $notifyonnotyetdonehours
     *
     * @return Servicestoservicers
     */
    public function setNotifyonnotyetdonehours($notifyonnotyetdonehours)
    {
        $this->notifyonnotyetdonehours = $notifyonnotyetdonehours;

        return $this;
    }

    /**
     * Get notifyonnotyetdonehours.
     *
     * @return int
     */
    public function getNotifyonnotyetdonehours()
    {
        return $this->notifyonnotyetdonehours;
    }

    /**
     * Set notifyonoverdue.
     *
     * @param int $notifyonoverdue
     *
     * @return Servicestoservicers
     */
    public function setNotifyonoverdue($notifyonoverdue)
    {
        $this->notifyonoverdue = $notifyonoverdue;

        return $this;
    }

    /**
     * Get notifyonoverdue.
     *
     * @return int
     */
    public function getNotifyonoverdue()
    {
        return $this->notifyonoverdue;
    }

    /**
     * Set notifyonaccepted.
     *
     * @param int $notifyonaccepted
     *
     * @return Servicestoservicers
     */
    public function setNotifyonaccepted($notifyonaccepted)
    {
        $this->notifyonaccepted = $notifyonaccepted;

        return $this;
    }

    /**
     * Get notifyonaccepted.
     *
     * @return int
     */
    public function getNotifyonaccepted()
    {
        return $this->notifyonaccepted;
    }

    /**
     * Set notifyondeclined.
     *
     * @param int $notifyondeclined
     *
     * @return Servicestoservicers
     */
    public function setNotifyondeclined($notifyondeclined)
    {
        $this->notifyondeclined = $notifyondeclined;

        return $this;
    }

    /**
     * Get notifyondeclined.
     *
     * @return int
     */
    public function getNotifyondeclined()
    {
        return $this->notifyondeclined;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Servicestoservicers
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
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Servicestoservicers
     */
    public function setServicerid(\AppBundle\Entity\Servicers $servicerid = null)
    {
        $this->servicerid = $servicerid;

        return $this;
    }

    /**
     * Get servicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getServicerid()
    {
        return $this->servicerid;
    }

    /**
     * Set serviceid.
     *
     * @param \AppBundle\Entity\Services|null $serviceid
     *
     * @return Servicestoservicers
     */
    public function setServiceid(\AppBundle\Entity\Services $serviceid = null)
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    /**
     * Get serviceid.
     *
     * @return \AppBundle\Entity\Services|null
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }
}
