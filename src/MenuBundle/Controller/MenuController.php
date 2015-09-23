<?php

namespace MenuBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\RegistrationType;
use AppBundle\Form\Model\Registration;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class MenuController extends Controller
{
    /**
     * @Route("/menu", name="menu_gestion")
     * @Template("MenuBundle:menu:default.html.twig")
     *
     * @return array
     */
    public function defaultAction()
    {
        $fs = new Filesystem();
        
        // traigo el query y lo itero para ordenarlo
        $array=array(
            'uno' => array(
                'go1','go2'
            ),
            'dos' => 'resp2'
        );
        
        $yml = new \Symfony\Component\Yaml\Dumper;
        
        $fs->dumpFile('file.yml', $yml->dump($array,2));

        return $this->render('MenuBundle:Menu:default.html.twig', array(
                'yml' => $yml->dump($array,2)
// ...
            ));    
        
    }

    public function generateMenuAction()
    {
        return $this->render('MenuBundle:Menu:generateMenu.html.twig', array(
                // ...
            ));    }

    public function itemsAction()
    {
        return $this->render('MenuBundle:Menu:items.html.twig', array(
                // ...
            ));    }

}
