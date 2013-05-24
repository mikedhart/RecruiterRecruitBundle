<?php

namespace Recruiter\RecruitBundle\Services;

use Recruiter\CommonBundle\Entity\UploadType;
use Recruiter\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Recruiter\RecruitBundle\Entuty\Recruit;
use Recruiter\CommonBundle\Interfaces\ProfilePageInterface;
use Recruiter\CommonBundle\Entity\ProfilePageComponent;
use Symfony\Component\HttpFoundation\Request;

/**
 * This class encapsulates business logic for the display of a recruit's
 * profile page. It will retrieve data from the db and provide it to the 
 * view layer in a simple usable format.
 * 
 * @author Mike Hart
 * @copyright Mike Hart Web Development
 * @version 0.1
 * 
 * @package RecruitBundle
 * @subpackage Services
 */
class ProfilePage implements ProfilePageInterface
{
	/**
	 * Holds the data for this profile.
	 * 
	 * @var \ArrayObject
	 */
	private $data;
	
	/**
	 * Holds the recruit object.
	 * 
	 * @var SecurityContext
	 */
	private $securityContext;
	
	/**
	 * Holds the doctrine entity manager instance.
	 * 
	 * @var EntityManager
	 */
	private $em;
	
	/**
	 * Holds the current recruit.
	 * 
	 * @var Recruit
	 */
	private $recruit;
	
	/**
	 * Holds the current authed user
	 * 
	 * @var User
	 */
	private $user;
	
	/**
	 * Construct the object.
	 * 
	 * @param SecurityContext $securityContext
	 * @param EntityManager $em
	 * @return void
	 */
	public function __construct(SecurityContext $securityContext, EntityManager $em, Request $request)
	{
		$this->securityContext = $securityContext;
		$this->em = $em;

		if ($request->get('id')) {
			$this->recruit = $em
				->getRepository("RecruiterRecruitBundle:Recruit")
				->find($request->get('id'))
			;
		}
	}
	
	/**
	 * Load the data and hydrate the object.
	 * 
	 * @return void
	 */
	public function loadData()
	{
		$recruit = $this->getRecruit();
		// var_dump($recruit);die;
		$methods = array("skills" => "getSkills", "job_types" => "getJobTypes");
		
		foreach ($methods as $key => $method) {
			$component = new ProfilePageComponent;
			$component->setTitle(str_replace("_", " ", ucfirst($key)));
			$component->setKey($key);
			$component->setCollection($recruit->$method());
			$component->setEditRoute($key);
			
			$this->data[] = $component;

			unset($component);
		}
	}
	
	public function getPortfolio()
	{
		return $this->getRecruit()->getPortfolioEntries();
	}
	
	public function isOwner()
	{
		return ($this->recruit->getUser()->getId() === $this->getUser()->getId() || $this->getUser()->hasRole("ROLE_ADMIN"));
	}
	
	/**
	 * @return ProfilePageComponent[]
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Gets the current authed user.
	 * 
	 * @return User
	 */
	private function getUser()
	{
		if (!$this->user instanceof User) {
			$this->user = $this->securityContext->getToken()->getUser();
		}
		
		return $this->user;
	}
	
	/**
	 * Returns the recruit object for this profile.
	 * 
	 * @return Recruit
	 */
	public function getRecruit()
	{
		if (!$this->recruit) {
			$this->recruit = $this->getUser()->getRecruit();
		}
		
		return $this->recruit;
	}
}