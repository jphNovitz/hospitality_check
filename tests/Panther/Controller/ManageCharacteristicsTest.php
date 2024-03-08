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
use Facebook\WebDriver\WebDriverKeys;
use JetBrains\PhpStorm\NoReturn;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\PantherTestCaseTrait;

class ManageCharacteristicsTest extends PantherTestCase
{
    use PantherTestCaseTrait;

    private ?Client $client;
    private ResidentRepository $resident_repository;
    private string $path = 'http://localhost:8000/resident/';
    private EntityManagerInterface $manager;

    /** @var AbstractDatabaseTool */
    private mixed $databaseTool;


    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
//        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        passthru('pkill -f chrome');
        $this->client = static::createPantherClient(array_replace(
            static::$defaultOptions, [
            'port' => 9017
        ]));
        $this->client->start();
        //        $this->client = static::createPantherClient();
//        $this->resident_repository = static::getContainer()->get('doctrine')->getRepository(Resident::class);
//        $this->base_repository = static::getContainer()->get('doctrine')->getRepository(Base::class);
//        $this->user_repository = static::getContainer()->get('doctrine')->getRepository(User::class);
//        $this->manager = static::getContainer()->get('doctrine')->getManager();
//        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }


    #[NoReturn]
    public function test_edit_characteristics_can_add_interest(): void
    {

//        $this->databaseTool->loadFixtures([
//            UserTestFixtures::class,
//            RoomTestFixtures::class,
//            ResidentTestFixtures::class,
//            BaseTestFixtures::class
//        ]);
//die;
//        $resident = $this->resident_repository->find(1);
//dd($resident);
//        $this->client->start();
        $crawler = $this->client->request('GET', 'http://localhost:8000/login');

        $form = $crawler->selectButton('login')->form([
//            'email' => 'referent@exempl.es',
            'email' => 'user_1@test.be',
            'password' => 'password',
        ]);

        $crawler = $this->client->submit($form);

        $this->assertStringContainsString('Liste des Résidents', $crawler->text());

        $crawler = $this->client->request('GET', sprintf('%s%s%s', $this->path, '3', "/characteristic"));

        $this->client->takeScreenshot("teeeeeeest.jpg");


        $this->client->getWebDriver()->executeScript('window.scrollTo(0,document.body.scrollHeight);');
        $crawler->filter('#add')->click();

        $submit_button = $crawler->selectButton('Modifier');
        $update_form = $submit_button->form();
        $row_index = $crawler->filter('.remove')->count() - 1;

        $timestamp = time();

        $update_form["characteristics[characteristics][$row_index][name]"] = "name_".$timestamp;
        $update_form["characteristics[characteristics][$row_index][description]"] = "desciption lorem ".$timestamp;
        $update_form["characteristics[characteristics][$row_index][contentType]"] = "interest";

        $this->client->getWebDriver()->executeScript('window.scrollTo(0,document.body.scrollHeight);');
        $this->client->takeScreenshot("dump submit.jpg");
        $crawler = $this->client->submit($update_form);

//        $this->client->wait(1);

        self::assertSame(200, $this->client->getInternalResponse()->getStatusCode());
        $crawler = $this->client->request('GET', sprintf('%s%s', $this->path, '3'));
        self::assertStringContainsString($timestamp, $crawler->text());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
        $this->client->close();
    }

}
