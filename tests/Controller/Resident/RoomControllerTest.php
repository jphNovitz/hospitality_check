<?php

namespace App\Tests\Controller\Resident;

use App\DataFixtures\Tests\ResidentTestFixtures;
use App\DataFixtures\Tests\RoomTestFixtures;
use App\DataFixtures\Tests\UserTestFixtures;
use App\Entity\Resident;
use App\Entity\Room;
use App\Entity\User;
use App\Repository\ResidentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;

class RoomControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $user_repository;
    private ResidentRepository $resident_repository;
    private string $path = '/room';
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
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_room_index_redirect_to_login_if_not_logged(): void
    {
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(302);
        self::assertResponseRedirects('/login');
    }

    #[NoReturn]
    public function test_room_index(): void
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

}
