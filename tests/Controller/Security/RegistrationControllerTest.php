<?php

namespace App\Tests\Controller\Security;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Security\EmailVerifier;
use App\Tests\SessionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationControllerTest extends WebTestCase
{

    private $router;
    private KernelBrowser $client;
    private mixed $databaseTool;
    private ContainerInterface $container;

    /**
     * @throws Exception
     */
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
     * @throws Exception
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

    public function test_new_user_is_added_in_db_when_submitted_redirect_home()
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $userRepository = $this->container->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@test.be');
        $this->client->loginUser($adminUser);

        $form = $this->client->request('GET', '/admin/register')->selectButton("Register")->form();

        $form['registration_form[email]'] = 'new_user@test.com';
        $form['registration_form[name]'] = 'Lorem Ipsum';
        $form['registration_form[plainPassword]'] = 'password';

        $this->client->submit($form);

        $this->assertResponseRedirects(
            '/admin/',
            302,
            'The user has been created');

        $testUser = $userRepository->findOneByEmail('new_user@test.com');
        $this->assertEquals('new_user@test.com', $testUser->getEmail());
    }

    public function test_new_user_is_added_in_db_when_submitted_redirect_register()
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $userRepository = $this->container->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@test.be');
        $this->client->loginUser($adminUser);

        $form = $this->client->request('GET', '/admin/register')->selectButton("Register and Add")->form();

        $form['registration_form[email]'] = 'new_user@test.com';
        $form['registration_form[name]'] = 'Lorem Ipsum';
        $form['registration_form[plainPassword]'] = 'password';

        $this->client->submit($form);

        $this->assertResponseRedirects(
            $this->router->generate('app_register'),
            302,
            'The user has been created');

        $testUser = $userRepository->findOneByEmail('new_user@test.com');
        $this->assertEquals('new_user@test.com', $testUser->getEmail());
    }

    public function test_user_can_confirm_account()
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $userRepository = $this->container->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@test.be');
        $this->client->loginUser($adminUser);

        $form = $this->client->request('GET', '/admin/register')->selectButton("Register")->form();

        $form['registration_form[email]'] = 'new_user@test.com';
        $form['registration_form[name]'] = 'Lorem Ipsum';
        $form['registration_form[plainPassword]'] = 'password';

        $this->client->submit($form);
        $test_user_id = $userRepository->findOneByEmail('new_user@test.com')->getId();

        $email = $this->getMailerMessage(0);
        $this->assertEmailHeaderSame($email, 'To', 'new_user@test.com');
        $this->assertEmailCount(1);

        $link = explode('"', $email->getHtmlBody())[1];
        $this->client->request('GET', $link);
        $this->assertResponseRedirects($this->router->generate('app_home'));
    }

    public function test_redirect_to_register_page_if_not_id_passed()
    {
        $link = 'http://localhost/verify/email';

        $this->client->request('GET', $link);
        $this->assertResponseRedirects($this->router->generate('app_register'));
    }

    public function test_redirect_to_register_if_user_not_exist()
    {
        $link = 'http://localhost/verify/email?id=999';

        $this->client->request('GET', $link);
        $this->assertResponseRedirects($this->router->generate('app_register'));
           }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
