<?php

namespace App\Tests\Controller\Admin;

use App\DataFixtures\Tests\ResidentTestFixtures;
use App\DataFixtures\Tests\RoomTestFixtures;
use App\DataFixtures\Tests\UserTestFixtures;
use App\Entity\Resident;
use App\Entity\User;
use App\Repository\ResidentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $user_repository;
    private string $path = '/admin/';
    private EntityManagerInterface $manager;
    private mixed $databaseTool;


    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->user_repository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_home_admin_is_not_accessible_if_not_admin(): void
    {
        $this->databaseTool->loadFixtures([UserTestFixtures::class]);
        $user = $this->user_repository->find(2);
        $this->client->loginUser($user);

        $this->client->request('GET', $this->path);
        self::assertResponseStatusCodeSame(403);
        self::assertTrue($user->getRoles() === ['ROLE_USER']);
    }

    public function test_home_admin_is_not_accessible_if__admin(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
        ]);
        $user = $this->user_repository->find(1);
        $this->client->loginUser($user);

        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertResponseIsSuccessful();
        self::assertContains('ROLE_ADMIN', $user->getRoles());
    }
}
