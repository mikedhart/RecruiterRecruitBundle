<?php

namespace Recruiter\RecruitBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\Response;
use Recruiter\RecruitBundle\Entity\Recruit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function editSkillsAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$skills = $em->getRepository("RecruiterRecruitBundle:Skill")->findAll();
		$handler = $this->get('recruiter_recruit.recruithandler');
		
		if ($this->getRequest()->getMethod() === "POST" && isset($_POST['skills'])) {
			$skills = $em 	->getRepository("RecruiterRecruitBundle:Skill")
							->findAllByIds(array_keys($_POST['skills']));
			$handler->editSkills($skills);
			
			return $this->redirect($this->generateUrl('recruiter_recruit_homepage'));
		}
		
		return $this->render('RecruiterRecruitBundle:Default:skills.html.twig', array('skills' => $skills, 'recruit' => $handler->getRecruit()));
	}
	
	public function editJobTypesAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$jobTypes = $em->getRepository("RecruiterRecruitBundle:JobType")->findAll();
		$handler = $this->get('recruiter_recruit.recruithandler');
	
		if ($this->getRequest()->getMethod() === "POST" && isset($_POST['job_types'])) {
			$jobTypes = $em ->getRepository("RecruiterRecruitBundle:JobType")
							->findAllByIds(array_keys($_POST['job_types']));
			$handler->editJobTypes($jobTypes);
				
			return $this->redirect($this->generateUrl('recruiter_recruit_homepage'));
		}
	
		return $this->render('RecruiterRecruitBundle:Default:job_types.html.twig', array('job_types' => $jobTypes, 'recruit' => $handler->getRecruit()));
	}
	
    public function indexAction($id = null)
    {    	
        $page = $this->get('recruiter_recruit.profilepage');
        $page->loadData();
        
        $handler = $this->get('recruiter_recruit.recruithandler');
        
        try {
        	$profilePicture = $handler->getProfilePicture();
        } catch (\Doctrine\ORM\NoResultException $e) {
        	$profilePicture = false;
        }
        
        try {
        	$cv = $handler->getCurrentCv();
        } catch (\Doctrine\ORM\NoResultException $e) {
        	$cv = false;
        }

        $deleteForm = $this->createDeleteForm($handler->getRecruit()->getId());
        
        return $this->render(
        		'RecruiterCommonBundle:Default:profile.html.twig', 
        		array(
        			'page' => $page,
        			'profile_picture' => $profilePicture,
        			'current_cv' => $cv,
        			'delete_form' => $deleteForm->createView()
        		)
        );
    }
    
    private function createDeleteForm($id)
    {
    	return $this->createFormBuilder(array('id' => $id))
    		->add('id', 'hidden')
    		->getForm()
    	;
    }
    
    public function editAction()
    {
    	$handler = $this->get('recruiter_recruit.recruithandler');
    	$recruit = $handler->getRecruit();
    	
    	if (!$recruit instanceof Recruit) {
    		throw $this->createNotFoundException("Recruit not found.");
    	}
    	
    	$form = $this->createFormBuilder($recruit)
    		->add('recruit_gender', 'choice', array('label' => 'Gender', 'choices' => $handler->getGenderOptions()))
    		->add('recruit_dob', 'date', array(
			    'input'  => 'timestamp',
    			'label' => 'Date of Birth',
			    'widget' => 'choice',
    			'years' => range(date("Y") - 100, date("Y"))
			))
			
			->add('education_status')
			->add('location')
			->add('recruit_personal_statement', 'text', array('label' => 'Personal Statement'))
            ->add('recruit_phone_number', null, array('label' => "Phone Number"))
    		->getForm()
    	;
    	
    	if ($this->getRequest()->getMethod() === 'POST') {
    		$form->bindRequest($this->getRequest());
    		
    		$recruit = $handler->getRecruit();
    		
    		$this->getDoctrine()->getEntityManager()->persist($recruit);
    		$recruit->getUser()->setFirstName($_POST['user']['first_name']);
    		$recruit->getUser()->setLastName($_POST['user']['last_name']);
    		$this->getDoctrine()->getEntityManager()->flush();
    		
    		$this->addFeedback();
    		
    		return $this->redirect($this->generateUrl('recruiter_recruit_homepage', array('id' => $recruit->getId())));
    	}
    	
    	$mode = ($this->getRequest()->isXmlHttpRequest()) ? "ajax" : "html";
    	
    	return $this->render(
    		'RecruiterRecruitBundle:Default:edit.' . $mode . '.twig',
    		array(
    			'form' => $form->createView(),
    			'recruit' => $handler->getRecruit()
   			)
    	);
    }
    
    public function addFeedback()
    {
    	$this->get('session')->getFlashBag()->add('success', "Your changes have been saved.");
    }
}
