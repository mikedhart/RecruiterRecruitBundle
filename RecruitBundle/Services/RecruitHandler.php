<?php

namespace Recruiter\RecruitBundle\Services;

use Symfony\Component\Routing\Exception\InvalidParameterException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
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
	 * Holds the recruit for all operations in this service.
	 * 
	 * @var Recruit
	 */
	private $recruit;
	
	/**
	 * Populate the object and set the recruit.
	 * 
	 * @param SecurityContext $securityContext
	 * @param EntityManager $em
	 * @param Request $request
	 * @return void
	 */
	public function __construct(SecurityContext $securityContext, EntityManager $em, Request $request)
	{
		parent::__construct($securityContext, $em);
		
		if ($request->get('id')) {
			
		}
		
		$rid = (is_numeric($request->get('id'))) ? $request->get('id') : $this->getUser()->getRecruit()->getId();
		
		$this->loadRecruit($rid);
	}
	
	public function fetchChartData()
	{
		$days = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
		$figrues = array();
		
		foreach ($days as $key => $value) {
			$currentDay = $this->em->createQueryBuilder()
				->add('select', 'r')
				->add('from', 'RecruiterRecruitBundle:Recruit r')
				->where('r.recruit_created_at = "?1"')
				->andWhere('r.created_at')
			;
			
			
		}
	}
	
	/**
	 * Load the recruit.
	 * 
	 * @param integer $rid
	 * @throws NotFoundHttpException
	 */
	private function loadRecruit($rid)
	{
		if (!$rid) {
			throw new InvalidParameterException("rid is a required parameter");	
		}
		
		$recruit = $this->em->getRepository('RecruiterRecruitBundle:Recruit')->find($rid);
		
		if (!$recruit instanceof Recruit) {
			throw new NotFoundHttpException("Could not find this recruit.");
		}
		
		$this->recruit = $recruit;
	}
	
	public function getCurrentCv()
	{
		return $this->em
			->getRepository('RecruiterRecruitBundle:Recruit')
				->getCvs($this->getRecruit()->getId(), 1);	
	}
	
	/**
	 * @return Recruit
	 */
	public function getRecruit()
	{
		return $this->recruit;
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
	 * Takes an array of job types and appends them to the recruit.
	 * @param JobType[] $jobTypesArray
	 */
	public function editJobTypes($jobTypesArray)
	{
		$this->em->persist($this->getRecruit());
	
		$this->getRecruit()->setJobTypes(new ArrayCollection());
	
		foreach($jobTypesArray as $type) {
			$this->getRecruit()->addJobType($type);
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
	public static function create(EntityManager $em, User $user)
	{
		$recruit = new Recruit;
		$channel = $em
			->getRepository('RecruiterCommonBundle:Channel')
			->findOneBy(array("channel_name" => "creativesrus"))
		;
		
		$em->persist($recruit);
		
		$recruit->setChannel($channel);
		$recruit->setUser($user);
		
		$em->flush();
		
		return $recruit;
	}
}