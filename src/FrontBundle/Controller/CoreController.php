<?php
namespace FrontBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;


use AppBundle\Entity\User;

class CoreController extends Controller
{
    /**
     * @Route("/escritorio", name="core_admin")
     * @Template("FrontBundle:Core:index.html.twig")
     *
     * @return array
     */
    
    public function adminAction()
    {
        return array();
    }

    /**
     * @Route("/administrador/crea", name="core_create")
     * @Template("AppBundle:Core:register.html.twig")
     *
     * @return array
     */

    public function createAction(Request $request)
    {
            return new Response('Hello world!');
    }
    
}