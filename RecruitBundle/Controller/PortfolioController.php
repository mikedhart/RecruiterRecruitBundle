<?php

namespace Recruiter\RecruitBundle\Controller;

use Recruiter\RecruitBundle\Entity\PortfolioEntry;

use Symfony\Component\HttpFoundation\Response;
use Recruiter\RecruitBundle\Entity\Recruit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PortfolioController extends Controller
{
	private function makeForm(PortfolioEntry $entry)
	{
		$form = $this->createFormBuilder($entry)
			->add('portfolio_item_name', 'text', array('label' => 'Project name'))
			->add('portfolio_item_href', 'text', array('label' => 'Link', 'required' => false))
			->add('file', null, array('required' => false))
			->add('portfolio_item_brief', 'textarea', array('label' => 'Brief'))
			->getForm()
		;
		
		return $form;
	}
	
	public function newAction()
	{
		$handler = $this->get('recruiter_recruit.portfoliohandler');
		$newEntry = $handler->createEntryInstance();
				
		$form = $this->makeForm($newEntry);
		
		if ($this->getRequest()->getMethod() === 'POST') {
			$form->bindRequest($this->getRequest());
			$this->getDoctrine()->getEntityManager()->persist($newEntry);
			
			$this->getDoctrine()->getEntityManager()->flush();
		
			return $this->redirect($this->generateUrl('recruiter_recruit_portfolio_homepage', array("id" => $handler->getRecruit()->getId())));
		}
			
		return $this->render(
			'RecruiterRecruitBundle:Portfolio:new.html.twig',
			array(
				'form' => $form->createView()
			)
		);
	}
	
	public function indexAction($id)
	{
		$handler = $this->get('recruiter_recruit.portfoliohandler');
		
		return $this->render(
			'RecruiterRecruitBundle:Portfolio:index.html.twig',
			array(
				'portfolio' => $handler->getEntries(),
				'recruit' => $handler->getRecruit()
			)
		);
	}
	
	public function showAction($id)
	{
		$entry = $this->getDoctrine()->getEntityManager()->getRepository('RecruiterRecruitBundle:PortfolioEntry')->find($id);
		
		if (!$entry instanceof PortfolioEntry) {
			throw $this->createNotFoundException("Portfolio item not found.");
		}
		
		$entry->setPortfolioItemBrief(nl2br($entry->getPortfolioItemBrief()));
		
		return $this->render('RecruiterRecruitBundle:Portfolio:show.html.twig', array('entry' => $entry));
	}
	
	public function editAction($id)
	{		
		$entry = $this->getDoctrine()->getEntityManager()->getRepository('RecruiterRecruitBundle:PortfolioEntry')->find($id);
		$em = $this->getDoctrine()->getEntityManager();
		
		if (!$entry instanceof PortfolioEntry) {
			throw $this->createNotFoundException("Portfolio item not found.");
		}
				
		$form = $this->createFormBuilder($entry)
			->add('portfolio_item_name', 'text', array('label' => 'Project name'))
			->add('portfolio_item_href', 'text', array('label' => 'Link'))
			->add('portfolio_item_brief', 'textarea', array('label' => 'Brief'))
			->getForm()
		;
		
		if ($this->getRequest()->getMethod() === 'POST') {
			$form->bindRequest($this->getRequest());

			$em->persist($entry);

			$em->flush();
		
			return $this->redirect($this->generateUrl('recruiter_recruit_portfolio_homepage', array("id" => $entry->getRecruit()->getId())));
		}
		
		$mode = ($this->getRequest()->isXmlHttpRequest()) ? "ajax" : "html";
			
		return $this->render(
			'RecruiterRecruitBundle:Portfolio:edit.' . $mode . '.twig',
			array(
				'form' => $form->createView()
			)
		);
	}
}
