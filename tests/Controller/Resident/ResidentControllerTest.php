<?php

namespace App\Tests\Controller\Resident;

use App\Entity\Resident;
use App\Entity\Room;
use App\Entity\User;
use App\Factory\Test\ResidentFactory;
use App\Factory\Test\RoomFactory;
use App\Factory\Test\UserFactory;
use App\Factory\BaseFactory;
use App\Repository\UserRepository;
use App\Repository\ResidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ResidentControllerTest extends WebTestCase
{
    use ResetDatabase, Factories;

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
    }

    public function test_resident_index_redirect_to_login_if_not_logged(): void
    {
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(302);
        self::assertResponseRedirects('/login');
    }

    
    public function test_resident_index(): void
    {
        $user = UserFactory::createOne();
        $residentFactory = ResidentFactory::createOne([
            'referent' => $user,
        ]);
        $user = $this->user_repository->findAll()[0];
        $residents = $this->resident_repository->findAll();

        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString($residents[0]->getFirstName(), $crawler->text());

    }

    public function test_new_resident(): void
    {
        $user = UserFactory::createOne();
        $room = RoomFactory::createOne();

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
        self::assertResponseRedirects(sprintf('%s%s', $this->path, 'fake-first'));
        self::assertSame(1, $this->resident_repository->count([]));
        self::assertNotNull($this->resident_repository->find(1)->getPicture());
    }

    public function test_new_room_is_persisted_by_event(): void
    {
        $user = UserFactory::createOne();

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

        self::assertResponseRedirects(sprintf('%s%s', $this->path, 'fake-first'));
        self::assertSame(1, $this->manager->getRepository(Room::class)->count([]));
    }

    public function test_member_can_see_resident_profile(): void
    {
        $user = UserFactory::createOne();
        $room = RoomFactory::createOne();
        $resident = ResidentFactory::createOne([
            'referent' => $user,
            'room' => $room,
        ]);

        $resident = $this->resident_repository->find(1);
        $user = $this->user_repository->find(1);

        $this->client->loginUser($user); // login as referent

        $crawler = $this->client->request('GET', sprintf('%s%s', $this->path, $resident->getSlug()));
        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('A propos de');
        self::assertPageTitleContains($resident->getFirstName());
        self::assertStringContainsString($resident->getFirstName(), $crawler->text());
        self::assertStringContainsString($resident->getNationality(), $crawler->text());
    }

    public function test_random_member_can_not_edit_resident(): void
    {
        $this->client->disableReboot();

        $referent = UserFactory::createOne();
        $random_member = UserFactory::createOne();
        $room = RoomFactory::createOne();
        $resident = ResidentFactory::createOne([
            'referent' => $referent,
            'room' => $room,
        ]);

        $user = $this->user_repository->find(2);
        $resident = $this->resident_repository->find(1);

        $this->client->loginUser($user);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $resident->getSlug()));

        $this->assertResponseStatusCodeSame(403);

    }

    public function test_edit_resident_page_is_accessible_referent(): void
    {
        $referent = UserFactory::createOne();
        $room = RoomFactory::createOne();
        $resident = ResidentFactory::createOne([
            'referent' => $referent,
            'room' => $room,
        ]);

        $user = $this->user_repository->find(1);
        $resident = $this->resident_repository->find(1);

        $this->client->loginUser($user);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $resident->getSlug()));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains("title", "Modification d'un résident");
        $this->assertSelectorTextContains("title", $resident->getFirstName());
        $this->assertSelectorTextContains("h2", "Modification d'un résident");
    }

    public function test_referent_can_modify_resident_infos(): void
    {
        $referent = UserFactory::createOne();
        $room = RoomFactory::createMany(2);
        $resident = ResidentFactory::createOne([
            'referent' => $referent,
            'room' => $room[0],
        ]);

        $user = $this->user_repository->find(1);
        $resident = $this->resident_repository->find(1);

        $this->client->loginUser($user);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $resident->getSlug()));

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
            'resident[room]' => "2",
            'resident[nationality]' => 'French',
            'resident[referent]' => $user->getId(),
        ]);

        $resident = $this->resident_repository->find(1);

        $this->assertSame('New FirstName Lipsum', $resident->getFirstName());
        $this->assertSame('French', $resident->getNationality());
        $this->assertSame(2, $resident->getRoom()->getId());
        $this->assertNotNull($resident->getPicture());
    }
    
    public function test_referent_can_modify_resident_basics(): void
    {
        $base = BaseFactory::createOne();
        $referent = UserFactory::createOne();
        $room = RoomFactory::createMany(2);
        $resident = ResidentFactory::createOne([
            'referent' => $referent,
            'room' => $room[0],
        ]);

        $user = $this->user_repository->find(1);
        $resident = $this->resident_repository->find(1);
        
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', sprintf('%s%s/base', $this->path, $resident->getSlug()));
        $form = $crawler->selectButton('Modifier')->form();
        $form['basic[bases][0]']->tick();

        $this->client->submit($form);

        // $this->client->followRedirect();
        $this->assertResponseRedirects(sprintf('%s%s', $this->path, $resident->getSlug()));

        $resident = $this->resident_repository->find(1);

        $this->assertSame(1, $resident->getBases()->count());
    }
    
}
