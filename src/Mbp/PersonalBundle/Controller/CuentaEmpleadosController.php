<?php

namespace Mbp\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\PersonalBundle\Entity\CuentaEmpleados;
use \DateTime;
use Mbp\PersonalBundle\Clases;

class CuentaEmpleadosController extends Controller
{    
	public function cuentaEmpleadosListAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:CuentaEmpleados');
		$calculoClass = $this->get('calculoConceptos');	
		
		$idP = $req->query->get('idP');
		$comp = $req->query->get('comp');
		
		try{
			$qb = $repo->createQueryBuilder('c')
			->select('')
			->where('c.idPersonal = :idPersonal')
			->andWhere('c.compensatorio = :comp')
			->setParameter('idPersonal', $idP)
			->setParameter('comp', $comp)
			->getQuery();
		
			$res = $qb->getResult();
			if(empty($res)){
				echo json_encode(array(
					'data' => '',
					'succes' => true
				));
				return new Response();
			}
			$cant = count($res);
								
			$data = array();			
			for ($i=0; $i < $cant; $i++) {
				$periodo = $calculoClass->descripcionPeriodo($res[$i]->getPeriodo()).' mes '.$res[$i]->getMes().' del '.$res[$i]->getAnio();
				$data[$i]['id'] = $res[$i]->getId(); 
				$data[$i]['periodo'] = $res[$i]->getConcepto();
				$data[$i]['neto'] = $res[$i]->getNeto();
				$data[$i]['pagado'] = $res[$i]->getPagado();
				$data[$i]['compensatorio'] = $res[$i]->getCompensatorio();				
			}
			
			$calculoClass->calculaSaldo($data, $debe='pagado', $haber='neto');
			
			echo json_encode(array(
				'data' => $data,
				'succes' => true
			));	
		}catch(\Doctrine\ORM\ORMException $e){
			echo json_encode(array(
				'success' => false,
				'msg' => 'Se produjo un error, vuelva a intentarlo'
			));
			$this->get('logger')->error($e->getMessage());
		}		
		
		return new Response();
	}
	
	public function cuentaEmpleadosCreateAction()
	{		
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:CuentaEmpleados');
		$repoEmpleado = $em->getRepository('MbpPersonalBundle:Personal');
		$calculoClass = $this->get('calculoConceptos');	
		
		$data = $req->request->get('data');
		$decodeData = json_decode($data);
		$idP = $req->request->get('idP');
		$empleado = $repoEmpleado->find($idP);
		$comp = $req->request->get('comp');
		
		try{
			$id = $decodeData->id;
			$cuenta;
			if($id > 0){
				$cuenta = $repo->find($id);
			}else{
				$cuenta = new CuentaEmpleados();	
			}
			
			$concepto = $decodeData->periodo;
			$neto = $decodeData->neto;
			
			$date = new DateTime();
			
			$cuenta->setMes($date->format('m'));
			$cuenta->setAnio($date->format('Y'));
			$cuenta->setPagado($neto);
			$cuenta->setConcepto($concepto);
			$cuenta->setIdPersonal($empleado);
			$cuenta->setCompensatorio($comp);
			
			$em->persist($cuenta);
			$em->flush();
			
			$qb = $repo->createQueryBuilder('c')
				->select('')
				->where('c.idPersonal = :idPersonal')
				->andWhere('c.compensatorio = :comp')
				->setParameter('idPersonal', $idP)
				->setParameter('comp', $comp)
				->getQuery();
			
			$res = $qb->getResult();
			
			$cant = count($res);
							
			$periodo = $calculoClass->descripcionPeriodo($res[0]->getPeriodo()).' mes '.$res[0]->getMes().' del '.$res[0]->getAnio();
	
			$data = array();			
			for ($i=0; $i < $cant; $i++) {
				$data[$i]['id'] = $res[$i]->getId(); 
				$data[$i]['periodo'] = $res[$i]->getPeriodo() > 0 ? $periodo : $res[$i]->getConcepto();
				$data[$i]['neto'] = $res[$i]->getNeto();
				$data[$i]['pagado'] = $res[$i]->getPagado();				
			}
			
			$aux = $this->get('calculoConceptos');
			$aux->calculaSaldo($data, $debe='pagado', $haber='neto');
			
			$count = count($data) - 1;
			
			echo json_encode(array(
				'success' => true,
				'data' => array(
					'id' => $data[$count]['id'],
					'periodo' => $decodeData->periodo,
					'neto' => $data[$count]['neto'],
					'pagado' => $data[$count]['pagado'],
					'saldo' => $data[$count]['saldo'],
				)
			));	
		}catch(\Doctrine\ORM\ORMException $e){
			echo json_encode(array(
				'success' => false,
				'msg' => 'Se produjo un error, vuelva a intentarlo'
			));
			$this->get('logger')->error($e->getMessage());
		}
		return new Response();
	}

	public function cuentaEmpleadosDeleteAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpPersonalBundle:CuentaEmpleados');
		
		$jsonData = json_decode($req->request->get('data'));
		try{
			$reg = $repo->find($jsonData->id);
			$em->remove($reg);
			$em->flush();
			
			echo json_encode(array(
				'success' => true
			));	
		}catch(\Doctrine\ORM\ORMException $e){
			echo json_encode(array(
				'success' => false,
				'msg' => 'Se produjo un error, vuelva a intentarlo'
			));
			$this->get('logger')->error($e->getMessage());
		}				
		return new Response();
	}
}























