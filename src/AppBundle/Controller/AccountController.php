<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\RegistrationType;
use AppBundle\Form\Type\UserType;
use AppBundle\Form\Model\Registration;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use AppBundle\Entity\User;

class AccountController extends Controller
{
    
    /**
     * @Template("AppBundle:Account:perfil.html.twig")
     * @Route("/mi-perfil", name="myprofile")
     */

    public function MyProfileAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(new UserType(), $user);
        return array('form' => $form->createView());
    }
    
    
    /**
     * @Route("/listar-usuarios", name="list_users")
     * @Template("AppBundle:Account:index.html.twig")
     * 
     * @return array
     */
    
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:User')->findAll();
        $dql   = "SELECT a FROM AppBundle:User a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
       $pagination = $paginator->paginate($query,
            //$entities,
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );
        return array(
            'pagination' => $pagination,
            'entities' => $entities,
        );
    }
    
    /**
     * @Route("/cuenta-de-usuario", name="create_user")
     * @Template("AppBundle:Account:crud.html.twig")
     * 
     * @return array
     */
    
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new UserType(), new User(), array(
            'action' => $this->generateUrl('create_user'),
        ));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $editPlan = $form->getData();
            $em->persist($editPlan);
            $em->flush();
        // the validation passed, do something with the $author object

        }

        return  array('form' => $form->createView());
    }
    /**
     * @Route("/editar-cuenta-de-usuario/{id}",requirements={"id" = "\d+"}, name="edit_user")
     * @Template("AppBundle:Account:crud.html.twig")
     * 
     * @return array
     */
    
    public function editRegisterAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:User')->find($id);
        
        if (!$entity) {
            $this->get('session')->getFlashBag()->add('info', 'No record');
            return $this->redirectToRoute('homepage');
        }
        
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('edit_user',array('id'=>$id)),
        ));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $editPlan = $form->getData();
            $em->persist($editPlan);
            $em->flush();
        // the validation passed, do something with the $author object

        }

        return  array('form' => $form->createView());
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new RegistrationType(), new Registration());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $registration = $form->getData();
            $em->persist($registration->getUser());
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        return array('form' => $form->createView());
    }
    
    public function helloAction()
    {
        $user = $this->getUser();
        //var_dump($user->getRoles());
        
        \Symfony\Component\VarDumper\VarDumper::dump($user->getRoles());
        exit();
        $nuser= new \AppBundle\Entity\User;
        $nuser->setUsername('testing');
        $nuser->setPassword('clave');
        $nuser->setEmail('mail@mail.com');
        $nuser->setRoles('ROLE_USER');
        $nuser->setPlainPassword('eliminar');
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($nuser);
        $entityManager->flush();
    
        // creando la ACL
        $aclProvider = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($nuser);
        $acl = $aclProvider->createAcl($objectIdentity);

        // recupera la identidad de seguridad del usuario
        // registrado actualmente
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // otorga permiso de propietario
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $aclProvider->updateAcl($acl);
        
        return new Response('Well hi there ');
    }
        
    public function chauAction()
    {
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        \Symfony\Component\VarDumper\VarDumper::dump($user->getRoles());exit();
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:User')->find('8');    
        // verifica el acceso para edición
        if (false === $securityContext->isGranted('EDIT', $entity))
        {
            throw new AccessDeniedException();
        }
        return new Response('estás validado ');
    }
    

    public function chau2Action()
    {
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:User')->find('4');    
        // verifica el acceso para edición
        if (false === $securityContext->isGranted('EDIT', $user))
        {
            throw new AccessDeniedException();
        }
        return ;
    }
}