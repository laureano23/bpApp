<?php

namespace Mbp\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class PersonalController extends Controller
{
	/**
     * @Route("/list", name="mbp_personal_list", options={"expose"=true})
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:Personal');
		
		$repo->listarPersonal();
		
		return new Response();
    }
	
	/**
     * @Route("/Fulllist", name="mbp_personal_Fulllist", options={"expose"=true})
     */
	 public function listFullAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMINISTRACION')) {
        	throw new AccessDeniedException();
    	}else{
    		$req = $this->getRequest();
			$idPersonal = $req->request->get('idPersonal');
	        $em = $this->getDoctrine()->getManager();
			$repo = $em->getRepository('MbpPersonalBundle:Personal');
						
			$repo->fullListaPersonal($idPersonal);
						
			return new Response();
		}
    }
	
	/**
     * @Route("/create", name="mbp_personal_create", options={"expose"=true})
     */
	public function createAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpPersonalBundle:Personal');
		$data = $req->request->get('data');
		
		$jsonData = json_decode($data);
		$validator = $this->get('validator');
		$res = $repo->crearEmpleado($jsonData, $validator);
				
		$response =  new Response();
		
		$response->setContent(json_encode($res));
		if(array_key_exists('tipo', $res)){
			$response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);	
			$res->setContent(
				json_encode(
					array(
						'success' => false,
						'msg' => $res['errors']
					)
				)				
			);
		}elseif($res['success'] == false){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}else{
			$response->setStatusCode(Response::HTTP_OK);			
		}
		
		return $response;
	}
	
	/*public function editAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpPersonalBundle:Personal');
		$data = $req->request->get('data');
		
		$jsonData = json_decode($data);
		$repo->editaEmpleado($jsonData);
				
		return new Response();
	}*/
	
	/**
     * @Route("/delete", name="mbp_personal_delete", options={"expose"=true})
     */
	public function deleteAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpPersonalBundle:Personal');
		$data = $req->request->get('data');
		
		$jsonData = json_decode($data);
		$idP = $jsonData->idP;
		
		$repo->eliminarEmpleado($idP);
				
		return new Response();
	}
	
	/**
     * @Route("/listProvincias", name="mbp_personal_provinciasList", options={"expose"=true})
     */
	public function listProvinciasAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:Provincias');
		
		$repo->listarProvincias();
		
		return new Response();
	}
	
	/**
     * @Route("/listPartidos", name="mbp_personal_partidosList", options={"expose"=true})
     */
	public function listPartidosAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:Departamentos');
		
		$dql = 'SELECT dep.id AS idPartido, dep.nombre, prov.id AS idProvincia FROM MbpPersonalBundle:Departamentos dep JOIN dep.provinciaId prov
				ORDER BY dep.nombre ASC';
		$query = $em->createQuery($dql);
		$res = $query->getArrayResult();
		
		$jsonRes = json_encode(array(
			'items' => $res
		));
		
		echo $jsonRes;
		
		return new Response();
	}
	
	/**
     * @Route("/listLocalidades", name="mbp_personal_localidadesList", options={"expose"=true})
     */
	public function listLocalidadesAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:Localidades');
		
		$dql = 'SELECT loc.id idLocalidad, loc.nombre, dep.id AS idDepartamento FROM MbpPersonalBundle:Localidades loc JOIN loc.departamentoId dep
				ORDER BY loc.nombre';
		$query = $em->createQuery($dql);
		$res = $query->getArrayResult();
		
		$jsonRes = json_encode(array(
			'items' => $res
		));
		
		echo $jsonRes;
		
		return new Response();
	}
	
	/**
     * @Route("/categoriasList", name="mbp_personal_categoriasList", options={"expose"=true})
     */
	public function listCategoriasAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:Categorias');
		
		$repo->listCategorias();
		
				
		return new Response();	
	}
	
	/**
     * @Route("/sindicatosList", name="mbp_personal_sindicatosList", options={"expose"=true})
     */
	public function listSindicatosAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:Sindicatos');
		
		$repo->listarSindicatos();
		
		return new Response();	
	}	
	
	/**
     * @Route("/categoriasCreate", name="mbp_personal_categoriasCreate", options={"expose"=true})
     */
	public function categoriasCreateAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:Categorias');
		$req = $this->getRequest();
		$data = $req->request->get('data');
		
		$jsonData = json_decode($data);
		
		$repo->crearCategoria($jsonData);
		
		return new Response();
	}
	
	/**
     * @Route("/categoriasDelete", name="mbp_personal_categoriasDelete", options={"expose"=true})
     */
	public function categoriasDeleteAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:Categorias');
		$req = $this->getRequest();
		$data = $req->request->get('data');
		
		$jsonData = json_decode($data);
		
		$repo->borrarCategoria($jsonData);
		
		return new Response();
	}
	
	/**
     * @Route("/sindicatoCreate", name="mbp_personal_sindicatoCreate", options={"expose"=true})
     */
	public function sindicatoCreateAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$jsonData = json_decode($data);
		$repo = $em->getRepository('MbpPersonalBundle:Sindicatos');
		
		if($jsonData->idSindicato > 0){
			$repo->updateSindicato($jsonData);
		}else{
			$repo->crearSindicato($jsonData);		
		}
		
		return new Response();	
	}	
	
	/**
     * @Route("/sindicatoDelete", name="mbp_personal_sindicatoDelete", options={"expose"=true})
     */
	public function sindicatoDeleteAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$jsonData = json_decode($data);
		$repo = $em->getRepository('MbpPersonalBundle:Sindicatos');
		
		$repo->borrarSindicato($jsonData->idSindicato);
		
		return new Response();
	}
	
	/**
     * @Route("/datosFijosPersonaRead", name="mbp_personal_datosFijosPersonaRead", options={"expose"=true})
     */
	public function datosFijosPersonaReadAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$idP = $req->query->get('idP');
		$repo = $em->getRepository('MbpPersonalBundle:Personal');
		
		$repo->listDatoFijoPersona($idP);		
		
		return new Response();
	}
	
	/**
     * @Route("/datosFijosPersonaCreate", name="mbp_personal_datosFijosPersonaCreate", options={"expose"=true})
     */
	public function datosFijosPersonaCreateAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$idP = $req->request->get('idP');
		$datosFijos = $req->request->get('data');
		$datosFijos = json_decode($datosFijos); 
		
		$repo = $em->getRepository('MbpPersonalBundle:PersonalConceptosSueldo');
		
		$repo->creaDatoFijoPersona($idP, $datosFijos->idDatosFijos);		
		
		return new Response();
	}
	
	/**
     * @Route("/datosFijosPersonaDelete", name="mbp_personal_datosFijosPersonaDelete", options={"expose"=true})
     */
	public function datosFijosPersonaDeleteAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repoPersonalConcepto = $em->getRepository('MbpPersonalBundle:PersonalConceptosSueldo');
				
		$idP = $req->request->get('idP');
		$idConcepto = $req->request->get('idDatoFijo');
		
		$repoPersonalConcepto->eliminarConcepto($idP, $idConcepto);
		
		return new Response();
	}
}





















