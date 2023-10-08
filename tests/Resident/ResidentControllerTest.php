<?php

namespace App\Test\Controller;

use App\Entity\Resident;
use App\Repository\ResidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResidentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ResidentRepository $repository;
    private string $path = '/resident/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Resident::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Resident index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

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
    }
}
