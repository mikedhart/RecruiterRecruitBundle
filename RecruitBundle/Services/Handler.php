<?php

namespace Recruiter\RecruitBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;

use Recruiter\CommonBundle\Entity\UploadType;
use Recruiter\UserBundle\Services\Handler as UserHandler;
use Recruiter\UserBundle\Entity\User;
use Recruiter\RecruitBundle\Entity\Recruit;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Recruiter\CommonBundle\Entity\Upload;

class RecruitHandler extends UserHandler
{
	/**
	 * @return Recruit
	 */
	public function getRecruit()
	{
		return $this->getUser()->getRecruit();	
	}
	
	private function getDefaultProfilePicture(UploadType $type)
	{
		$upload = new Upload();
		$upload->setUploadType($type);
		$upload->setUploadFileName("user-uploadpic.png");
		
		return $upload;
	}
	
	/**
	 * @return Upload
	 */
	public function getProfilePicture()
	{
		$type = $this->em->getRepository('RecruiterCommonBundle:UploadType')
			->findOneBy(array('upload_type_name' => UploadType::TYPE_PROFILE_PICTURE));
			
		$qb = $this->em->createQueryBuilder();
		$qb ->add('select', 'u')
			->add('from', 'RecruiterCommonBundle:Upload u')
			->add('where', 'u.upload_type = :type and u.recruit = :recruit')
			->add('orderBy', 'u.id DESC')
			->setParameter('type', $type)
			->setParameter('recruit', $this->getRecruit())
			->setMaxResults(1);

		$result = $qb->getQuery()->getSingleResult();
		
		return $result;
	}
	
	/**
	 * Takes an array of skills and appends them to the recruit.
	 * @param Skill[] $skillsArray
	 */
	public function editSkills($skillsArray)
	{
		$this->em->persist($this->getRecruit());
		
		$this->getRecruit()->setSkills(new ArrayCollection());
		
		foreach($skillsArray as $skill) {
			$this->getRecruit()->addSkill($skill);
		}
		
		$this->em->flush();
	}
	
	/**
	 * @return array
	 */
	public function getGenderOptions()
	{
		return array('m' => 'Male', 'f' => 'Female');
	}
	
	/**
	 * @return boolean
	 */
	public function hasProfilePicture()
	{
		return ($this->getProfilePicture() instanceof Upload);
	}
	
	/**
	 * Creates a recruit instance and persists it to the db.
	 * 
	 * @return \Recruiter\RecruitBundle\Entity\Recruit
	 */
	public function create()
	{
		$recruit = new Recruit;
		$channel = $this->em
			->getRepository('RecruiterCommonBundle:Channel')
			->findOneBy(array("channel_name" => "creativesrus"))
		;
		
		$this->em->persist($recruit);
		
		$recruit->setChannel($channel);
		$recruit->setUser($this->getUser());
		
		$this->em->flush();
		
		return $recruit;
	}
}