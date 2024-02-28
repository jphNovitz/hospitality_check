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
use JetBrains\PhpStorm\NoReturn;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;

class BaseControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $user_repository;
    private BaseRepository $base_repository;
    private ResidentRepository $resident_repository;
    private string $path = '/resident/';
    private EntityManagerInterface $manager;
    private mixed $databaseTool;

    use InteractsWithLiveComponents;

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

    public function test_base_is_visible_in_resident_show(): void
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
        self::assertStringContainsString('Informations de base', $crawler->text());
        self::assertStringContainsString('Pas de préférences de bases', $crawler->text());

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
    public function test_edit_resident_base_redirect_if_not_referent(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);

        $resident = $this->resident_repository->find(1);

        $user_not_referent = $this->user_repository->findOneBy(['email' => "simple@exempl.es"]);
        $this->client->loginUser($user_not_referent);

        $crawler = $this->client->request('GET', sprintf('%s%s%s', $this->path, $resident->getId(), "/base"));

        self::assertResponseStatusCodeSame('301');
        self::assertResponseRedirects(sprintf('%s%s', $this->path, $resident->getId()));
    }

    #[NoReturn]
    public function test_edit_resident_base_successful_if_referent(): void
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

    #[NoReturn]
    public function test_edit_resident_can_add_base(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
            BaseTestFixtures::class
        ]);

        $resident = $this->resident_repository->find(1);

        $user_referent = $this->user_repository->findOneBy(['email' => "referent@exempl.es"]);
        $this->client->loginUser($user_referent);

        $crawler = $this->client->request('GET', sprintf('%s%s%s', $this->path, $resident->getId(), "/base"));

        self::assertEquals(0, $resident->getBases()->count());
        $form = $crawler->selectButton('Modifier')->form();
        $form['basic[bases][0]']->tick();
//        Alternative
//        $form['basic[bases][0]']->setValue('1');
        $this->client->submit($form);

        self::assertResponseRedirects(sprintf('%s%s', $this->path, $resident->getId()));
        self::assertEquals(1, $this->resident_repository->find(1)->getBases()->count());

    }

    #[NoReturn]
    public function test_edit_resident_can_remove_base(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
            BaseTestFixtures::class
        ]);

        $resident = $this->resident_repository->find(1);
        $user_referent = $this->user_repository->findOneBy(['email' => "referent@exempl.es"]);
        $this->client->loginUser($user_referent);

        $basis = $this->manager->getRepository(Base::class)->find(1);
        $resident->addBasis($basis);
        $this->manager->flush();

        self::assertEquals(1, $resident->getBases()->count());

        $crawler = $this->client->request('GET', sprintf('%s%s%s', $this->path, $resident->getId(), "/base"));
        $form = $crawler->selectButton('Modifier')->form();
        $form['basic[bases][0]']->untick();
        $this->client->submit($form);

        self::assertResponseRedirects(sprintf('%s%s', $this->path, $resident->getId()));
        self::assertEquals(0, $this->resident_repository->find(1)->getBases()->count());

    }
}
