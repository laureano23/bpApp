<?php
namespace Mbp\FinanzasBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnviarCITICommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('finanzas:enviarCITI')
            ->setDescription('Envía los TXT correspondientes a los CITI Venta por mail');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

		$em = $this->getContainer()->get('doctrine')->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Facturas');

	
		$desde=new \DateTime();
		$desde=$desde->modify('-1 month');
		$hasta=new \DateTime();
		$hasta=$hasta->modify('-1 month');
		$desde = $desde->modify('first day of this month');
		$hasta = $hasta->modify('last day of this month');
								
		$kernel = $this->getContainer()->get('kernel');			

		$res = $repo->citiVentasCbtes($desde, $hasta);
		$res2 = $repo->citiVentasAlicuota($desde, $hasta);
		
		$nombreArchivo="CITI-VENTAS-CBTE.txt";
		$nombreArchivo2="CITI-VENTAS-ALICUOTA.txt";
		
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
		$file=fopen($basePath.$nombreArchivo, "w");
		$file2=fopen($basePath.$nombreArchivo2, "w");
			
		foreach ($res as $linea) {		
			$str = $linea['fechaEmision'].
				$linea['tipoCbteAfip'].
				$linea['ptoVta'].
				$linea['fcNroDesde'].
				$linea['fcNroHasta'].
				$linea['codDocumento'].
				$linea['numIdentificacion'].
				$linea['nombreComprador'].
				$linea['montoTotal'].
				$linea['montoNoGrabado'].
				$linea['percepcionNoCategorizados'].
				$linea['montoExcento'].
				$linea['pagoCuentaImpNacionales'].
				$linea['perIIBB'].
				$linea['impPercepcionImpMunicipales'].
				$linea['impInternos'].
				$linea['moneda'].
				$linea['tipoCambio'].
				$linea['tipoCambioDecimal'].
				$linea['cantAlicuotasIVA'].
				$linea['codigoDeOperacion'].
				$linea['otrosTributos'].
				$linea['fechaVencimiento'].
				"\r\n";	//fin de linea para aplicativos bajo SO Windows
			fwrite($file, $str);
		}
		fclose($file);

		foreach ($res2 as $linea) {				
			$str = $linea['tipoCbteAfip'].
				$linea['ptoVta'].
				$linea['fcNro'].
				$linea['netoGrabado'].
				$linea['alicuotaIVACodigoAfip'].
				$linea['impuestoLiquidado'].
				"\r\n";	//fin de linea para aplicativos bajo SO Windows
			fwrite($file2, $str);
		}
		fclose($file2);


		$mensaje = \Swift_Message::newInstance()
				->setSubject("CITI Ventas Metalurgica BP")
				->setFrom('administracion@metalurgicabp.com.ar')
				->setTo('lucilamariani@estudioplataroti.com.ar')
				->setBody("Este es un envío automático");
		$adjunto1 = \Swift_Attachment::fromPath($basePath.$nombreArchivo);
		$adjunto2 = \Swift_Attachment::fromPath($basePath.$nombreArchivo2);
		$mensaje->attach($adjunto1);
		$mensaje->attach($adjunto2);
				
		$this->getContainer()->get('mailer')->send($mensaje);	
	
    }
}