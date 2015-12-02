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
use AppBundle\Form\Type\FilterUserType;
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
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createForm(new FilterUserType());
        
        
        $session = $this->getRequest()->getSession();
        $page = $request->query->getInt('page', 1)/*page number*/;
        //$dql   = "SELECT a FROM AppBundle:User AS a WHERE 1=1 ";
        
        if ($request->isMethod('POST')) {
            if ($request->request->get('filter')['email'] != ''){
                $session->set('filter-email',$request->request->get('filter')['email'] );
            } else {
                $session->set('filter-email',null );
            }
            if ($request->request->get('filter')['fname'] != ''){
                $session->set('filter-nombre',$request->request->get('filter')['fname'] );
            } else {
                $session->set('filter-nombre',null );
            }
            if ($request->request->get('filter')['lname'] != ''){
                $session->set('filter-apellido',$request->request->get('filter')['lname'] );
            } else {
                $session->set('filter-apellido',null );
            }
            $page = 1;
            
            //redirects campos submit
        }
        
        //$dql .= " WHERE true AND a.fname LIKE '%don%' AND a.fname LIKE '%d%' ";
       
        if ($session->get('filter-email') !== null){
            $form->get('email')->setData($session->get('filter-email'));
        }
        
        if ($session->get('filter-nombre') !== null){
            $form->get('fname')->setData($session->get('filter-nombre'));
        }
        if ($session->get('filter-apellido') !== null){
            $form->get('lname')->setData($session->get('filter-apellido'));
        }
        
        $entities = $em->getRepository('AppBundle:User')->listUsers(array(
            'email'=>$session->get('filter-email')?:'',
            'fname'=>$session->get('filter-nombre')?:'',
            'lname'=>$session->get('filter-apellido')?:'',
            ));
        
        // si toogle variable a twig
        
        
        
        //$session->set('dql', $dql);
        
        //$query = $em->createQuery($session->get('dql'));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entities,
            //$entities,
            $page,
            10/*limit per page*/
        );
        return array(
            'pagination' => $pagination,
            'form' => $form->createView(),
            //'entities' => $entities,
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
        $header='Nuevo Registro ';
        
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

        return  array('form' => $form->createView(),
                'encabezado'=>$header);
    }
     /**
     * @Route("/blanqueo-usuario", name="blankpass")
     * 
     * @return array
     */
    
    public function changePassword(Request $request){
        $em = $this->getDoctrine()->getManager();
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        if ($request->isXmlHttpRequest()) {
            $result = array(
                'id' => $request->request->get('user')['id'],
                'notifico' => $request->request->get('notifico'),
                //'isactive' => $request->request->get('isactive'),
                'password' => $request->request->get('user')['password']);
            
            if($request->request->get('notifico')=='notifico'){
                //mando mail
                die();
                $result.=array('enviado' => 'si');
            }
            
            $entity = $em->getRepository('AppBundle:User')->find($request->request->get('user')['id']);
            $hash = $this->get('security.password_encoder')->encodePassword($entity, $request->request->get('user')['password']);
            $entity->setPassword($hash);
            //$entity->setPassword($request->request->get('user')['password']);
            $em->persist($entity);
            $em->flush();
            
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        }
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
        
        $header='Editando Registro ';
        
        $form = $this->createForm(new UserType(array('mode'=>'edit')), $entity, array(
            'action' => $this->generateUrl('edit_user',array('id'=>$id)),
        ));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $editPlan = $form->getData();
            $em->persist($editPlan);
            $em->flush();
            
            if ($form->get('submit')->isClicked()) {
            // voy al listado
                return $this->redirectToRoute('list_users');
                // mensaje al alerta
            }
            if ($form->get('submitundnew')->isClicked()) {
            // nuevo usuario
                return $this->redirectToRoute('create_user');
                // mensaje al alerta
            }
            
        }

        return  array('form' => $form->createView(),
                'encabezado'=>$header);
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new RegistrationType(), new Registration());
        $form->handleRequest($request);
        
        $encoder = $this->get('security.encoder_factory')->getEncoder($entity);

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
    
    function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&amp;*?';

        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++){
        $password .= $all[array_rand($all)];}

        $password = str_shuffle($password);

        if(!$add_dashes)
            return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }
}