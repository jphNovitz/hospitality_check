<?php

namespace App\Factory\Test;

use App\Entity\Resident;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Resident>
 */
final class ResidentFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function class(): string
    {
        return Resident::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'firstName' => self::faker()->firstName(),
            'birthDate' => self::faker()->dateTimeThisCentury(),
            'nationality' => self::faker()->country(),
            'room' => RoomFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this// ->afterInstantiate(function(Resident $resident): void {})
            ;
    }
}
