<?php

namespace App\Tests\Controller\Resident;

use App\DataFixtures\Tests\ResidentTestFixtures;
use App\DataFixtures\Tests\RoomTestFixtures;
use App\DataFixtures\Tests\TestFixtures;
use App\DataFixtures\Tests\UserTestFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Resident;
use App\Entity\Room;
use App\Entity\User;
use App\Repository\ResidentRepository;
use App\Repository\UserRepository;
use App\Twig\Components\Live\Resident\Form\Profile;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;

class ResidentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $user_repository;
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
        $this->user_repository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_resident_index_redirect_to_login_if_not_logged(): void
    {
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(302);
        self::assertResponseRedirects('/login');
    }

    #[NoReturn]
    public function test_resident_index(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);
        $user = $this->user_repository->findAll()[1];
        $residents = $this->resident_repository->findAll();

        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString($residents[0]->getFirstName(), $crawler->text());

    }

    public function test_new_resident(): void
    {

        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
        ]);

        $user = $this->user_repository->find(1);
        $this->client->loginUser($user);
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);
        $imagePath = __DIR__ . '/../../fixtures/image_test.jpeg';
        $imageFile = new UploadedFile(
            $imagePath,
            'image_test.jpeg',
            'image/jpeg',
            null,
            true
        );

        $this->client->submitForm('Enregistrer', [
            'resident[imageFile][file]' => $imageFile,
            'resident[firstName]' => 'Fake First',
            'resident[birthDate]' => [
                'year' => 2018,
                'month' => 12,
                'day' => 1,
            ],
            'resident[nationality]' => 'Belgian',
            'resident[room]' => 1,
            'resident[referent]' => $user->getId(),
        ]);
        self::assertResponseRedirects(sprintf('%s%s', $this->path, 1));
        self::assertSame(1, $this->resident_repository->count([]));
    }

    public function test_new_room_is_persisted_by_event(): void
    {

        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
        ]);

        $user = $this->user_repository->find(1);
        $this->client->loginUser($user);

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $imagePath = __DIR__ . '/../../fixtures/image_test.jpeg';
        $imageFile = new UploadedFile(
            $imagePath,
            'image_test.jpeg',
            'image/jpeg',
            null,
            true
        );

        $this->client->submitForm('Enregistrer', [
            'resident[imageFile][file]' => $imageFile,
            'resident[firstName]' => 'Fake First',
            'resident[birthDate]' => [
                'year' => 2018,
                'month' => 12,
                'day' => 1,
            ],
            'resident[nationality]' => 'Belgian',
            'resident[room]' => "",
            'resident[newRoom]' => "1",
            'resident[referent]' => $user->getId(),
        ]);

        self::assertResponseRedirects(sprintf('%s%s', $this->path, 1));
        self::assertSame(1, $this->manager->getRepository(Room::class)->count([]));
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
    public function test_edit_resident(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);


        $resident = $this->resident_repository->find(1);
        $user = $this->user_repository->find(1);
        $other_user = $this->user_repository->find(2);
        $this->client->loginUser($user);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $resident->getId()));

        $imagePath = __DIR__ . '/../../fixtures/image_test.jpeg';
        $imageFile = new UploadedFile(
            $imagePath,
            'image_test.jpeg',
            'image/jpeg',
            null,
            true
        );

        $crawler = $this->client->submitForm('Modifier', [
            'resident[imageFile][file]' => $imageFile,
            'resident[firstName]' => 'New FirstName Lipsum',
            'resident[birthDate]' => [
                'year' => 2018,
                'month' => 12,
                'day' => 1,
            ],
            'resident[room]' => "3",
            'resident[nationality]' => 'French',
            'resident[referent]' => $user->getId(),
        ]);

        $resident = $this->resident_repository->find(1);

        self::assertSame('New FirstName Lipsum', $resident->getFirstName());
        self::assertSame('French', $resident->getNationality());
        self::assertSame(3, $resident->getRoom()->getId());
    }
}
