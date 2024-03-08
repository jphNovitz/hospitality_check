<?php

namespace App\Tests\Controller\Resident;

use App\DataFixtures\Tests\BaseTestFixtures;
use App\DataFixtures\Tests\ResidentTestFixtures;
use App\DataFixtures\Tests\RoomTestFixtures;
use App\DataFixtures\Tests\UserTestFixtures;
use App\Entity\Base;
use App\Entity\Resident;
use App\Entity\User;
use App\Repository\BaseRepository;
use App\Repository\ResidentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\WebDriver\Chrome\ChromeDriver;
use JetBrains\PhpStorm\NoReturn;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\PantherTestCaseTrait;

class CharacteristicControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $user_repository;
    private BaseRepository $base_repository;
    private ResidentRepository $resident_repository;
    private string $path = '/resident/';
    private EntityManagerInterface $manager;
    private mixed $databaseTool;


    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->resident_repository = static::getContainer()->get('doctrine')->getRepository(Resident::class);
        $this->base_repository = static::getContainer()->get('doctrine')->getRepository(Base::class);
        $this->user_repository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_characteristics_block_is_visible_in_resident_show(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);


        $resident = $this->resident_repository->find(1);
        $user = $this->user_repository->find(1);
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', sprintf('%s%s', $this->path, $resident->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString("Je n'ai aucun centre d'intérêt", $crawler->text());
        self::assertStringContainsString("RAS concernant ce que j'accepte", $crawler->text());
        self::assertStringContainsString("Avec moi, il n'y a rien de particulier à éviter", $crawler->text());

    }

    public function test_show_resident(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);

        $resident = $this->resident_repository->find(1);
        $user = $this->user_repository->find(1);
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', sprintf('%s%s', $this->path, $resident->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Resident');
        self::assertStringContainsString($resident->getFirstName(), $crawler->text());
        self::assertStringContainsString($resident->getNationality(), $crawler->text());
    }

    #[NoReturn]
    public function test_edit_resident_characteristics_redirect_if_not_referent(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);

        $resident = $this->resident_repository->find(1);

        $user_not_referent = $this->user_repository->findOneBy(['email' => "simple@exempl.es"]);
        $this->client->loginUser($user_not_referent);

        $crawler = $this->client->request('GET', sprintf('%s%s%s', $this->path, $resident->getId(), "/characteristic"));

        self::assertResponseStatusCodeSame('301');
        self::assertResponseRedirects(sprintf('%s%s', $this->path, $resident->getId()));
    }

    #[NoReturn]
    public function test_edit_resident_characteristics_accessible_if_referent(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);

        $resident = $this->resident_repository->find(1);

        $user_referent = $this->user_repository->findOneBy(['email' => "referent@exempl.es"]);
        $this->client->loginUser($user_referent);

        $this->client->request('GET', sprintf('%s%s%s', $this->path, $resident->getId(), "/base"));

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame('200');
    }

}
