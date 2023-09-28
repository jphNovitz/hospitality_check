<?php

namespace App\Test\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    private KernelBrowser $client;
//    private UserRepository $repository;
    private string $path = '/profile';
    private mixed $databaseTool;
    private ContainerInterface $container;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->container = static::getContainer();
    }

    public function test_show_is_not_accessible_if_not_logged_in(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $this->client->request('GET', sprintf('%s', $this->path));

        self::assertResponseStatusCodeSame(302);
        self::assertResponseRedirects('/login');

    }

    public function testShow(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $users = $this->container->get(UserRepository::class)->findAll();

        $this->client->loginUser($users[1]);
        $response = $this->client->request('GET', sprintf('%s', $this->path));
        $content = $response->filter('html')->text();
        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString($users[1]->getName(), $content);

        $this->client->loginUser($users[2]);
        $response = $this->client->request('GET', sprintf('%s', $this->path));
        $content = $response->filter('html')->text();
        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString($users[2]->getName(), $content);

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $users = $this->container->get(UserRepository::class)->findAll();

        $this->client->loginUser($users[1]);
        $this->client->request('GET', sprintf('%s/edit', $this->path));

        $this->client->submitForm('Update', [
            "user[email]" => "lipsum@lorem.test",
            "user[name]" => "Marcel",
        ]);

        self::assertResponseRedirects('/profile');

        $user = $this->container->get(UserRepository::class)->findOneByEmail("lipsum@lorem.test");

        self::assertSame("lipsum@lorem.test", $user->getEmail());
        self::assertSame("Marcel", $user->getName());
    }


}
