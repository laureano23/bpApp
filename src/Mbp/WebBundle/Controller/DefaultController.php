<?php

namespace Mbp\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mbp\WebBundle\Entity\SubCategoria;
use Mbp\WebBundle\Clases\ReCaptcha;



use Mbp\WebBundle\Entity\TiposRadiadores;

class DefaultController extends Controller
{
    /**
     * @Route("/calidad", name="calidad")
     * @Cache(smaxage="604800")
     * @Template()
     */
    public function calidadAction()
    {
        return $this->render('MbpWebBundle:newFront:calidad.html.twig');
    }

    /**
     * @Route("/novedades/{page}", name="novedades", defaults={"page": 1})
     * @Cache(smaxage="604800")
     * @Template()
     */
    public function novedadesAction($page)
    {
    	$em = $this->getDoctrine()->getManager('web');
        $repo = $em->getRepository('MbpWebBundle:Novedades');
		$maxResult = 3;
		
		$firstResult=0;
		
		if(isset($page) && $page == 2){
			$firstResult = $maxResult;
		}
		
		if(isset($page) && $page > 2){
			$firstResult = $maxResult * $page;
		}
		
		$query = $repo->createQueryBuilder('n')
			->select('')
			->setFirstResult($firstResult)
			->setMaxResults($maxResult)			
			->orderBy('n.id', 'DESC')
			->getQuery()
			->getResult();
			
			
		$statusNext = "next";
		$statusPrevious = "previous";
		if(count($query) < 3){
			$statusNext = "next disabled";
		}
		
		if($page <= 1){
			$statusPrevious = "previous disabled";
		}
		
		
        return $this->render('MbpWebBundle:newFront:novedades.html.twig', array(
        	'page' => $page,
        	'items' => $query,
			'next' => $statusNext,
			'previous' => $statusPrevious
			));
    }

    public function lastNewsAction()
    {
        $em = $this->getDoctrine()->getManager('web');
        $repo = $em->getRepository('MbpWebBundle:Novedades');
		$maxResult = 3;
		
		$firstResult=0;
		
		$res = $repo->createQueryBuilder('n')
			->select('')
			->setFirstResult($firstResult)
			->setMaxResults($maxResult)			
			->orderBy('n.id', 'DESC')
			->getQuery()
            ->getResult();
            
            return $this->render('MbpWebBundle:newFront:lista_novedades.html.twig', array('items' => $res   ));
    }


    /**
     * @Route("/index", name="index")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function indexAction()
    {
        return $this->render('MbpWebBundle:newFront:index.html.twig');
    }

    /**
     * @Route("/about", name="about")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function about()
    {
        return $this->render('MbpWebBundle:newFront:about.html.twig');
    }

    /**
     * @Route("/contacto", name="contacto")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function contacto()
    {
        $req = $this->getRequest();
		
    	/*CAPTCHA*/
    	$secret = "6Ld-NSoUAAAAACPvwHWgUIgiBam6-_FJzkmFQAMD";
    	$response = null;
 
		try{// check secret key
		    $reCaptcha = new ReCaptcha($secret);
			if ($req->request->get("g-recaptcha-response")) {
			    $response = $reCaptcha->verifyResponse(
			        $req->getClientIp(),
			        $req->request->get("g-recaptcha-response")
			    );
			}
			
			
	    	if($req->request->get("g-recaptcha-response") != "" && !$response->success){
	    		throw new \Exception('Error Captcha', 99);
				
	    	}
	        
	        $nombre = $req->request->get('name');
	        $email = $req->request->get('email');
	        $telefono = $req->request->get('phone');      
	        $asunto = $req->request->get('subject');
	        $consulta = $req->request->get('message');
	
	        if($nombre){            
                $message = \Swift_Message::newInstance();
                $message->setSubject("Consulta web: ".$asunto);
                $message->setFrom('info@metalurgicabp.com.ar');
                $message->setTo("info@metalurgicabp.com.ar");
                $message->setBody("
                    <strong>Nombre y Apellido: </strong>".$nombre."<br>
                    <strong>Email: </strong>".$email."<br>
                    <strong>Teléfono: </strong>".$telefono."<br>
                    <strong>Consulta: </strong>".$consulta."<br>
                    ", "text/html");

                $mailer = $this->get('mailer');
                $mailer->send($message);
                

                $req->getSession()
                    ->getFlashBag()
                    ->add('success', 'En breve responderemos su mensaje');

                $req->getSession()
                    ->getFlashBag()
                    ->add('statusOk', '');
                
               } 
            }catch(\Exception $e){
                throw $e;
                
            	$req->getSession()
                    ->getFlashBag()
                    ->add('statusFail', '');
                    
					
            	if($e->getCode() == 99){
            		$req->getSession()
	                    ->getFlashBag()
	                    ->add('fail', 'Error al validar el Captcha!'); 
            	}                

                $req->getSession()
	                    ->getFlashBag()
	                    ->add('fail', 'Error, por favor intente nuevamente en unos minutos'); 
        
			}
         return $this->render('MbpWebBundle:newFront:contact.html.twig');
    }

    /**
     * @Route("/products", name="products")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function products()
    {
        $em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
    	$req = $this->getRequest();
    	$categoria = $repo->find($req->query->get('cat')); 


        return $this->render('MbpWebBundle:newFront:products.html.twig', array('categorias' => $categoria));
    }

    public function listarCategoriasAsideAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
        $em->clear();
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
    	$items = $repo->findAll();		


    	return $this->render('MbpWebBundle:newFront:lista_categorias_aside.html.twig', array('items' => $items));
    }

    public function listarCategoriasAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
        $locale = $req = $this->getRequest()->getLocale();

    	$items = $repo->listarCategorias($locale);
		
		

    	return $this->render('MbpWebBundle:newFront:lista_categorias.html.twig', array('items' => $items));
    }

    public function listarProductosMainAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
        $locale = $req = $this->getRequest()->getLocale();

        $items = $repo->listarCategorias($locale);
        
		

    	return $this->render('MbpWebBundle:newFront:listMainProducts.html.twig', array('items' => $items));
    }
}
 