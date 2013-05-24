<?php

namespace Recruiter\RecruitBundle\Entity;

use JMS\SecurityExtraBundle\Security\Util\String;

use Doctrine\Common\Collections\ArrayCollection;
use Recruiter\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Recruiter\CommonBundle\Entity\Channel;
use Recruiter\CommonBundle\Entity\Location;
use Recruiter\CommonBundle\Entity\Upload;
use Recruiter\RecruitBundle\Entity\EducationStatus;

/**
 * Recruit
 *
 * @ORM\Table(name="recruits")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Recruiter\RecruitBundle\Entity\RecruitRepository")
 */
class Recruit
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
     * @var integer
     *
     * @ORM\Column(name="recruit_created_at", type="integer")
     */
    private $recruit_created_at = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="recruit_updated_at", type="integer")
     */
    private $recruit_updated_at = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="recruit_gender", type="string", length=1)
     */
    private $recruit_gender = "";
    
    /**
     * @var string
     *
     * @ORM\Column(name="recruit_phone_number", type="string", length=15)
     */
    private $recruit_phone_number = "";

    /**
     * @var integer
     *
     * @ORM\Column(name="recruit_dob", type="integer")
     */
    private $recruit_dob = 0;
    
    /**
     * @var string
     *
     * @ORM\Column(name="recruit_job_title", type="string")
     */
    private $recruit_job_title = "";
    
    /**
     * @var string
     * 
     * @ORM\Column(name="recruit_personal_statement", type="text")
     */
    private $recruit_personal_statement = "";

    /**
     * @ORM\ManyToOne(targetEntity="EducationStatus", inversedBy="recruits")
     * @var EducationStatus
     */
    private $education_status;

    /**
     * @ORM\OneToMany(targetEntity="PortfolioEntry", mappedBy="recruit", cascade={"persist"}, cascade={"remove"})
     * @var PortfolioItem
     */
    private $portfolio_entries;
    
    /**
     * @ORM\OneToOne(targetEntity="Recruiter\UserBundle\Entity\User", inversedBy="recruit", cascade={"remove"})
     * @var User
     */
    private $user;
    
    /**
     * @ORM\ManyToMany(targetEntity="Qualification", mappedBy="recruits", cascade={"remove"})
     * @ORM\JoinTable(name="qualifications_recruits")
     * @var Qualification
     */
    private $qualifications;
    
    /**
     * @ORM\ManyToMany(targetEntity="Skill", inversedBy="recruits", cascade={"remove"})
     * @ORM\JoinTable(name="recruits_skills")
     * @var Skill
     */
    private $skills;
    
    /**
     * @ORM\ManyToOne(targetEntity="Recruiter\CommonBundle\Entity\Location", inversedBy="recruits")
     * @var Location
     */
    private $location;
    
    /**
     * @ORM\OneToMany(targetEntity="Recruiter\CommonBundle\Entity\Upload", mappedBy="recruit", cascade={"remove"})
     * @var Upload
     */
    private $uploads;
    
    /**
     * @ORM\ManyToMany(targetEntity="JobType", inversedBy="recruits", cascade={"remove"})
     * @ORM\JoinTable(name="job_types_recruits")
     *
     * @var ArrayCollection
     */
    private $job_types;
    
    public function __construct()
    {
    	$this->qualifications = new ArrayCollection();
    	$this->skills = new ArrayCollection();
    	$this->uploads = new ArrayCollection();
    	$this->job_types = new ArrayCollection();
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
    	if ($this->getRecruitCreatedAt() == 0) {
    		$this->setRecruitCreatedAt(time());
    	}
    	
    	$this->setRecruitUpdatedAt(time());
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
     * Set recruit_created_at
     *
     * @param integer $recruitCreatedAt
     * @return Recruit
     */
    public function setRecruitCreatedAt($recruitCreatedAt)
    {
        $this->recruit_created_at = $recruitCreatedAt;
    
        return $this;
    }

    /**
     * Get recruit_created_at
     *
     * @return integer 
     */
    public function getRecruitCreatedAt()
    {
        return $this->recruit_created_at;
    }

    /**
     * Set recruit_updated_at
     *
     * @param integer $recruitUpdatedAt
     * @return Recruit
     */
    public function setRecruitUpdatedAt($recruitUpdatedAt)
    {
        $this->recruit_updated_at = $recruitUpdatedAt;
    
        return $this;
    }

    /**
     * Get recruit_updated_at
     *
     * @return integer 
     */
    public function getRecruitUpdatedAt()
    {
        return $this->recruit_updated_at;
    }

    /**
     * Set recruit_gender
     *
     * @param string $recruitGender
     * @return Recruit
     */
    public function setRecruitGender($recruitGender)
    {
        $this->recruit_gender = $recruitGender;
    
        return $this;
    }

    /**
     * Get recruit_gender
     *
     * @return string 
     */
    public function getRecruitGender()
    {
        return $this->recruit_gender;
    }

    /**
     * Set recruit_dob
     *
     * @param integer $recruitDob
     * @return Recruit
     */
    public function setRecruitDob($recruitDob)
    {
        $this->recruit_dob = $recruitDob;
    
        return $this;
    }

    /**
     * Get recruit_dob
     *
     * @return integer 
     */
    public function getRecruitDob()
    {
        return $this->recruit_dob;
    }

    /**
     * Set channel
     *
     * @param \Recruiter\CommonBundle\Entity\Channel $channel
     * @return Recruit
     */
    public function setChannel(\Recruiter\CommonBundle\Entity\Channel $channel = null)
    {
        $this->channel = $channel;
    
        return $this;
    }

    /**
     * Get channel
     *
     * @return \Recruiter\CommonBundle\Entity\Channel 
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set education_status
     *
     * @param \Recruiter\RecruitBundle\Entity\EducationStatus $educationStatus
     * @return Recruit
     */
    public function setEducationStatus(\Recruiter\RecruitBundle\Entity\EducationStatus $educationStatus = null)
    {
        $this->education_status = $educationStatus;
    
        return $this;
    }

    /**
     * Get education_status
     *
     * @return \Recruiter\RecruitBundle\Entity\EducationStatus 
     */
    public function getEducationStatus()
    {
        return $this->education_status;
    }

    /**
     * Set user
     *
     * @param \Recruiter\UserBundle\Entity\User $user
     * @return Recruit
     */
    public function setUser(\Recruiter\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Recruiter\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add qualifications
     *
     * @param \Recruiter\RecruitBundle\Entity\Qualification $qualifications
     * @return Recruit
     */
    public function addQualification(\Recruiter\RecruitBundle\Entity\Qualification $qualifications)
    {
        $this->qualifications[] = $qualifications;
    
        return $this;
    }

    /**
     * Remove qualifications
     *
     * @param \Recruiter\RecruitBundle\Entity\Qualification $qualifications
     */
    public function removeQualification(\Recruiter\RecruitBundle\Entity\Qualification $qualifications)
    {
        $this->qualifications->removeElement($qualifications);
    }

    /**
     * Get qualifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQualifications()
    {
        return $this->qualifications;
    }

    /**
     * Add skills
     *
     * @param \Recruiter\RecruitBundle\Entity\Skill $skills
     * @return Recruit
     */
    public function addSkill(\Recruiter\RecruitBundle\Entity\Skill $skills)
    {
        $this->skills[] = $skills;
    
        return $this;
    }

    /**
     * Remove skills
     *
     * @param \Recruiter\RecruitBundle\Entity\Skill $skills
     */
    public function removeSkill(\Recruiter\RecruitBundle\Entity\Skill $skills)
    {
        $this->skills->removeElement($skills);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Add uploads
     *
     * @param \Recruiter\CommonBundle\Entity\Upload $uploads
     * @return Recruit
     */
    public function addUpload(\Recruiter\CommonBundle\Entity\Upload $uploads)
    {
        $this->uploads[] = $uploads;
    
        return $this;
    }

    /**
     * Remove uploads
     *
     * @param \Recruiter\CommonBundle\Entity\Upload $uploads
     */
    public function removeUpload(\Recruiter\CommonBundle\Entity\Upload $uploads)
    {
        $this->uploads->removeElement($uploads);
    }

    /**
     * Get uploads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUploads()
    {
        return $this->uploads;
    }
    
    
    public function hasSkill($skill)
    {
    	return ($this->getSkills()->contains($skill));
    }
    
    public function hasJobType($jobType)
    {
    	return ($this->getJobTypes()->contains($jobType));
    }
    
    /**
     * Set the job types en mass.
     *
     * @param ArrayCollection $types
     */
    public function setJobTypes(ArrayCollection $types)
    {
    	$this->job_types = $types;
    }
    
    /**
     * Set the skills en mass.
     * 
     * @param ArrayCollection $skills
     */
    public function setSkills(ArrayCollection $skills)
    {
    	$this->skills = $skills;
    }

    /**
     * Set location
     *
     * @param \Recruiter\CommonBundle\Entity\Location $location
     * @return Recruit
     */
    public function setLocation(\Recruiter\CommonBundle\Entity\Location $location = null)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return \Recruiter\CommonBundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set recruit_personal_statement
     *
     * @param string $recruitPersonalStatement
     * @return Recruit
     */
    public function setRecruitPersonalStatement($recruitPersonalStatement)
    {
        $this->recruit_personal_statement = $recruitPersonalStatement;
    
        return $this;
    }

    /**
     * Get recruit_personal_statement
     *
     * @return string 
     */
    public function getRecruitPersonalStatement()
    {
        return $this->recruit_personal_statement;
    }

    /**
     * Add portfolio_entries
     *
     * @param \Recruiter\RecruitBundle\Entity\PortfolioEntry $portfolioEntries
     * @return Recruit
     */
    public function addPortfolioEntrie(\Recruiter\RecruitBundle\Entity\PortfolioEntry $portfolioEntries)
    {
        $this->portfolio_entries[] = $portfolioEntries;
    
        return $this;
    }
    
    public function addPortfolioEntry(\Recruiter\RecruitBundle\Entity\PortfolioEntry $portfolioEntries)
    {
    	return $this->addPortfolioEntrie($portfolioEntries);
    }

    /**
     * Remove portfolio_entries
     *
     * @param \Recruiter\RecruitBundle\Entity\PortfolioEntry $portfolioEntries
     */
    public function removePortfolioEntrie(\Recruiter\RecruitBundle\Entity\PortfolioEntry $portfolioEntries)
    {
        $this->portfolio_entries->removeElement($portfolioEntries);
    }

    /**
     * Get portfolio_entries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPortfolioEntries()
    {
        return $this->portfolio_entries;
    }

    /**
     * Set recruit_job_title
     *
     * @param string $recruitJobTitle
     * @return Recruit
     */
    public function setRecruitJobTitle($recruitJobTitle)
    {
        $this->recruit_job_title = $recruitJobTitle;
    
        return $this;
    }

    /**
     * Get recruit_job_title
     *
     * @return string 
     */
    public function getRecruitJobTitle()
    {
        return $this->recruit_job_title;
    }

    /**
     * Add job_types
     *
     * @param \Recruiter\RecruitBundle\Entity\JobType $jobTypes
     * @return Recruit
     */
    public function addJobType(\Recruiter\RecruitBundle\Entity\JobType $jobTypes)
    {
        $this->job_types[] = $jobTypes;
    
        return $this;
    }

    /**
     * Remove job_types
     *
     * @param \Recruiter\RecruitBundle\Entity\JobType $jobTypes
     */
    public function removeJobType(\Recruiter\RecruitBundle\Entity\JobType $jobTypes)
    {
        $this->job_types->removeElement($jobTypes);
    }

    /**
     * Get job_types
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJobTypes()
    {
        return $this->job_types;
    }

    /**
     * Set recruit_phone_number
     *
     * @param string $recruitPhoneNumber
     * @return Recruit
     */
    public function setRecruitPhoneNumber($recruitPhoneNumber)
    {
        $this->recruit_phone_number = $recruitPhoneNumber;
    
        return $this;
    }

    /**
     * Get recruit_phone_number
     *
     * @return string 
     */
    public function getRecruitPhoneNumber()
    {
        return $this->recruit_phone_number;
    }
    
    public function __toString()
    {
        return $this->getUser()->getFirstName() 
                    . " " . $this->getUser()->getLastName();
    }
}