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

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


class MenuController extends Controller
{
    /**
     * @Route(
     *      "/menu/{id}/{name}",
     *      defaults={"name": "editar"},
     *      name="menu_gestion",
     *      requirements={
     *          "name" : ".+"
     *      }
     * )
     * @Template("MenuBundle:menu:default.html.twig")
     *
     * @return array
     */
    public function defaultAction($id,$name)
    {
        $fs = new Filesystem();

        $product = $this->getDoctrine()
            ->getRepository('MenuBundle:MenuItem')
            ->find($id);


        if (!$id) {
            throw $this->createNotFoundException(
                'No element found for id '
            );
        }

        $hydrator = new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($entityManager);
        $entityArray = $hydrator->extract($entity);

        //$encoders = array(new XmlEncoder(), new JsonEncoder());
        //$normalizers = array(new GetSetMethodNormalizer());

        //$serializer = new Serializer($normalizers, $encoders);


        //$jsonContent = $serializer->serialize($product, 'json');

        // traigo el query y lo itero para ordenarlo
        //$array = (array) $product;
        dump($entityArray);exit();

        $yml = new \Symfony\Component\Yaml\Dumper;

        $fs->dumpFile('file.yml', $yml->dump($entityArray,2));

        return $this->render('MenuBundle:Menu:default.html.twig', array(
                'yml' => $yml->dump($array,2)
// ...
            ));

    }

    private function _armar($array) {
        $arrayfinal = array ();
        foreach ($array as $elemento){
            echo $elemento;
            //if (is_array($elemento))
            //$arrayfinal['label'] = 'qwe';
            
        }
        return $arrayfinal;
    }
    
    /**
     * @Route(
     *      "/show-menu"
     * )
     * @Template("MenuBundle:menu:default.html.twig")
     * @return array
     */
    
    public function showMenuAction()
    {
        $fs = new Filesystem();
        $repo = $this->getDoctrine()->getRepository('MenuBundle:MenuItem');
        $nodos = $repo->getRootNodes();
        //$elem = $repo->getRootNodes($repo);
        //
        
        foreach($nodos as $nodo) {
            $arbol = $repo->find($nodo->getId());
            $elarbol = $repo->childrenHierarchy($arbol,false);
            
            //dump($repo->childrenHierarchy($arbol,false));
        }
        
        $yml = new \Symfony\Component\Yaml\Dumper;
        $arraynuevo = array();
        //echo '<pre>'; print_r($elarbol);exit();
        
        foreach ($elarbol as $rama){
            
            $parcial[$rama['id'].$rama['title']] = $this->_armar($rama);
            //$parcial['uri'] = $rama['uri'];
            //$arraynuevo[];
            
            
            //$arraynuevo[] = $parcial;
            //dump ($yml->dump($rama,4));
            
        }
        
        dump ($parcial);
        
        //dump ($yml->dump($elarbol,4));
        
        $fs->dumpFile('file.yml', $yml->dump($elarbol,4));
        exit();
            
        foreach($nodos as $nodo) {
            dump ($repo->childrenHierarchy($nodo)); 
            dump ($repo->getPath($nodo)); 
            dump ($repo->children($nodo)); 
            dump ($repo->getChildren($nodo));
            dump ($repo->getLeafs($nodo)); 
            dump ($repo->getNodesHierarchy($nodo)); 
            
            
                    
        }
       
        
        
        //dump($repo);
        return $this->render('MenuBundle:Menu:items.html.twig', array(
                'yml' => $yml->dump($array,2)));
    }
    /**
     * @Route(
     *      "/generate"
     * )
     * @Template("MenuBundle:menu:default.html.twig")
     * @return array
     */
    public function generateAction()
    {
        //$repo = $this->getDoctrine()->getRepository('MenuBundle:MenuItem');
        //$arrayTree = $repo->childrenHierarchy();
        
        $itemuno = new \MenuBundle\Entity\MenuItem();
        $itemuno->setTitle('hey');
        $itemdos = new \MenuBundle\Entity\MenuItem();
        $itemdos->setTitle('hey2');
        $itemdos->setParent($itemuno);
        $item3 = new \MenuBundle\Entity\MenuItem();
        $item3->setTitle('hey3');
        $item3->setParent($itemdos);
        $em = $this->getDoctrine()->getManager();
        $em->persist($itemuno);
        $em->persist($itemdos);
        $em->persist($item3);
        $em->flush();
        //dump($arrayTree);exit();
        return $this->render('MenuBundle:Menu:items.html.twig', array(
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
