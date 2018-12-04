<?php
namespace Entities;

/**
 * @Entity
 * @Table(name="categories", options={"where": "(visible = 1)"})
 */
class Category
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
     * @Column(type="string")
     * @var string
     **/
    public $icon;
	
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
	
    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @OneToMany(targetEntity="Subcategory", mappedBy="category")
	 * @OrderBy({"priority" = "ASC"})
     */
    private $subcategories;
    public $subcategoriesLists;
    public function __construct()
    {
        $this->subcategories = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }
    public function __toString()
    {
        return $this->getTitle();
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
	
    /**
     * Add subcategory
     *
     * @param \Entities\Subcategory $subcategory
     *
     * @return Category
     */
    public function addSubcategory(\Entities\Subcategory $subcategory)
    {
        $this->subcategories[] = $subcategory;

        return $this;
    }

    /**
     * Remove subcategory
     *
     * @param \Entities\Subcategory $subcategory
     */
    public function removeSubcategory(\Entities\Subcategory $subcategory)
    {
        $this->subcategories->removeElement($subcategory);
    }

    /**
     * Get subcategories
     *
     * @return \Doctrine\Common\Collections\Collection
	 * @OrderBy({"priority" = "ASC"})
	 * 
     */
    public function getSubcategories()
    {
		return $this->subcategories->filter(function(Subcategory $subcat) {
			return $subcat->getVisible() == 1;
			});
    }

}
