<?php


namespace Mbp\ProduccionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ProduccionBundle\Entity\ProduccionSoldado;

use Mbp\ProduccionBundle\Clases\Calculo;


class ProduccionSoldadoController extends Controller
{
	/**
     * @Route("/listarProduccion", name="mbp_produccion_listarProduccionSoldado", options={"expose"=true})
     */
	public function listarProduccion()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		
		try{
			$repo = $em->getRepository('MbpProduccionBundle:ProduccionSoldado');
			
			$delay = new \DateTime('-1 months');
		
			$data = $repo->createQueryBuilder('p')
				->select("DATE_FORMAT(p.fecha, '%d/%m/%Y') as fecha,
						DATE_FORMAT(p.hsInicio, '%H:%i') as hsInicio,
						DATE_FORMAT(p.hsFin, '%H:%i') as hsFin,
						p.id,
						p.ot,
						p.cantidad,
						p.observaciones,
						personal.idP as idP,
						personal.nombre,
						CONCAT(op.codigo, ' | ',op.descripcion) as operacion,
						op.id as operacionId
						")
				->join('p.personalId', 'personal')
				->join('p.operacionId', 'op')
				->where('p.fecha > :delay')
				->setParameter('delay', $delay)
				->orderBy('p.id', 'ASC')
				->getQuery()
				->getArrayResult();
				
			$resp = json_encode(array('success' => true, 'data' => $data));
			
			return new Response($resp);	
		}catch(\Exception $e){
			$resp = json_encode(array('success' => false, 'msg' => $e->getMessage()));
			
			return new Response($resp, 500);
		}
	}
	
	/**
     * @Route("/nuevoRegistroSoldado", name="mbp_produccion_nuevoRegistroSoldado", options={"expose"=true})
     */
	public function nuevoRegistroSoldado()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repoPersonal = $em->getRepository('MbpPersonalBundle:Personal');
		$repoProd = $em->getRepository('MbpProduccionBundle:ProduccionSoldado');
		$repoOperaciones = $em->getRepository('MbpProduccionBundle:Operaciones');
		
		
		try{
			$data = $req->request->get('data');
			$decodeData = json_decode($data);
						
			$registro;
			if($decodeData->id > 0){
				$registro = $repoProd->find($decodeData->id);
			}else{
				$registro = new ProduccionSoldado;
			}
			
			
			
			$registro->setFechaCarga(new \DateTime());
			$registro->setFecha(\DateTime::createFromFormat('d/m/Y', $decodeData->fecha));
			$registro->setOt($decodeData->ot);
			$registro->setHsInicio(\DateTime::createFromFormat('H:i', $decodeData->hsInicio));
			$registro->setHsFin(\DateTime::createFromFormat('H:i', $decodeData->hsFin));
			$registro->setObservaciones($decodeData->observaciones);
			$registro->setCantidad($decodeData->cantidad);
			
			
			$empleado = $repoPersonal->find($decodeData->idP);
			$operacion = $repoOperaciones->find($decodeData->operacionId);
			
			$registro->setPersonalId($empleado);
			$registro->setOperacionId($operacion);
			$registro->setUsuarioId($this->get('security.context')->getToken()->getUser());
			
			/* VALIDACIONES */
			$validator = $this->get('validator');
			$errors = $validator->validate($registro);
			if(count($errors) > 0){
				$errList = array();
				foreach ($errors as $error) {
					$errList[$error->getPropertyPath()] = $error->getMessage();
				}
				
				$resp = array(
					'success' => false,
					'msg' => $errList,
					'tipo' => 'validacion',
				);
								
				return new Response(json_encode($resp), 422);		
			}
			
						
			$em->persist($registro);
			$em->flush();
			
			$resp = array(
				'success' => true,
				'data' => array(
					'id' => $registro->getId()
				)
			);
			
			return new Response(json_encode($resp), 200);
		}catch(\Exception $e){
			$resp = array('success' => false, 'msg' => 'No se ingreso correctamente el registro', 'msgDev' => $e->getMessage());
			return new Response(json_encode($resp), 500);
		}
	}
	
	/**
     * @Route("/borrarRegistroSoldado", name="mbp_produccion_borrarRegistroSoldado", options={"expose"=true})
     */
	public function borrarRegistroSoldado()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repoProd = $em->getRepository('MbpProduccionBundle:ProduccionSoldado');
		
		
		try{
			$data = $req->request->get('data');
			$decodeData = json_decode($data);
						
			$registro;
			if($decodeData->id > 0){
				$registro = $repoProd->find($decodeData->id);
			}else{
				throw new \Exception("No se encuentra el registro", 1);
				
			}
			
						
			$em->remove($registro);
			$em->flush();
			
			$resp = array('success' => true);
			$response = new Response;
			return $response->setContent(json_encode($resp));
		}catch(\Exception $e){
			$resp = array('success' => false, 'msg' => 'Error al borrar el registro', 'msgDev' => $e->getMessage());
			return new Response(json_encode($resp), 500);
		}
	}
}

























