<?php

namespace Recruiter\RecruitBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * EducationStatus
 *
 * @ORM\Table(name="education_statuses")
 * @ORM\Entity(repositoryClass="Recruiter\RecruitBundle\Entity\EducationStatusRepository")
 */
class EducationStatus
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
     * @ORM\Column(name="education_status_name", type="string", length=20)
     */
    private $education_status_name;

    /**
     * @ORM\OneToMany(targetEntity="Recruit", mappedBy="education_status")
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
     * Set education_status_name
     *
     * @param string $educationStatusName
     * @return EducationStatus
     */
    public function setEducationStatusName($educationStatusName)
    {
        $this->education_status_name = $educationStatusName;
    
        return $this;
    }

    /**
     * Get education_status_name
     *
     * @return string 
     */
    public function getEducationStatusName()
    {
        return $this->education_status_name;
    }

    /**
     * Add recruits
     *
     * @param \Recruiter\RecruitBundle\Entity\Recruit $recruits
     * @return EducationStatus
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
    	return $this->getEducationStatusName();
    }
}