<?php

namespace App\Tests\Controller;

use App\DataFixtures\Tests\UserTestFixtures;
use App\Entity\Resident;
use App\Entity\User;
use App\Repository\ResidentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
//use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $user_repository;
    private ResidentRepository $resident_repository;
    private string $path = '/';
    private EntityManagerInterface $manager;
    private mixed $databaseTool;


    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->resident_repository = static::getContainer()->get('doctrine')->getRepository(Resident::class);
        $this->user_repository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_homepage_is_accessible(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Bienvenue sur Roomate');
    }

    public function test_homepage_redirect_to_admin_home_if_user_is_admin(){
        $this->databaseTool->loadFixtures([UserTestFixtures::class]);

        $admin_user = $this->user_repository->find(1);
        $this->client->loginUser($admin_user);
        $this->client->request('GET', '/');

        $this->assertContains('ROLE_ADMIN', $admin_user->getRoles());
        $this->assertTrue($admin_user->isVerified());
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('/admin/');

    }

    public function test_homepage_redirect_to_resident_if_user_is_user(){
        $this->databaseTool->loadFixtures([UserTestFixtures::class]);

        $user = $this->user_repository->find(2);
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/');

        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertTrue($user->isVerified());
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('/resident/');

    }

    public function test_homepage_redirect_dont_redirect_if_user_is_not_verified()
    {
        $this->databaseTool->loadFixtures([UserTestFixtures::class]);

        $user = $this->user_repository->find(3);
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/');

        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertFalse($user->isVerified());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

    }


}
