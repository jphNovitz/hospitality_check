<?php

namespace App\Test\Controller;

use App\DataFixtures\Tests\ResidentTestFixtures;
use App\DataFixtures\Tests\RoomTestFixtures;
use App\DataFixtures\Tests\TestFixtures;
use App\DataFixtures\Tests\UserTestFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Resident;
use App\Entity\User;
use App\Repository\ResidentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResidentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $user_repository;
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
        $this->user_repository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testIndex_redirect_to_login_if_not_logged(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(302);
        self::assertResponseRedirects('/login');
    }

    public function testIndex(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);
        $user = $this->user_repository->findAll()[0];
        $residents = $this->resident_repository->findAll();

        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString($residents[0]->getFirstName(), $crawler->text());

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {

        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);
        $originalNumObjectsInRepository = count($this->resident_repository->findAll());

        $user = $this->user_repository->find(1);
        $this->client->loginUser($user);
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'resident[picture]' => 'Testing',
            'resident[firstName]' => 'Fake First',
            'resident[birthDate]' => [
                'year' => 2018,
                'month' => 12,
                'day' => 1,
            ],
            'resident[nationality]' => 'Beligian',
            'resident[room]' => 1,
            'resident[referent]' => $user->getId(),
        ]);

        self::assertResponseRedirects('/resident/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->resident_repository->findAll()));
    }

    public function testShow(): void
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

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
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

        $this->client->submitForm('Update', [
            'resident[picture]' => 'new_picture',
            'resident[firstName]' => 'New FirstName Lipsum',
            'resident[birthDate]' => [
                'year' => 2018,
                'month' => 12,
                'day' => 14,
            ],
            'resident[nationality]' => 'French',
            'resident[room]' => 2,
            'resident[referent]' => $other_user->getId(),
        ]);

        self::assertResponseRedirects('/resident/');

        $resident = $this->resident_repository->find(1);

        self::assertSame('new_picture', $resident->getPicture());
        self::assertSame('New FirstName Lipsum', $resident->getFirstName());
        self::assertSame('French', $resident->getNationality());
        self::assertSame(2, $resident->getRoom()->getId());
        self::assertSame($other_user->getId(), $resident->getReferent()->getId());
        self::assertSame($other_user->getName(), $resident->getReferent()->getName());
    }

    public function testRemove(): void
    {
        $this->databaseTool->loadFixtures([
            UserTestFixtures::class,
            RoomTestFixtures::class,
            ResidentTestFixtures::class,
        ]);
        $originalNumObjectsInRepository = count($this->resident_repository->findAll());

        $user = $this->user_repository->find(1);
        $this->client->loginUser($user);
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'resident[picture]' => 'Testing',
            'resident[firstName]' => 'Fake First',
            'resident[birthDate]' => [
                'year' => 2018,
                'month' => 12,
                'day' => 1,
            ],
            'resident[nationality]' => 'Beligian',
            'resident[room]' => 1,
            'resident[referent]' => $user->getId(),
        ]);
        $all_residents = $this->resident_repository->findAll();
        self::assertSame($originalNumObjectsInRepository + 1, count($all_residents));

        $this->client->request('GET', sprintf('%s%s', $this->path, end($all_residents)->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->resident_repository->findAll()));
        self::assertResponseRedirects('/resident/');
    }
}
