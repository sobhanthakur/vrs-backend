<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Onboardings
 *
 * @ORM\Table(name="Onboardings", indexes={@ORM\Index(name="IDX_7A09CA60854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Onboardings
{
    /**
     * @var int
     *
     * @ORM\Column(name="OnboardingID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $onboardingid;

    /**
     * @var bool
     *
     * @ORM\Column(name="Q1", type="boolean", nullable=false)
     */
    private $q1 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q2", type="boolean", nullable=false)
     */
    private $q2 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q3", type="boolean", nullable=false)
     */
    private $q3 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q4", type="boolean", nullable=false)
     */
    private $q4 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q5", type="boolean", nullable=false)
     */
    private $q5 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q6", type="boolean", nullable=false)
     */
    private $q6 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q7", type="boolean", nullable=false)
     */
    private $q7 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q8", type="boolean", nullable=false)
     */
    private $q8 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q9", type="boolean", nullable=false)
     */
    private $q9 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q10", type="boolean", nullable=false)
     */
    private $q10 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q11", type="boolean", nullable=false)
     */
    private $q11 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q12", type="boolean", nullable=false)
     */
    private $q12 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q13", type="boolean", nullable=false)
     */
    private $q13 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q14", type="boolean", nullable=false)
     */
    private $q14 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q15", type="boolean", nullable=false)
     */
    private $q15 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q16", type="boolean", nullable=false)
     */
    private $q16 = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q1Notes", type="text", length=-1, nullable=true)
     */
    private $q1notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q2Notes", type="text", length=-1, nullable=true)
     */
    private $q2notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q3Notes", type="text", length=-1, nullable=true)
     */
    private $q3notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q4Notes", type="text", length=-1, nullable=true)
     */
    private $q4notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q5Notes", type="text", length=-1, nullable=true)
     */
    private $q5notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q6Notes", type="text", length=-1, nullable=true)
     */
    private $q6notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q7Notes", type="text", length=-1, nullable=true)
     */
    private $q7notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q8Notes", type="text", length=-1, nullable=true)
     */
    private $q8notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q9Notes", type="text", length=-1, nullable=true)
     */
    private $q9notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q10Notes", type="text", length=-1, nullable=true)
     */
    private $q10notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q11Notes", type="text", length=-1, nullable=true)
     */
    private $q11notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q12Notes", type="text", length=-1, nullable=true)
     */
    private $q12notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q13Notes", type="text", length=-1, nullable=true)
     */
    private $q13notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q14Notes", type="text", length=-1, nullable=true)
     */
    private $q14notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q15Notes", type="text", length=-1, nullable=true)
     */
    private $q15notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Q16Notes", type="text", length=-1, nullable=true)
     */
    private $q16notes;

    /**
     * @var bool
     *
     * @ORM\Column(name="Q1Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q1active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q2Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q2active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q3Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q3active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q4Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q4active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q5Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q5active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q6Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q6active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q7Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q7active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q8Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q8active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q9Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q9active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q10Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q10active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q11Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q11active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q12Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q12active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q13Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q13active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q14Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q14active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q15Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q15active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Q16Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $q16active = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowCompleted", type="boolean", nullable=false)
     */
    private $showcompleted = '0';

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
     * Get onboardingid.
     *
     * @return int
     */
    public function getOnboardingid()
    {
        return $this->onboardingid;
    }

    /**
     * Set q1.
     *
     * @param bool $q1
     *
     * @return Onboardings
     */
    public function setQ1($q1)
    {
        $this->q1 = $q1;

        return $this;
    }

    /**
     * Get q1.
     *
     * @return bool
     */
    public function getQ1()
    {
        return $this->q1;
    }

    /**
     * Set q2.
     *
     * @param bool $q2
     *
     * @return Onboardings
     */
    public function setQ2($q2)
    {
        $this->q2 = $q2;

        return $this;
    }

    /**
     * Get q2.
     *
     * @return bool
     */
    public function getQ2()
    {
        return $this->q2;
    }

    /**
     * Set q3.
     *
     * @param bool $q3
     *
     * @return Onboardings
     */
    public function setQ3($q3)
    {
        $this->q3 = $q3;

        return $this;
    }

    /**
     * Get q3.
     *
     * @return bool
     */
    public function getQ3()
    {
        return $this->q3;
    }

    /**
     * Set q4.
     *
     * @param bool $q4
     *
     * @return Onboardings
     */
    public function setQ4($q4)
    {
        $this->q4 = $q4;

        return $this;
    }

    /**
     * Get q4.
     *
     * @return bool
     */
    public function getQ4()
    {
        return $this->q4;
    }

    /**
     * Set q5.
     *
     * @param bool $q5
     *
     * @return Onboardings
     */
    public function setQ5($q5)
    {
        $this->q5 = $q5;

        return $this;
    }

    /**
     * Get q5.
     *
     * @return bool
     */
    public function getQ5()
    {
        return $this->q5;
    }

    /**
     * Set q6.
     *
     * @param bool $q6
     *
     * @return Onboardings
     */
    public function setQ6($q6)
    {
        $this->q6 = $q6;

        return $this;
    }

    /**
     * Get q6.
     *
     * @return bool
     */
    public function getQ6()
    {
        return $this->q6;
    }

    /**
     * Set q7.
     *
     * @param bool $q7
     *
     * @return Onboardings
     */
    public function setQ7($q7)
    {
        $this->q7 = $q7;

        return $this;
    }

    /**
     * Get q7.
     *
     * @return bool
     */
    public function getQ7()
    {
        return $this->q7;
    }

    /**
     * Set q8.
     *
     * @param bool $q8
     *
     * @return Onboardings
     */
    public function setQ8($q8)
    {
        $this->q8 = $q8;

        return $this;
    }

    /**
     * Get q8.
     *
     * @return bool
     */
    public function getQ8()
    {
        return $this->q8;
    }

    /**
     * Set q9.
     *
     * @param bool $q9
     *
     * @return Onboardings
     */
    public function setQ9($q9)
    {
        $this->q9 = $q9;

        return $this;
    }

    /**
     * Get q9.
     *
     * @return bool
     */
    public function getQ9()
    {
        return $this->q9;
    }

    /**
     * Set q10.
     *
     * @param bool $q10
     *
     * @return Onboardings
     */
    public function setQ10($q10)
    {
        $this->q10 = $q10;

        return $this;
    }

    /**
     * Get q10.
     *
     * @return bool
     */
    public function getQ10()
    {
        return $this->q10;
    }

    /**
     * Set q11.
     *
     * @param bool $q11
     *
     * @return Onboardings
     */
    public function setQ11($q11)
    {
        $this->q11 = $q11;

        return $this;
    }

    /**
     * Get q11.
     *
     * @return bool
     */
    public function getQ11()
    {
        return $this->q11;
    }

    /**
     * Set q12.
     *
     * @param bool $q12
     *
     * @return Onboardings
     */
    public function setQ12($q12)
    {
        $this->q12 = $q12;

        return $this;
    }

    /**
     * Get q12.
     *
     * @return bool
     */
    public function getQ12()
    {
        return $this->q12;
    }

    /**
     * Set q13.
     *
     * @param bool $q13
     *
     * @return Onboardings
     */
    public function setQ13($q13)
    {
        $this->q13 = $q13;

        return $this;
    }

    /**
     * Get q13.
     *
     * @return bool
     */
    public function getQ13()
    {
        return $this->q13;
    }

    /**
     * Set q14.
     *
     * @param bool $q14
     *
     * @return Onboardings
     */
    public function setQ14($q14)
    {
        $this->q14 = $q14;

        return $this;
    }

    /**
     * Get q14.
     *
     * @return bool
     */
    public function getQ14()
    {
        return $this->q14;
    }

    /**
     * Set q15.
     *
     * @param bool $q15
     *
     * @return Onboardings
     */
    public function setQ15($q15)
    {
        $this->q15 = $q15;

        return $this;
    }

    /**
     * Get q15.
     *
     * @return bool
     */
    public function getQ15()
    {
        return $this->q15;
    }

    /**
     * Set q16.
     *
     * @param bool $q16
     *
     * @return Onboardings
     */
    public function setQ16($q16)
    {
        $this->q16 = $q16;

        return $this;
    }

    /**
     * Get q16.
     *
     * @return bool
     */
    public function getQ16()
    {
        return $this->q16;
    }

    /**
     * Set q1notes.
     *
     * @param string|null $q1notes
     *
     * @return Onboardings
     */
    public function setQ1notes($q1notes = null)
    {
        $this->q1notes = $q1notes;

        return $this;
    }

    /**
     * Get q1notes.
     *
     * @return string|null
     */
    public function getQ1notes()
    {
        return $this->q1notes;
    }

    /**
     * Set q2notes.
     *
     * @param string|null $q2notes
     *
     * @return Onboardings
     */
    public function setQ2notes($q2notes = null)
    {
        $this->q2notes = $q2notes;

        return $this;
    }

    /**
     * Get q2notes.
     *
     * @return string|null
     */
    public function getQ2notes()
    {
        return $this->q2notes;
    }

    /**
     * Set q3notes.
     *
     * @param string|null $q3notes
     *
     * @return Onboardings
     */
    public function setQ3notes($q3notes = null)
    {
        $this->q3notes = $q3notes;

        return $this;
    }

    /**
     * Get q3notes.
     *
     * @return string|null
     */
    public function getQ3notes()
    {
        return $this->q3notes;
    }

    /**
     * Set q4notes.
     *
     * @param string|null $q4notes
     *
     * @return Onboardings
     */
    public function setQ4notes($q4notes = null)
    {
        $this->q4notes = $q4notes;

        return $this;
    }

    /**
     * Get q4notes.
     *
     * @return string|null
     */
    public function getQ4notes()
    {
        return $this->q4notes;
    }

    /**
     * Set q5notes.
     *
     * @param string|null $q5notes
     *
     * @return Onboardings
     */
    public function setQ5notes($q5notes = null)
    {
        $this->q5notes = $q5notes;

        return $this;
    }

    /**
     * Get q5notes.
     *
     * @return string|null
     */
    public function getQ5notes()
    {
        return $this->q5notes;
    }

    /**
     * Set q6notes.
     *
     * @param string|null $q6notes
     *
     * @return Onboardings
     */
    public function setQ6notes($q6notes = null)
    {
        $this->q6notes = $q6notes;

        return $this;
    }

    /**
     * Get q6notes.
     *
     * @return string|null
     */
    public function getQ6notes()
    {
        return $this->q6notes;
    }

    /**
     * Set q7notes.
     *
     * @param string|null $q7notes
     *
     * @return Onboardings
     */
    public function setQ7notes($q7notes = null)
    {
        $this->q7notes = $q7notes;

        return $this;
    }

    /**
     * Get q7notes.
     *
     * @return string|null
     */
    public function getQ7notes()
    {
        return $this->q7notes;
    }

    /**
     * Set q8notes.
     *
     * @param string|null $q8notes
     *
     * @return Onboardings
     */
    public function setQ8notes($q8notes = null)
    {
        $this->q8notes = $q8notes;

        return $this;
    }

    /**
     * Get q8notes.
     *
     * @return string|null
     */
    public function getQ8notes()
    {
        return $this->q8notes;
    }

    /**
     * Set q9notes.
     *
     * @param string|null $q9notes
     *
     * @return Onboardings
     */
    public function setQ9notes($q9notes = null)
    {
        $this->q9notes = $q9notes;

        return $this;
    }

    /**
     * Get q9notes.
     *
     * @return string|null
     */
    public function getQ9notes()
    {
        return $this->q9notes;
    }

    /**
     * Set q10notes.
     *
     * @param string|null $q10notes
     *
     * @return Onboardings
     */
    public function setQ10notes($q10notes = null)
    {
        $this->q10notes = $q10notes;

        return $this;
    }

    /**
     * Get q10notes.
     *
     * @return string|null
     */
    public function getQ10notes()
    {
        return $this->q10notes;
    }

    /**
     * Set q11notes.
     *
     * @param string|null $q11notes
     *
     * @return Onboardings
     */
    public function setQ11notes($q11notes = null)
    {
        $this->q11notes = $q11notes;

        return $this;
    }

    /**
     * Get q11notes.
     *
     * @return string|null
     */
    public function getQ11notes()
    {
        return $this->q11notes;
    }

    /**
     * Set q12notes.
     *
     * @param string|null $q12notes
     *
     * @return Onboardings
     */
    public function setQ12notes($q12notes = null)
    {
        $this->q12notes = $q12notes;

        return $this;
    }

    /**
     * Get q12notes.
     *
     * @return string|null
     */
    public function getQ12notes()
    {
        return $this->q12notes;
    }

    /**
     * Set q13notes.
     *
     * @param string|null $q13notes
     *
     * @return Onboardings
     */
    public function setQ13notes($q13notes = null)
    {
        $this->q13notes = $q13notes;

        return $this;
    }

    /**
     * Get q13notes.
     *
     * @return string|null
     */
    public function getQ13notes()
    {
        return $this->q13notes;
    }

    /**
     * Set q14notes.
     *
     * @param string|null $q14notes
     *
     * @return Onboardings
     */
    public function setQ14notes($q14notes = null)
    {
        $this->q14notes = $q14notes;

        return $this;
    }

    /**
     * Get q14notes.
     *
     * @return string|null
     */
    public function getQ14notes()
    {
        return $this->q14notes;
    }

    /**
     * Set q15notes.
     *
     * @param string|null $q15notes
     *
     * @return Onboardings
     */
    public function setQ15notes($q15notes = null)
    {
        $this->q15notes = $q15notes;

        return $this;
    }

    /**
     * Get q15notes.
     *
     * @return string|null
     */
    public function getQ15notes()
    {
        return $this->q15notes;
    }

    /**
     * Set q16notes.
     *
     * @param string|null $q16notes
     *
     * @return Onboardings
     */
    public function setQ16notes($q16notes = null)
    {
        $this->q16notes = $q16notes;

        return $this;
    }

    /**
     * Get q16notes.
     *
     * @return string|null
     */
    public function getQ16notes()
    {
        return $this->q16notes;
    }

    /**
     * Set q1active.
     *
     * @param bool $q1active
     *
     * @return Onboardings
     */
    public function setQ1active($q1active)
    {
        $this->q1active = $q1active;

        return $this;
    }

    /**
     * Get q1active.
     *
     * @return bool
     */
    public function getQ1active()
    {
        return $this->q1active;
    }

    /**
     * Set q2active.
     *
     * @param bool $q2active
     *
     * @return Onboardings
     */
    public function setQ2active($q2active)
    {
        $this->q2active = $q2active;

        return $this;
    }

    /**
     * Get q2active.
     *
     * @return bool
     */
    public function getQ2active()
    {
        return $this->q2active;
    }

    /**
     * Set q3active.
     *
     * @param bool $q3active
     *
     * @return Onboardings
     */
    public function setQ3active($q3active)
    {
        $this->q3active = $q3active;

        return $this;
    }

    /**
     * Get q3active.
     *
     * @return bool
     */
    public function getQ3active()
    {
        return $this->q3active;
    }

    /**
     * Set q4active.
     *
     * @param bool $q4active
     *
     * @return Onboardings
     */
    public function setQ4active($q4active)
    {
        $this->q4active = $q4active;

        return $this;
    }

    /**
     * Get q4active.
     *
     * @return bool
     */
    public function getQ4active()
    {
        return $this->q4active;
    }

    /**
     * Set q5active.
     *
     * @param bool $q5active
     *
     * @return Onboardings
     */
    public function setQ5active($q5active)
    {
        $this->q5active = $q5active;

        return $this;
    }

    /**
     * Get q5active.
     *
     * @return bool
     */
    public function getQ5active()
    {
        return $this->q5active;
    }

    /**
     * Set q6active.
     *
     * @param bool $q6active
     *
     * @return Onboardings
     */
    public function setQ6active($q6active)
    {
        $this->q6active = $q6active;

        return $this;
    }

    /**
     * Get q6active.
     *
     * @return bool
     */
    public function getQ6active()
    {
        return $this->q6active;
    }

    /**
     * Set q7active.
     *
     * @param bool $q7active
     *
     * @return Onboardings
     */
    public function setQ7active($q7active)
    {
        $this->q7active = $q7active;

        return $this;
    }

    /**
     * Get q7active.
     *
     * @return bool
     */
    public function getQ7active()
    {
        return $this->q7active;
    }

    /**
     * Set q8active.
     *
     * @param bool $q8active
     *
     * @return Onboardings
     */
    public function setQ8active($q8active)
    {
        $this->q8active = $q8active;

        return $this;
    }

    /**
     * Get q8active.
     *
     * @return bool
     */
    public function getQ8active()
    {
        return $this->q8active;
    }

    /**
     * Set q9active.
     *
     * @param bool $q9active
     *
     * @return Onboardings
     */
    public function setQ9active($q9active)
    {
        $this->q9active = $q9active;

        return $this;
    }

    /**
     * Get q9active.
     *
     * @return bool
     */
    public function getQ9active()
    {
        return $this->q9active;
    }

    /**
     * Set q10active.
     *
     * @param bool $q10active
     *
     * @return Onboardings
     */
    public function setQ10active($q10active)
    {
        $this->q10active = $q10active;

        return $this;
    }

    /**
     * Get q10active.
     *
     * @return bool
     */
    public function getQ10active()
    {
        return $this->q10active;
    }

    /**
     * Set q11active.
     *
     * @param bool $q11active
     *
     * @return Onboardings
     */
    public function setQ11active($q11active)
    {
        $this->q11active = $q11active;

        return $this;
    }

    /**
     * Get q11active.
     *
     * @return bool
     */
    public function getQ11active()
    {
        return $this->q11active;
    }

    /**
     * Set q12active.
     *
     * @param bool $q12active
     *
     * @return Onboardings
     */
    public function setQ12active($q12active)
    {
        $this->q12active = $q12active;

        return $this;
    }

    /**
     * Get q12active.
     *
     * @return bool
     */
    public function getQ12active()
    {
        return $this->q12active;
    }

    /**
     * Set q13active.
     *
     * @param bool $q13active
     *
     * @return Onboardings
     */
    public function setQ13active($q13active)
    {
        $this->q13active = $q13active;

        return $this;
    }

    /**
     * Get q13active.
     *
     * @return bool
     */
    public function getQ13active()
    {
        return $this->q13active;
    }

    /**
     * Set q14active.
     *
     * @param bool $q14active
     *
     * @return Onboardings
     */
    public function setQ14active($q14active)
    {
        $this->q14active = $q14active;

        return $this;
    }

    /**
     * Get q14active.
     *
     * @return bool
     */
    public function getQ14active()
    {
        return $this->q14active;
    }

    /**
     * Set q15active.
     *
     * @param bool $q15active
     *
     * @return Onboardings
     */
    public function setQ15active($q15active)
    {
        $this->q15active = $q15active;

        return $this;
    }

    /**
     * Get q15active.
     *
     * @return bool
     */
    public function getQ15active()
    {
        return $this->q15active;
    }

    /**
     * Set q16active.
     *
     * @param bool $q16active
     *
     * @return Onboardings
     */
    public function setQ16active($q16active)
    {
        $this->q16active = $q16active;

        return $this;
    }

    /**
     * Get q16active.
     *
     * @return bool
     */
    public function getQ16active()
    {
        return $this->q16active;
    }

    /**
     * Set showcompleted.
     *
     * @param bool $showcompleted
     *
     * @return Onboardings
     */
    public function setShowcompleted($showcompleted)
    {
        $this->showcompleted = $showcompleted;

        return $this;
    }

    /**
     * Get showcompleted.
     *
     * @return bool
     */
    public function getShowcompleted()
    {
        return $this->showcompleted;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Onboardings
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
