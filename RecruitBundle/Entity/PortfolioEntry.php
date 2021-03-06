<?php

namespace Recruiter\RecruitBundle\Entity;

use Recruiter\CommonBundle\Entity\Upload;

use Doctrine\ORM\Mapping as ORM;

/**
 * PortfolioEntry
 *
 * @ORM\Table(name="portfolio_entries")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PortfolioEntry
{
	protected $file;
	protected $path;
	
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
     * @ORM\Column(name="portfolio_item_name", type="string", length=255)
     */
    private $portfolio_item_name;

    /**
     * @var string
     *
     * @ORM\Column(name="portfolio_item_href", type="text")
     */
    private $portfolio_item_href = "";

    /**
     * @var string
     *
     * @ORM\Column(name="portfolio_item_brief", type="text")
     */
    private $portfolio_item_brief;
    
    /**
     * @var string
     *
     * @ORM\Column(name="portfolio_item_file_name", type="text")
     */
    private $portfolio_item_file_name = "";

    /**
     * @ORM\ManyToOne(targetEntity="Recruit", inversedBy="portfolio_entries")
     * @var Recruit
     */
    private $recruit;

    /**
     * @ORM\OneToOne(targetEntity="Recruiter\CommonBundle\Entity\Upload", inversedBy="portfolio_entry")
     * @var Upload
     */
    private $upload;

    /**
     * @ORM\PrePersist()
     */
    public function preUpload()
    {
    	if (null !== $this->file) {
    		// do whatever you want to generate a unique name
    		$filename = sha1(uniqid(mt_rand(), true));
    		$filename = $filename.'.'.$this->file->guessExtension();
    		$this->path = $filename;
    		$this->setPortfolioItemFileName($filename);
    	}
    }
    
    /**
     * @ORM\PostPersist()
     */
    public function upload()
    {
    	if (null === $this->file) {
    		return;
    	}
    
    	// if there is an error when moving the file, an exception will
    	// be automatically thrown by move(). This will properly prevent
    	// the entity from being persisted to the database on error
    	$this->file->move($this->getUploadRootDir(), $this->path);
    
    	unset($this->file);
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
    	if ($file = $this->getAbsolutePath()) {
    		unlink($file);
    	}
    }
    
    public function getAbsolutePath()
    {
    	return null === $this->path
    	? null
    	: $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath()
    {
    	return '/'.$this->getUploadDir().'/'.$this->getPortfolioItemFileName();
    }
    
    protected function getUploadRootDir()
    {
    	// the absolute directory path where uploaded
    	// documents should be saved
    	return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
    	// get rid of the __DIR__ so it doesn't screw up
    	// when displaying uploaded doc/image in the view.
    	return 'uploads/documents' . '/portfolio';
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
     * Set portfolio_item_name
     *
     * @param string $portfolioItemName
     * @return PortfolioEntry
     */
    public function setPortfolioItemName($portfolioItemName)
    {
        $this->portfolio_item_name = $portfolioItemName;
    
        return $this;
    }

    /**
     * Get portfolio_item_name
     *
     * @return string 
     */
    public function getPortfolioItemName()
    {
        return $this->portfolio_item_name;
    }

    /**
     * Set portfolio_item_href
     *
     * @param string $portfolioItemHref
     * @return PortfolioEntry
     */
    public function setPortfolioItemHref($portfolioItemHref)
    {
    	if ($portfolioItemHref == null) {
    		$portfolioItemHref = "";
    	}
    	
        $this->portfolio_item_href = $portfolioItemHref;
    
        return $this;
    }

    /**
     * Get portfolio_item_href
     *
     * @return string 
     */
    public function getPortfolioItemHref()
    {
        return $this->portfolio_item_href;
    }

    /**
     * Set portfolio_item_brief
     *
     * @param string $portfolioItemBrief
     * @return PortfolioEntry
     */
    public function setPortfolioItemBrief($portfolioItemBrief)
    {
        $this->portfolio_item_brief = $portfolioItemBrief;
    
        return $this;
    }

    /**
     * Get portfolio_item_brief
     *
     * @return string 
     */
    public function getPortfolioItemBrief()
    {
        return $this->portfolio_item_brief;
    }

    /**
     * Set recruit
     *
     * @param \Recruiter\RecruitBundle\Entity\Recruit $recruit
     * @return PortfolioEntry
     */
    public function setRecruit(\Recruiter\RecruitBundle\Entity\Recruit $recruit = null)
    {
        $this->recruit = $recruit;
    
        return $this;
    }

    /**
     * Get recruit
     *
     * @return \Recruiter\RecruitBundle\Entity\Recruit 
     */
    public function getRecruit()
    {
        return $this->recruit;
    }
    
    public function __toString()
    {
    	return $this->getPortfolioItemName();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uploads = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getFile()
    {
    	return $this->file;
    }
    
    public function setFile($file)
    {
    	$this->file = $file;
    }
    
    /**
     * Add uploads
     *
     * @param \Recruiter\CommonBundle\Entity\Upload $uploads
     * @return PortfolioEntry
     */
    public function addUpload(\Recruiter\CommonBundle\Entity\Upload $uploads)
    {
        $this->uploads[] = $uploads;
    
        return $this;
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

    /**
     * Set upload
     *
     * @param \Recruiter\CommonBundle\Entity\Upload $upload
     * @return PortfolioEntry
     */
    public function setUpload(\Recruiter\CommonBundle\Entity\Upload $upload = null)
    {
        $this->upload = $upload;
    
        return $this;
    }

    /**
     * Get upload
     *
     * @return \Recruiter\CommonBundle\Entity\Upload 
     */
    public function getUpload()
    {
        return $this->upload;
    }

    /**
     * Set portfolio_item_file_name
     *
     * @param string $portfolioItemFileName
     * @return PortfolioEntry
     */
    public function setPortfolioItemFileName($portfolioItemFileName)
    {
        $this->portfolio_item_file_name = $portfolioItemFileName;
    
        return $this;
    }

    /**
     * Get portfolio_item_file_name
     *
     * @return string 
     */
    public function getPortfolioItemFileName()
    {
        return $this->portfolio_item_file_name;
    }
}