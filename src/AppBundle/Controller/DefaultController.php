<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** 
     * @Route("/" )
     * 
     */ 
class DefaultController extends Controller
{
    /** 
     * @Template("AppBundle:Default:index.html.twig")
     */ 
    public function secondAction($id) { 
        return array(); }

    /**
     * @Template("AppBundle:Default:index.html.twig")
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $slug = $this->get('app.slugger')->slugify('sanéo el texto');
        // replace this example code with whatever you need
        
        //$this->get('session')->getFlashBag()->set('error', 'Does Not Exist');
        
        return array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'slug' => $slug
        );
        
    }
    
}

