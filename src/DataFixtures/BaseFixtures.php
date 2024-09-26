<?php

namespace App\DataFixtures;

use App\Factory\BaseFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BaseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $base_items = [
            ['name' => 'Porte ouverte', 'description' => 'Préférence pour garder la porte ouverte en permanence.'],
            ['name' => 'Porte fermée', 'description' => 'Préférence pour garder la porte fermée en permanence.'],
            ['name' => 'Porte ouverte en début de nuit, fermée à la fin', 'description' => 'Préférence pour avoir la porte ouverte au début de la nuit et la fermer plus tard.'],
            ['name' => 'Porte fermée en début de nuit, ouverte à la fin', 'description' => 'Préférence pour avoir la porte fermée au début de la nuit et l’ouvrir plus tard.'],
            ['name' => 'Fauteuil roulant manuel', 'description' => 'Utilisation d’un fauteuil roulant manuel.'],
            ['name' => 'Pas de fauteuil roulant', 'description' => 'Pas besoin de fauteuil roulant.'],
            ['name' => 'Ambiance sonore silencieuse', 'description' => 'Préférence pour un environnement silencieux.'],
            ['name' => 'Bruit blanc en fond sonore', 'description' => 'Préférence pour avoir un bruit blanc en fond sonore.'],
            ['name' => 'Musique douce en fond sonore', 'description' => 'Préférence pour avoir de la musique douce en fond sonore.'],
            ['name' => 'Couches taille S', 'description' => 'Utilisation de couches de taille S.'],
            ['name' => 'Couches taille M', 'description' => 'Utilisation de couches de taille M.'],
            ['name' => 'Couches taille L', 'description' => 'Utilisation de couches de taille L.'],
            ['name' => 'Couches taille XL', 'description' => 'Utilisation de couches de taille XL.'],
            ['name' => 'Régime alimentaire végétarien', 'description' => 'Suivi d’un régime alimentaire végétarien.'],
            ['name' => 'Régime alimentaire sans gluten', 'description' => 'Suivi d’un régime alimentaire sans gluten.'],
            ['name' => 'Régime alimentaire diabétique', 'description' => 'Suivi d’un régime alimentaire adapté aux diabétiques.'],
            ['name' => 'Communication verbale', 'description' => 'Préférence pour la communication verbale.'],
            ['name' => 'Communication par gestes', 'description' => 'Préférence pour la communication par gestes.'],
            ['name' => 'Communication par tablette', 'description' => 'Utilisation d’une tablette pour la communication.'],
            ['name' => 'Communication par pictogrammes', 'description' => 'Utilisation de pictogrammes pour la communication.'],
            ['name' => 'Communication en français', 'description' => 'Préférence pour la communication en français.'],
            ['name' => 'Communication en langue des signes', 'description' => 'Utilisation de la langue des signes pour communiquer.'],
        ];

        foreach ($base_items as $item) {
            BaseFactory::createOne($item);
        }

        $manager->flush();
    }
}
