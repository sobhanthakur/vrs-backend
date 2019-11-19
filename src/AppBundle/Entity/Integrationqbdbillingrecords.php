<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbdbillingrecords
 *
 * @ORM\Table(name="IntegrationQBDBillingRecords", indexes={@ORM\Index(name="IDX_A4DF0BDCEF8DEFC9", columns={"TaskID"}), @ORM\Index(name="IDX_A4DF0BDCED4D199A", columns={"IntegrationQBBatchID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbdbillingrecordsRepository")
 */
class Integrationqbdbillingrecords
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDBillingRecordID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbdbillingrecordid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="TxnID", type="string", length=36, nullable=true)
     */
    private $txnid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ItemTxnID", type="string", length=36, nullable=true)
     */
    private $itemtxnid;

    /**
     * @var int
     *
     * @ORM\Column(name="Status", type="integer", nullable=false, options={"default"="1","comment"="0=Excluded,1=Approved,2=FailedToSend"})
     */
    private $status = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="SentStatus", type="boolean", nullable=true, options={"comment"="0=NotSentFromVRS, 1=SentFromVRS"})
     */
    private $sentstatus;

    /**
     * @var int|null
     *
     * @ORM\Column(name="RefNumber", type="integer", nullable=true)
     */
    private $refnumber;

    /**
     * @var \Tasks
     *
     * @ORM\ManyToOne(targetEntity="Tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TaskID", referencedColumnName="TaskID")
     * })
     */
    private $taskid;

    /**
     * @var \Integrationqbbatches
     *
     * @ORM\ManyToOne(targetEntity="Integrationqbbatches")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IntegrationQBBatchID", referencedColumnName="IntegrationQBBatchID")
     * })
     */
    private $integrationqbbatchid;



    /**
     * Get integrationqbdbillingrecordid.
     *
     * @return int
     */
    public function getIntegrationqbdbillingrecordid()
    {
        return $this->integrationqbdbillingrecordid;
    }

    /**
     * Set txnid.
     *
     * @param string|null $txnid
     *
     * @return Integrationqbdbillingrecords
     */
    public function setTxnid($txnid = null)
    {
        $this->txnid = $txnid;

        return $this;
    }

    /**
     * Get txnid.
     *
     * @return string|null
     */
    public function getTxnid()
    {
        return $this->txnid;
    }

    /**
     * Set itemtxnid.
     *
     * @param string|null $itemtxnid
     *
     * @return Integrationqbdbillingrecords
     */
    public function setItemtxnid($itemtxnid = null)
    {
        $this->itemtxnid = $itemtxnid;

        return $this;
    }

    /**
     * Get itemtxnid.
     *
     * @return string|null
     */
    public function getItemtxnid()
    {
        return $this->itemtxnid;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Integrationqbdbillingrecords
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrationqbdbillingrecords
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
     * Set sentstatus.
     *
     * @param bool|null $sentstatus
     *
     * @return Integrationqbdbillingrecords
     */
    public function setSentstatus($sentstatus = null)
    {
        $this->sentstatus = $sentstatus;

        return $this;
    }

    /**
     * Get sentstatus.
     *
     * @return bool|null
     */
    public function getSentstatus()
    {
        return $this->sentstatus;
    }

    /**
     * Set refnumber.
     *
     * @param int|null $refnumber
     *
     * @return Integrationqbdbillingrecords
     */
    public function setRefnumber($refnumber = null)
    {
        $this->refnumber = $refnumber;

        return $this;
    }

    /**
     * Get refnumber.
     *
     * @return int|null
     */
    public function getRefnumber()
    {
        return $this->refnumber;
    }

    /**
     * Set taskid.
     *
     * @param \AppBundle\Entity\Tasks|null $taskid
     *
     * @return Integrationqbdbillingrecords
     */
    public function setTaskid(\AppBundle\Entity\Tasks $taskid = null)
    {
        $this->taskid = $taskid;

        return $this;
    }

    /**
     * Get taskid.
     *
     * @return \AppBundle\Entity\Tasks|null
     */
    public function getTaskid()
    {
        return $this->taskid;
    }

    /**
     * Set integrationqbbatchid.
     *
     * @param \AppBundle\Entity\Integrationqbbatches|null $integrationqbbatchid
     *
     * @return Integrationqbdbillingrecords
     */
    public function setIntegrationqbbatchid(\AppBundle\Entity\Integrationqbbatches $integrationqbbatchid = null)
    {
        $this->integrationqbbatchid = $integrationqbbatchid;

        return $this;
    }

    /**
     * Get integrationqbbatchid.
     *
     * @return \AppBundle\Entity\Integrationqbbatches|null
     */
    public function getIntegrationqbbatchid()
    {
        return $this->integrationqbbatchid;
    }
}
