<?php
namespace MenuBundle\Entity;

namespace MenuBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="MenuBundle\Entity\MenuItemRepository")
 * @ORM\Table(name="app_menu_items")
 */
class MenuItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;


    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     * @Assert\Null()
     */

    private $title;

    /**
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     * @Assert\Null()
     */
    private $label;

    /**
     * @ORM\Column(name="uri", type="string", length=255, nullable=true)
     * @Assert\Null()
     */
    private $uri;

    /**
     * @ORM\Column(name="display", type="boolean", length=255, nullable=true)
     * @Assert\Null()
     */
    private $display ;

    /**
     * @ORM\Column(name="displaychildren", type="boolean", length=255, nullable=true)
     * @Assert\Null()
     */
    private $displaychildren ;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     * @Assert\Null()
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     **/
    private $children;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $parent;
    // ...

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getUri() {
        return $this->uri;
    }

    public function getDisplay() {
        return $this->display;
    }

    public function getDisplaychildren() {
        return $this->displaychildren;
    }

    public function getPosition() {
        return $this->position;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    public function setUri($uri) {
        $this->uri = $uri;
        return $this;
    }

    public function setDisplay($display) {
        $this->display = $display;
        return $this;
    }

    public function setDisplaychildren($displaychildren) {
        $this->displaychildren = $displaychildren;
        return $this;
    }

    public function setPosition($position) {
        $this->position = $position;
        return $this;
    }

    public function setParent(MenuItem $parent = null) {
        $this->parent = $parent;
        return $this;
    }

        /*
    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getLabel() {
        return $this->label;
    }

    function getUri() {
        return $this->uri;
    }

    function getDisplay() {
        return $this->display;
    }

    function getDisplaychildren() {
        return $this->displaychildren;
    }

    function getOrder() {
        return $this->order;
    }

    function getChildren() {
        return $this->children;
    }

    function getParent() {
        return $this->parent;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function setUri($uri) {
        $this->uri = $uri;
    }

    function setDisplay($display) {
        $this->display = $display;
    }

    function setDisplaychildren($displaychildren) {
        $this->displaychildren = $displaychildren;
    }

    function setOrder($order) {
        $this->order = $order;
    }

    function setChildren($children) {
        $this->children = $children;
    }

    function setParent($parent) {
        $this->parent = $parent;
    }
    */


}
