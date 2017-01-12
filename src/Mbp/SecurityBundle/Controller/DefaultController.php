<?php

namespace Mbp\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function loginAction(Request $request)
    {
        $request = $this->getRequest();
        $session = $request->getSession();		
		

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'MbpSecurityBundle:Default:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
	
	public function logoutAction()
    {
    	$sesion="Usted ha cerrado Sesion correctamente";
        return $this->render('MbpSecurity:Default:logout.html.twig', array(
        'mensaje'=>$sesion
        ));
    }
	
	public function sesionAction()
	{
		
		$roles = $this->get('security.context')->getToken()->getUser()->getRoles();
		$role = $roles[0]->getRole(); 
		$name = $this->get('security.context')->getToken()->getUsername();
		
		$data = array(
			'nombre' => $name,
			'role' => $role
		);
		
		echo json_encode($data);
		
		return new Response();
	}
}





