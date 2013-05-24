<?php

namespace Recruiter\RecruitBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Qualification
 *
 * @ORM\Table(name="qualifications")
 * @ORM\Entity(repositoryClass="Recruiter\RecruitBundle\Entity\QualificationRepository")
 */
class Qualification
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="qualification_name", type="string", length=50)
     */
    private $qualification_name;

    /**
     * @ORM\ManyToMany(targetEntity="Recruit", inversedBy="qualifications")
     * @ORM\JoinTable(name="qualifications_recruits")
     * @var Recruit
     */
    private $recruits;
    
    public function __construct()
    {
    	$this->recruits = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set qualification_name
     *
     * @param string $qualificationName
     * @return Qualification
     */
    public function setQualificationName($qualificationName)
    {
        $this->qualification_name = $qualificationName;
    
        return $this;
    }

    /**
     * Get qualification_name
     *
     * @return string 
     */
    public function getQualificationName()
    {
        return $this->qualification_name;
    }

    /**
     * Add recruits
     *
     * @param \Recruiter\RecruitBundle\Entity\Recruit $recruits
     * @return Qualification
     */
    public function addRecruit(\Recruiter\RecruitBundle\Entity\Recruit $recruits)
    {
        $this->recruits[] = $recruits;
    
        return $this;
    }

    /**
     * Remove recruits
     *
     * @param \Recruiter\RecruitBundle\Entity\Recruit $recruits
     */
    public function removeRecruit(\Recruiter\RecruitBundle\Entity\Recruit $recruits)
    {
        $this->recruits->removeElement($recruits);
    }

    /**
     * Get recruits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecruits()
    {
        return $this->recruits;
    }
}