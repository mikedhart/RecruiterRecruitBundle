<?php

namespace Recruiter\RecruitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Recruiter\RecruitBundle\Entity\Recruit;
use Recruiter\RecruitBundle\Form\RecruitType;
use Symfony\Component\HttpFoundation\Response;
use Exporter\Writer\CsvWriter;
use Recruiter\CommonBundle\Services\CsvExporter;

/**
 * Recruit controller.
 *
 */
class RecruitController extends Controller
{
	public function exportAction()
	{
		$recruits = $this->getDoctrine()->getEntityManager()->getRepository('RecruiterRecruitBundle:Recruit')->findAll();
		
		$csv = new CsvExporter();
		$csv->setColumnHeaders(array("First name", "Last name", "Email address"));
		
		foreach ($recruits as $recruit) {
			$row = array(
				"first_name" => $recruit->getUser()->getFirstName(),
				"last_name" => $recruit->getUser()->getLastName(),
				"email_address" => $recruit->getUser()->getEmail()
			);
			
			$csv->addRow($row);
		}
		
		$csv->doExport();
	}
	
	public function chartAction()
	{
		$handler = $this->get('recruiter_recruit.recruithandler');

		return new Response($handler->getChartData());
	}
	
    /**
     * Lists all Recruit entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RecruiterRecruitBundle:Recruit')->fetchLatest();

        return $this->render('RecruiterRecruitBundle:Recruit:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Recruit entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RecruiterRecruitBundle:Recruit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recruit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RecruiterRecruitBundle:Recruit:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Recruit entity.
     *
     */
    public function newAction()
    {
        $entity = new Recruit();
        $form   = $this->createForm(new RecruitType(), $entity);

        return $this->render('RecruiterRecruitBundle:Recruit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Recruit entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Recruit();
        $form = $this->createForm(new RecruitType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('recruit_show', array('id' => $entity->getId())));
        }

        return $this->render('RecruiterRecruitBundle:Recruit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Recruit entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RecruiterRecruitBundle:Recruit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recruit entity.');
        }

        $editForm = $this->createForm(new RecruitType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RecruiterRecruitBundle:Recruit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Recruit entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RecruiterRecruitBundle:Recruit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recruit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RecruitType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('recruit_edit', array('id' => $id)));
        }

        return $this->render('RecruiterRecruitBundle:Recruit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Recruit entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RecruiterRecruitBundle:Recruit')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Recruit entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('recruit'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    public function searchAction()
    {
    	if ($this->getRequest()->getMethod() === "POST") {
        	$handler = $this->get("recruiter_employer.searchhandler");
           	$handler->buildQuery($_POST);
           	$recruits = $handler->run();

          	return $this->render("RecruiterRecruitBundle:Recruit:search_results.html.twig", array("recruits" => $recruits));
  		}

  		$em = $this->getDoctrine()->getEntityManager();
    	$skills = $em->getRepository("RecruiterRecruitBundle:Skill")->findAll();
    	$locations = $em->getRepository("RecruiterCommonBundle:Location")->findAll();
    	$jobTypes = $em->getRepository("RecruiterRecruitBundle:JobType")->findAll();
    	
        return $this->render(
        	"RecruiterRecruitBundle:Recruit:search.html.twig", 
        	array("skills" => $skills, "locations" => $locations, "job_types" => $jobTypes)
        );
    }
    
    public function listAction()
    {
    	return $this->indexAction();
    }
}
