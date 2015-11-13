<?php

namespace FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class DefaultController extends Controller
{

    /** 
     * @Template("AppBundle:Default:index.html.twig")
     * @Route("/blog/{name}", name="blog", requirements={"name" = "\d+"}, defaults={"name" = "portada"} )
     */ 
    public function indexAction($name)
    {
        return $this->render('FrontBundle:Default:index.html.twig', array('name' => $name));
    }


    /** 
     * @Template("AppBundle:Default:index.html.twig")
     * @Route("/", name="sub", host="what.{domain}")
     */ 

    public function whatAction(Request $request)
    {
        $slug = $this->get('app.slugger')->slugify('sanéo el texto');
        // replace this example code with whatever you need
        
        $this->get('session')->getFlashBag()->set('error', 'Does Not Exist');
        
        return array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'slug' => $slug
        );
        
    }
}
