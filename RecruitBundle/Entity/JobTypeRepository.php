<?php

namespace Recruiter\RecruitBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * JobTypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class JobTypeRepository extends EntityRepository
{
	public function findAllByIds($ids)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb ->add("select", "s")
			->add("from", "RecruiterRecruitBundle:JobType s")
			->add("where", "s.id in (" . implode(",", $ids) . ")");
	
		return $qb->getQuery()->getResult();
	}
}
