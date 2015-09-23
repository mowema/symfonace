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
use AppBundle\Form\Model\Registration;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use AppBundle\Entity\User;

class AccountController extends Controller
{

    public function registerAction()
    {
        $form = $this->createForm(new RegistrationType(), new Registration(), array(
            'action' => $this->generateUrl('account_create'),
        ));

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