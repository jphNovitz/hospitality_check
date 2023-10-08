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
    /*
        public function testNew(): void
        {
            $originalNumObjectsInRepository = count($this->repository->findAll());

            $this->markTestIncomplete();
            $this->client->request('GET', sprintf('%snew', $this->path));

            self::assertResponseStatusCodeSame(200);

            $this->client->submitForm('Save', [
                'resident[picture]' => 'Testing',
                'resident[firstName]' => 'Testing',
                'resident[birthDate]' => 'Testing',
                'resident[nationality]' => 'Testing',
                'resident[room]' => 'Testing',
                'resident[referent]' => 'Testing',
            ]);

            self::assertResponseRedirects('/resident/');

            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        }

        public function testShow(): void
        {
            $this->markTestIncomplete();
            $fixture = new Resident();
            $fixture->setPicture('My Title');
            $fixture->setFirstName('My Title');
            $fixture->setBirthDate('My Title');
            $fixture->setNationality('My Title');
            $fixture->setRoom('My Title');
            $fixture->setReferent('My Title');

            $this->manager->persist($fixture);
            $this->manager->flush();

            $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

            self::assertResponseStatusCodeSame(200);
            self::assertPageTitleContains('Resident');

            // Use assertions to check that the properties are properly displayed.
        }

        public function testEdit(): void
        {
            $this->markTestIncomplete();
            $fixture = new Resident();
            $fixture->setPicture('My Title');
            $fixture->setFirstName('My Title');
            $fixture->setBirthDate('My Title');
            $fixture->setNationality('My Title');
            $fixture->setRoom('My Title');
            $fixture->setReferent('My Title');

            $this->manager->persist($fixture);
            $this->manager->flush();

            $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

            $this->client->submitForm('Update', [
                'resident[picture]' => 'Something New',
                'resident[firstName]' => 'Something New',
                'resident[birthDate]' => 'Something New',
                'resident[nationality]' => 'Something New',
                'resident[room]' => 'Something New',
                'resident[referent]' => 'Something New',
            ]);

            self::assertResponseRedirects('/resident/');

            $fixture = $this->repository->findAll();

            self::assertSame('Something New', $fixture[0]->getPicture());
            self::assertSame('Something New', $fixture[0]->getFirstName());
            self::assertSame('Something New', $fixture[0]->getBirthDate());
            self::assertSame('Something New', $fixture[0]->getNationality());
            self::assertSame('Something New', $fixture[0]->getRoom());
            self::assertSame('Something New', $fixture[0]->getReferent());
        }

        public function testRemove(): void
        {
            $this->markTestIncomplete();

            $originalNumObjectsInRepository = count($this->repository->findAll());

            $fixture = new Resident();
            $fixture->setPicture('My Title');
            $fixture->setFirstName('My Title');
            $fixture->setBirthDate('My Title');
            $fixture->setNationality('My Title');
            $fixture->setRoom('My Title');
            $fixture->setReferent('My Title');

            $this->manager->persist($fixture);
            $this->manager->flush();

            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

            $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
            $this->client->submitForm('Delete');

            self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
            self::assertResponseRedirects('/resident/');
        }*/
}
