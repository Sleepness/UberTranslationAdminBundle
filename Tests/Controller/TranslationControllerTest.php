<?php

namespace Sleepness\UberTranslationAdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Testing TranslationController actions
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class TranslationControllerTest extends WebTestCase
{
    /**
     * @var \Sleepness\UberTranslationBundle\Cache\UberMemcached;
     */
    private $uberMemcached;

    private $client;

    /**
     * Test indexAction() of TranslationsController
     */
    public function testIndexAction()
    {
        $crawler = $this->client->request('GET', '/translations');
        $this->assertEquals(1, $crawler->filter('html:contains("logo_text")')->count());
        $this->assertEquals(5, $crawler->filter('th')->count());
        $this->responseAsserts($this->client->getResponse());
    }

    /**
     * Test editAction() of TranslationsController
     */
    public function testEditAction()
    {
        $crawler = $this->client->request('POST', '/translation/edit/en_US/messages/test.key');
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('div.modal')->count());
        $this->responseAsserts($this->client->getResponse());
    }

    /**
     * Test deleteAction() of TranslationsController
     */
    public function testDeleteAction()
    {
        $this->client->request('GET', '/translation/delete/en_US/messages/test.key');
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    /**
     * Test createAction() of TranslationsController
     */
    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', '/translation/create');
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(4, $crawler->filter('input')->count());
        $this->responseAsserts($this->client->getResponse());
    }

    /**
     * Common asserts for response
     *
     * @param $response
     */
    private function responseAsserts($response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'text/html; charset=UTF-8'
            )
        );
    }

    /**
     * Set up fixtures for testing
     */
    public function setUp()
    {
        static::bootKernel(array());
        $container = static::$kernel->getContainer();
        $this->client = static::createClient();
        $this->uberMemcached = $container->get('uber.memcached');
        $this->uberMemcached->addItem('en_US', array('messages' => array('test.key' => 'test value')));
    }

    /**
     * Tear down fixtures after testing
     */
    public function tearDown()
    {
        $this->uberMemcached->deleteItem('en_US');
    }
}
