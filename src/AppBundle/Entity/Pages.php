<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pages")
 * @ORM\Entity(repositoryClass="PagesRepository")
 */
class Pages
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     * @Assert\Length(
     *     min = 3,
     *     max = 21,
     *     minMessage = "Menu tittle must have between 3 and 21 characters",
     *     maxMessage = "Menu tittle must have between 3 and 21 characters"
     * )
     * @Assert\NotBlank(message = "Menu tittle must have between 3 and 21 characters")
     */
    private $menuTitle;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Pages", inversedBy="Pages")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    private $parent;

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
     * Set content
     *
     * @param string $content
     * @return Pages
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Pages $parent
     * @return Pages
     */
    public function setParent(\AppBundle\Entity\Pages $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Pages
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set menuTitle
     *
     * @param string $menuTitle
     * @return Pages
     */
    public function setMenuTitle($menuTitle)
    {
        $this->menuTitle = $menuTitle;
        return $this;
    }

    /**
     * Get menuTitle
     *
     * @return string
     */
    public function getMenuTitle()
    {
        return $this->menuTitle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parent = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Pages
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set children
     *
     * @param \AppBundle\Entity\Pages $children
     *
     * @return Pages
     */
    public function setChildren(\AppBundle\Entity\Pages $children = null)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return \AppBundle\Entity\Pages
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add parent
     *
     * @param \AppBundle\Entity\Pages $parent
     *
     * @return Pages
     */
    public function addParent(\AppBundle\Entity\Pages $parent)
    {
        $this->parent[] = $parent;

        return $this;
    }

    /**
     * Remove parent
     *
     * @param \AppBundle\Entity\Pages $parent
     */
    public function removeParent(\AppBundle\Entity\Pages $parent)
    {
        $this->parent->removeElement($parent);
    }
}
