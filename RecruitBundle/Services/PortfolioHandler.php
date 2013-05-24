<?php
namespace Recruiter\RecruitBundle\Services;

use Recruiter\RecruitBundle\Entity\PortfolioEntry;

class PortfolioHandler extends RecruitHandler
{
	public function getEntries()
	{
		return $this->getRecruit()->getPortfolioEntries();	
	}
	
	public function createEntryInstance()
	{
		$entry = new PortfolioEntry();
		$entry->setRecruit($this->getRecruit());
		
		return $entry;
	}
}
