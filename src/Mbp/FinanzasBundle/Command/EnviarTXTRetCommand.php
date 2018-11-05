<?php
namespace Mbp\FinanzasBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mbp\FinanzasBundle\Controller\AplicativosController;

class EnviarTXTRetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('finanzas:enviarTXTRetenciones')
            ->setDescription('Envía los TXT correspondientes a las retenciones de IIBB por mail');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

		$em = $this->getContainer()->get('doctrine')->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Facturas');

		$desde=new \DateTime();
		$hasta=new \DateTime();
		$quincena;

		//PARA QUE LAS FECHAS FUNCIONEN EL COMANDO SE DEBE EJECUTAR EL 1 Y EL 16 DE CADA MES
		if(16 >= $desde->format('d') && $desde->format('d') <= 31){
			$desde->modify('first day of this month');
			$hasta->modify('first day of this month');
			$hasta->modify('+14 day');
			$quincena=2;
		}else{
			$desde->modify('-1 month');
			$hasta->modify('-1 month');
			$desde->modify('first day of this month');
			$desde->modify('+15 day');
			$hasta->modify('last day of this month');
			$quincena=1;
		}
								
		$kernel = $this->getContainer()->get('kernel');			

		//RETENCIONES
		$res=$repo->retencionesTXT($desde->format('Y-m-d'), $hasta->format('Y-m-d'));
			
		$nombreArchivo="AR"."-".$this->getContainer()->getParameter('cuit_prod')."-".$desde->format("Ym").$quincena."-".AplicativosController::$codigoRetencion."-LOTE1.txt";
		
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
		$file=fopen($basePath.$nombreArchivo, "w");
		
		foreach ($res as $linea) {				
			$str = $linea['cuit'].$linea['fecha'].$linea['ptoVta'].$linea['fcNro'].$linea['retencion'].$linea['finLinea'].PHP_EOL;	
			fwrite($file, $str);
		}

		fclose($file);

		//REPORTE
		$reporteador = $this->getContainer()->get('reporteador');
		$kernel = $this->getContainer()->get('kernel');

		$jru = $reporteador->jru();		
				
		$ruta = $kernel->locateResource('@MbpProveedoresBundle/Reportes/Retenciones.jrxml');
		$destino = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'Retenciones.pdf';		
		$param = $reporteador->getJava('java.util.HashMap');
		$rutaLogo = $reporteador->getRutaLogo($kernel);
		
		$param->put('fechaDesde', $desde->format('d-m-Y'));
		$param->put('fechaHasta', $hasta->format('d-m-Y'));

		$desdeSql=$desde->format('Y/m/d');
		$hastaSql=$hasta->format('Y/m/d');
		$conn = $reporteador->getJdbc();

		$sql = "select
				DATE_FORMAT(op.fechaEmision, '%d/%m/%Y') AS fechaEmision,
				tipo.subTipoA,
				tipo.subTipoB,
				tipo.subTipoE,
				fc.sucursal,
				fc.numFc,
				op.id,
				prov.rsocial,
				prov.cuit,
				fc.neto,
				fc.totalFc,
				LPAD((truncate((tr.aplicado * pago.importe / baseImponible), 2)), 11 ,'0') AS retencion,
				baseImponible,
				tr.aplicado,
				pago.importe
			from TransaccionOPFC tr
				left join FacturaProveedor fc on fc.id = tr.facturaId
				left join TipoComprobante tipo on tipo.id=fc.tipoId
				left join OrdenPago op on op.id = tr.ordenPagoId
				inner join Proveedor prov on prov.id = op.proveedorId
				inner join OrdenDePago_detallesPagos op_det on op_det.ordenPago_id = op.id
				inner join Pago pago on pago.id = op_det.pago_id
				left join FormasPagos fp on fp.id = pago.idFormaPago
				inner join
				(select SUM(case when f.neto > op.topeRetencionIIBB then tr.aplicado else 0 end) as baseImponible, op.id as opId
					from TransaccionOPFC as tr
					inner join FacturaProveedor f on f.id = tr.facturaId
					inner join OrdenPago op on op.id = tr.ordenPagoId
					where op.fechaEmision between '$desdeSql' and '$hastaSql'
					group by op.id) as sub on sub.opId = op.id
			where fp.retencionIIBB = true
				and op.fechaEmision between '$desdeSql' and '$hastaSql'
				and fc.neto > op.topeRetencionIIBB
				group by tr.id";
			
		$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
		

		$mensaje = \Swift_Message::newInstance()
				->setSubject("TXT Retenciones IIBB Metalurgica BP")
				->setFrom('administracion@metalurgicabp.com.ar')
				->setTo($this->getContainer()->getParameter('mail_reportes_contables'))
				->setBody("Este es un envío automático");
		$adjunto1 = \Swift_Attachment::fromPath($basePath.$nombreArchivo);
		$adjunto2 = \Swift_Attachment::fromPath($destino);
		$mensaje->attach($adjunto1);
		$mensaje->attach($adjunto2);
				
		$this->getContainer()->get('mailer')->send($mensaje);		
    }
}