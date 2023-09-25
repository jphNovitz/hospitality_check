<?php

namespace App\Tests\Controller\Security;

use App\DataFixtures\UserFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private $router;
    private $client;
    private $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $container = static::getContainer();
        $this->router = $container->get('router');
        $this->databaseTool = $container->get(DatabaseToolCollection::class)->get();
        $this->container = $container;

    }

    public function test_Registration_Page_is_not_accessible_if_not_admin(): void
    {
        $this->client->request('GET', $this->router->generate('app_register'));
        $this->assertResponseRedirects();
    }

    /**
     * @throws \Exception
     */
    public function test_Registration_Page_is_accessible_if_user_is_admin(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $userRepository = $this->container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@test.be');
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/admin/register');
        $this->assertResponseIsSuccessful();
    }

    public function test_new_user_is_added_in_db_when_submitted()
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $userRepository = $this->container->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@test.be');
        $this->client->loginUser($adminUser);

        $form = $this->client->request('GET', '/admin/register')->selectButton("Register")->form();

//        $form['registration_form[username]'] = 'new_utilisateur';
        $form['registration_form[email]'] = 'new_user@test.com';
        $form['registration_form[plainPassword]'] = 'password';
        $form['registration_form[agreeTerms]'] = 1;
        $this->client->submit($form);

        $this->assertResponseRedirects(
            $this->router->generate('app_register'),
            302,
            'The user has been created');

        $testUser = $userRepository->findOneByEmail('new_user@test.com');
        $this->assertEquals('new_user@test.com', $testUser->getEmail());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
