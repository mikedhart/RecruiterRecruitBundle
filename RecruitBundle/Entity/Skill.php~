<?php

namespace Recruiter\RecruitBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Skill
 *
 * @ORM\Table(name="skills")
 * @ORM\Entity(repositoryClass="Recruiter\RecruitBundle\Entity\SkillRepository")
 */
class Skill
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
     * @ORM\Column(name="skill_name", type="string", length=50)
     */
    private $skill_name;

    /**
     * @var string
     *
     * @ORM\Column(name="skill_description", type="string", length=255)
     */
    private $skill_description;
    
    /**
     * @ORM\ManyToMany(targetEntity="Recruit", mappedBy="skills")
     * @ORM\JoinTable(name="recruits_skills")
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
     * Set skill_name
     *
     * @param string $skillName
     * @return Skill
     */
    public function setSkillName($skillName)
    {
        $this->skill_name = $skillName;
    
        return $this;
    }

    /**
     * Get skill_name
     *
     * @return string 
     */
    public function getSkillName()
    {
        return $this->skill_name;
    }

    /**
     * Set skill_description
     *
     * @param string $skillDescription
     * @return Skill
     */
    public function setSkillDescription($skillDescription)
    {
        $this->skill_description = $skillDescription;
    
        return $this;
    }

    /**
     * Get skill_description
     *
     * @return string 
     */
    public function getSkillDescription()
    {
        return $this->skill_description;
    }

    /**
     * Add recruits
     *
     * @param \Recruiter\RecruitBundle\Entity\Recruit $recruits
     * @return Skill
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
    
    public function getName()
    {
    	return $this->getSkillName();
    }
    
    public function __toString()
    {
    	return $this->getSkillName();
    }
}