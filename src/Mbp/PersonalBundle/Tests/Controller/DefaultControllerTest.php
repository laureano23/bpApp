<?php

namespace Mbp\PersonalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultControllerTest extends WebTestCase
{
	/**
     * @Route("/test1", name="mbp_personal_test1", options={"expose"=true})
     */   
    public function test1()
    {
        $client = static::createClient();
		$client->followRedirects(true);
		$crawler = $client->request('GET', '/personal/ValidacionFichadas');
		
		
		$this->assertEquals(200, $client->getResponse()->getStatusCode(),
		'El status es 200'
		);
    }
}
