<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customerreports
 *
 * @ORM\Table(name="CustomerReports", indexes={@ORM\Index(name="IDX_FE919B95854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Customerreports
{
    /**
     * @var int
     *
     * @ORM\Column(name="CustomerReportID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $customerreportid;

    /**
     * @var int
     *
     * @ORM\Column(name="CustomerReportType", type="integer", nullable=false, options={"comment"="1 = service history report"})
     */
    private $customerreporttype;

    /**
     * @var string|null
     *
     * @ORM\Column(name="URLString", type="text", length=-1, nullable=true)
     */
    private $urlstring;

    /**
     * @var int
     *
     * @ORM\Column(name="Status", type="integer", nullable=false, options={"comment"="0 = pending, 1 = completed"})
     */
    private $status = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Filename", type="string", length=200, nullable=true)
     */
    private $filename;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NumberOfRecords", type="integer", nullable=true)
     */
    private $numberofrecords;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NumberOfRecordsExported", type="integer", nullable=true)
     */
    private $numberofrecordsexported;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ReportCompleteDate", type="datetime", nullable=true)
     */
    private $reportcompletedate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="GenerationDate", type="datetime", nullable=true)
     */
    private $generationdate;

    /**
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CustomerID", referencedColumnName="CustomerID")
     * })
     */
    private $customerid;



    /**
     * Get customerreportid.
     *
     * @return int
     */
    public function getCustomerreportid()
    {
        return $this->customerreportid;
    }

    /**
     * Set customerreporttype.
     *
     * @param int $customerreporttype
     *
     * @return Customerreports
     */
    public function setCustomerreporttype($customerreporttype)
    {
        $this->customerreporttype = $customerreporttype;

        return $this;
    }

    /**
     * Get customerreporttype.
     *
     * @return int
     */
    public function getCustomerreporttype()
    {
        return $this->customerreporttype;
    }

    /**
     * Set urlstring.
     *
     * @param string|null $urlstring
     *
     * @return Customerreports
     */
    public function setUrlstring($urlstring = null)
    {
        $this->urlstring = $urlstring;

        return $this;
    }

    /**
     * Get urlstring.
     *
     * @return string|null
     */
    public function getUrlstring()
    {
        return $this->urlstring;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Customerreports
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
     * Set filename.
     *
     * @param string|null $filename
     *
     * @return Customerreports
     */
    public function setFilename($filename = null)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename.
     *
     * @return string|null
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set numberofrecords.
     *
     * @param int|null $numberofrecords
     *
     * @return Customerreports
     */
    public function setNumberofrecords($numberofrecords = null)
    {
        $this->numberofrecords = $numberofrecords;

        return $this;
    }

    /**
     * Get numberofrecords.
     *
     * @return int|null
     */
    public function getNumberofrecords()
    {
        return $this->numberofrecords;
    }

    /**
     * Set numberofrecordsexported.
     *
     * @param int|null $numberofrecordsexported
     *
     * @return Customerreports
     */
    public function setNumberofrecordsexported($numberofrecordsexported = null)
    {
        $this->numberofrecordsexported = $numberofrecordsexported;

        return $this;
    }

    /**
     * Get numberofrecordsexported.
     *
     * @return int|null
     */
    public function getNumberofrecordsexported()
    {
        return $this->numberofrecordsexported;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Customerreports
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
     * Set reportcompletedate.
     *
     * @param \DateTime|null $reportcompletedate
     *
     * @return Customerreports
     */
    public function setReportcompletedate($reportcompletedate = null)
    {
        $this->reportcompletedate = $reportcompletedate;

        return $this;
    }

    /**
     * Get reportcompletedate.
     *
     * @return \DateTime|null
     */
    public function getReportcompletedate()
    {
        return $this->reportcompletedate;
    }

    /**
     * Set generationdate.
     *
     * @param \DateTime|null $generationdate
     *
     * @return Customerreports
     */
    public function setGenerationdate($generationdate = null)
    {
        $this->generationdate = $generationdate;

        return $this;
    }

    /**
     * Get generationdate.
     *
     * @return \DateTime|null
     */
    public function getGenerationdate()
    {
        return $this->generationdate;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Customerreports
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
