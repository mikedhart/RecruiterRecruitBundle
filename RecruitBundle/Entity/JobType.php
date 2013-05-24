<?php

namespace Recruiter\RecruitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * JobType
 *
 * @ORM\Table(name="job_types")
 * @ORM\Entity(repositoryClass="Recruiter\RecruitBundle\Entity\JobTypeRepository")
 */
class JobType
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
     * @ORM\Column(name="job_type_name", type="string", length=100)
     */
    private $job_type_name;
    
    /**
     * @ORM\ManyToMany(targetEntity="Recruit", mappedBy="job_types")
     * @ORM\JoinTable(name="job_types_recruits")
     * 
     * @var ArrayCollection
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
     * Set job_type_name
     *
     * @param string $jobTypeName
     * @return JobType
     */
    public function setJobTypeName($jobTypeName)
    {
        $this->job_type_name = $jobTypeName;
    
        return $this;
    }

    /**
     * Get job_type_name
     *
     * @return string 
     */
    public function getJobTypeName()
    {
        return $this->job_type_name;
    }

    /**
     * Add recruits
     *
     * @param \Recruiter\RecruitBundle\Entity\Recruit $recruits
     * @return JobType
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
    
    public function __toString()
    {
    	return $this->getJobTypeName();
    }
}