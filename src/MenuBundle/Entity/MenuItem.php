<?php
namespace MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\Null() 
     */
    
    private $name;
    
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
     * @ORM\Column(name="order", type="integer", nullable=true)
     * @Assert\Null() 
     */
    private $order;

    
    

}
