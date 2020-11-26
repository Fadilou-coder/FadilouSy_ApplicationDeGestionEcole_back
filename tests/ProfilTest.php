<?php
namespace APP\ProfilTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ProfilTest extends WebTestCase
{
    protected function createAuthenticatedClient(): KernelBrowser
   {
       $client = static::createClient();
      
       $client->request(
           'POST',
           '/api/login',
            [],
            [],
           ['CONTENT_TYPE' => 'application/json'],
           '{
               "email":"abernathy.linnie@kuhic.com",
               "password":"password"
           }'
        );
       $data = \json_decode($client->getResponse()->getContent(), true);
       $client->setServerParameter('HTTP_Authorization', \sprintf('Bearer %s', $data['token']));
       $client->setServerParameter('CONTENT_TYPE', 'application/json');

       return $client;
   }

   public function testShowProfil()
   {
       $client = $this->createAuthenticatedClient();
       $client->request('GET', '/api/admin/profils');
       $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
   }

   public function testingAdd()
   {
       $client = $this->createAuthenticatedClient();
       $client->request(
           'POST',
           '/api/admin/profils',
            [],
            [],
           ['CONTENT_TYPE' => 'application/json'],
            '{
               "libelle": "nandite"
             }'
        );
        $responseContent = $client->getResponse();
         $this->assertEquals(Response::HTTP_OK,$responseContent->getStatusCode());
   }

}