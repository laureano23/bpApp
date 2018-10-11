<?php
namespace Mbp\FinanzasBundle\Entity;

/**
 * FacturasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FacturasRepository extends \Doctrine\ORM\EntityRepository
{
	public function citiVentasCbtes(\DateTime $desde, \DateTime $hasta)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');

		//si no hay IVA liquidado en la factura el importe neto es 0 y se completa el campo importe excento
		//el doc del cliente esta fijado en 80 que es CUIT, si se modifica hay q cambiar la consulta

		$desdeSql=$desde->format('Y-m-d');
		$hastaSql=$desde->format('Y-m-d');

		$res=$repoFacturas->createQueryBuilder('f')
				->select("
                    DATE_FORMAT(f.fecha, '%Y%m%d') AS fechaEmision,
                    LPAD(tipo.codigoAfip, 3, '0') as tipoCbteAfip,
                    LPAD(f.ptoVta, 5, '0') AS ptoVta,
                    LPAD(f.fcNro, 20, '0') AS fcNroDesde,
					LPAD(f.fcNro, 20, '0') AS fcNroHasta,
					LPAD(80, 2, '0') AS codDocumento,
                    CASE WHEN posicionIVA.esResponsableInscripto = true 
                        THEN LPAD(cliente.cuit, 20, '0')
                        ELSE '' END AS numIdentificacion,
					RPAD(REPLACE(f.rSocial, '°', ' '), 30, ' ') AS nombreComprador,
					CASE WHEN tipo.esNotaCredito=true
						THEN LPAD(LPAD(REPLACE(ROUND((f.total*f.tipoCambio), 2), '.', ''), 14, 0), 15, '0')
						ELSE LPAD(REPLACE(ROUND((f.total*f.tipoCambio), 2), '.', ''), 15, 0) END as montoTotal,
					CASE WHEN f.iva21 > 0 AND tipo.esNotaCredito=true  
						THEN LPAD(0, 15, '0') 
						WHEN f.iva21 = 0 AND tipo.esNotaCredito=true
						THEN LPAD(LPAD(REPLACE(ROUND(((f.total-f.iva21-f.perIIBB)*f.tipoCambio), 2), '.', ''), 14, '0'), 15, '0') 
						WHEN f.iva21 > 0 AND tipo.esNotaCredito=false
						THEN LPAD(0, 15, '0') 
						ELSE LPAD(REPLACE(ROUND(((f.total-f.iva21-f.perIIBB) * f.tipoCambio), 2), '.', ''), 15, '0') 
						END AS montoNoGrabado,	
					LPAD(0, 15, '0') AS percepcionNoCategorizados,   
					LPAD(0, 15, '0') AS montoExcento,  					
					LPAD(0, 15, '0') AS pagoCuentaImpNacionales,   
					CASE WHEN tipo.esNotaCredito=true
						THEN LPAD(LPAD(REPLACE(ROUND((f.perIIBB*f.tipoCambio), 2), '.', ''), 14, '0'), 15, '0')
						ELSE LPAD(REPLACE(ROUND((f.perIIBB * f.tipoCambio), 2), '.', ''), 15, '0')
						AS perIIBB,
					LPAD(0, 15, '0') AS impPercepcionImpMunicipales,   
                    LPAD(0, 15, '0') AS impInternos,   
                    CASE WHEN f.moneda = 1 
                        THEN 'DOL'
                        ELSE 'PES' END AS moneda,
					LPAD(FLOOR(f.tipoCambio), 4, '0') AS tipoCambio,
					RPAD(REPLACE((f.tipoCambio - FLOOR(f.tipoCambio)), '0.', ''), 6, '0') AS tipoCambioDecimal,
                    LPAD(1, 1, '0') AS cantAlicuotasIVA,
					CASE WHEN f.iva21 = 0   
                        THEN 'N'
						ELSE ' ' END AS codigoDeOperacion,
                    LPAD(0, 15, '0') AS otrosTributos,
                    DATE_FORMAT(f.vencimiento, '%Y%m%d') AS fechaVencimiento
                    ")
                ->join('f.tipoId', 'tipo')                
                ->join('f.clienteId', 'cliente')
                ->join('cliente.iva', 'posicionIVA')
                ->where('tipo.esBalance = 0')
                ->andWhere('f.fecha BETWEEN :desde AND :hasta')
                ->setParameter('desde', $desde->format('Y-m-d'))
				->setParameter('hasta', $hasta->format('Y-m-d'))
				->getQuery()
				->getArrayResult();

		print_r($res);
		exit;

				
		return $res;

	}

	public function citiVentasAlicuota(\DateTime $desde, \DateTime $hasta)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');

		$desdeSql=$desde->format('Y-m-d');
		$hastaSql=$desde->format('Y-m-d');

		$res=$repoFacturas->createQueryBuilder('f')
                ->select("
                    LPAD(tipo.codigoAfip, 3, '0') as tipoCbteAfip,
                    LPAD(f.ptoVta, 5, '0') AS ptoVta,
					LPAD(f.fcNro, 20, '0') AS fcNro,
					CASE WHEN f.iva21 = 0 AND tipo.esNotaCredito=true
						THEN LPAD(0, 15, '0') 
						WHEN f.iva21 > 0 AND tipo.esNotaCredito=true
						THEN LPAD(
							LPAD(
								REPLACE(
									ROUND(((f.total-f.iva21-f.perIIBB)*f.tipoCambio), 2), '.', ''), 14, 0), 15, '0') 
						WHEN f.iva21 = 0 AND tipo.esNotaCredito=false
						THEN LPAD(0, 15, '0') 
						ELSE LPAD(REPLACE(ROUND(((f.total-f.iva21-f.perIIBB) * f.tipoCambio), 2), '.', ''), 15, 0) 
						END as netoGrabado,						
					CASE WHEN f.iva21=0
						THEN LPAD(3, 4, '0')
                    	ELSE LPAD(5, 4, '0') END AS alicuotaIVACodigoAfip,
					CASE WHEN tipo.esNotaCredito=true
					THEN LPAD(
						LPAD(
							REPLACE(
								ROUND((f.iva21*f.tipoCambio), 2), '.', ''), 14, '0'), 15, '0')
					ELSE LPAD(REPLACE(ROUND((f.iva21 * f.tipoCambio), 2), '.', ''), 15, '0') AS impuestoLiquidado")
                ->join('f.tipoId', 'tipo')                
                ->join('f.clienteId', 'cliente')
                ->join('cliente.iva', 'posicionIVA')
                ->where('tipo.esBalance = 0')
                ->andWhere('f.fecha BETWEEN :desde AND :hasta')
                ->setParameter('desde', $desde->format('Y-m-d'))
				->setParameter('hasta', $hasta->format('Y-m-d'))
				->getQuery()
				->getArrayResult();

		//print_r($res);
		//exit;

		return $res;

	}

	public function listaFacturasClientes($idCliente)
	{		
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
		
		try{
			$qbFacturas = $repoFacturas->createQueryBuilder('fac')
				->select('')
				->join('fac.clienteId', 'cliente')			
				->where('cliente.id = :idCliente')
				->setParameter('idCliente', $idCliente)	
				->getQuery();
			$resulFacturas = $qbFacturas->getResult();
			
			if(empty($resulFacturas)){
				return;
			}
			
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
		}	
		
		return $resulFacturas;
	}	
	
	public function listarFcCC($idCliente)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
		
		try{
			$qbFacturas = $repoFacturas->createQueryBuilder('fac')
				->select('
					fac.fecha, fac.vencimiento, fac.concepto, fac.tipo, fac.tipoCambio
					CASE fac.tipo WHEN 1 THEN item.cantidad * item.precio * tipoCambio ELSE 0 END AS debe,
					CASE fac.tipo WHEN 3 THEN item.cantidad * item.precio ELSE 0 END AS haber
				')
				->join('fac.clienteId', 'cliente')	
				->join('fac.facturaDetalleId', 'item')		
				->where('cliente.id = :idCliente')
				->setParameter('idCliente', $idCliente)	
				->getQuery();
			$resulFacturas = $qbFacturas->getResult();
			
			if(empty($resulFacturas)){
				return;
			}
			
			print_r($resulFacturas);
			
		}catch(\Exception $e){
			$this->get('logger')->error($e->getMessage());
			return false;			
		}	
	}

	public function ListarFcParaImputar($idCliente)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
		$repoTrans = $em->getRepository('MbpFinanzasBundle:TransaccionCobranzaFactura');

		try{

			$sql="
				SELECT sub.*, sub.haber - ifnull(sub.aplicado, 0) as pendiente
				FROM(SELECT 
					f.id,
					f.clienteId,
					date_format(f.fecha, '%d/%m/%Y') as fechaEmision,
					fcNro as numFc,
					ROUND((f.total*f.tipoCambio), 2) as haber,
					SUM(ifnull(tr.aplicado,0)) as aplicado,
					date_format(f.vencimiento, '%d/%m/%Y') as vencimiento    
				FROM Facturas f
				LEFT JOIN TransaccionCobranzaFactura tr ON tr.facturaId = f.id
				GROUP BY f.id) as sub
				where ((sub.haber - sub.aplicado) > 0 || isnull(sub.haber - sub.aplicado))
				AND sub.clienteId = $idCliente";
			
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll();


		}catch(\Exception $e){
			throw $e;			
		}
	}
}
