<?php
namespace Entities;

/**
 * @Entity
 * @Table(name="cities")
 */
class City
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     **/
    public $id;

    /**
     * @Column(type="string")
     * @var string
     **/
    public $title;

    /**
     * @Column(type="datetime")
     **/
    protected $created_at;

    /**
     * @Column(type="datetime")
     **/
    protected $updated_at;
	
	/**
     * @Column(type="integer")
     * @var integer
	 * @OrderBy({"priority" = "ASC"})
     **/    
	public $priority;
	
    /**
     * @Column(type="boolean",nullable=true)
     * @var string
     **/
    public $visible;
	
    // ...
    /**
     * Many Groups have Many Users.
     * @ManyToMany(targetEntity="Service", mappedBy="cities")
     */
    private $services;

    public $servicesCount;
    public function __construct()
    {
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->updated_at = new \DateTime("now");
        $this->created_at = new \DateTime("now");
		$this->priority = 0;
		$this->visible = true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
    /**
     * Add service
     *
     * @param \Entities\Service $service
     *
     * @return City
     */
    public function addService(\Entities\Service $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \Entities\Service $service
     */
    public function removeService(\Entities\Service $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }

	public function setPriority($priority)
    {
		$this->priority = $priority;
        return $this;
	}
	public function getPriority()
    {
		return $this->priority;
    }
	public function setVisible($visible)
    {
		$this->visible = $visible;
        return $this;
	}
	public function getVisible()
    {
		return $this->visible;
    }
}
