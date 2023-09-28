<?php

namespace App\Tests\Controller\Security;

use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $router;
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $container = static::getContainer();
        $this->router = $container->get('router');
        $this->container = $container;

    }

    public function test_login_page_redirect_if_already_connected(): void
    {
        $userRepository = $this->container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@test.be');
        $this->client->loginUser($testUser);

        $this->client->request('GET', $this->router->generate('app_login'));

        $this->assertResponseRedirects($this->router->generate('app_home'));
    }

    public function test_login_form_is_accessible(): void
    {
        $this->client->request('GET', $this->router->generate('app_login'));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Please sign in');
    }
}
