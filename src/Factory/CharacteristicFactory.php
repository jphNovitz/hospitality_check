<?php

namespace App\Factory;

use App\Repository\UserRepository;
use App\Entity\Characteristic;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Characteristic>
 */
final class CharacteristicFactory extends PersistentProxyObjectFactory
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
        return Characteristic::class;
    }


    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $characteristic_items = [
            // Interest
            ['contentType' => 'interest', 'name' => 'Musique', 'description' => 'Aime écouter et jouer de la musique'],
            ['contentType' => 'interest', 'name' => 'Lecture', 'description' => 'Apprécie les livres audio et en braille'],
            ['contentType' => 'interest', 'name' => 'Jardinage adapté', 'description' => 'Passion pour le jardinage surélevé'],
            ['contentType' => 'interest', 'name' => 'Sports adaptés', 'description' => 'Enthousiaste de basketball en fauteuil roulant'],
            ['contentType' => 'interest', 'name' => 'Art thérapie', 'description' => 'Trouve du réconfort dans la peinture et le dessin'],
            ['contentType' => 'interest', 'name' => 'Technologie assistive', 'description' => 'Passionné par les dernières innovations en technologie d\'assistance'],
            ['contentType' => 'interest', 'name' => 'Cuisine adaptée', 'description' => 'Aime expérimenter avec des ustensiles de cuisine adaptés'],
            ['contentType' => 'interest', 'name' => 'Photographie', 'description' => 'Capture le monde à travers un objectif unique'],
            ['contentType' => 'interest', 'name' => 'Natation', 'description' => 'Trouve la liberté dans l\'eau'],
            ['contentType' => 'interest', 'name' => 'Méditation', 'description' => 'Pratique quotidienne pour la paix intérieure'],
            ['contentType' => 'interest', 'name' => 'Jeux de société adaptés', 'description' => 'Apprécie les jeux de stratégie modifiés'],
            ['contentType' => 'interest', 'name' => 'Écriture créative', 'description' => 'Exprime ses pensées à travers la poésie et les nouvelles'],
            ['contentType' => 'interest', 'name' => 'Danse adaptée', 'description' => 'Passion pour l\'expression corporelle en mouvement'],
            ['contentType' => 'interest', 'name' => 'Astronomie', 'description' => 'Fasciné par l\'observation des étoiles'],
            ['contentType' => 'interest', 'name' => 'Langues des signes', 'description' => 'Apprend différentes langues des signes'],
            ['contentType' => 'interest', 'name' => 'Yoga adapté', 'description' => 'Pratique le yoga pour améliorer la flexibilité et la force'],
            ['contentType' => 'interest', 'name' => 'Cinéma', 'description' => 'Amateur de films avec audio-description'],
            ['contentType' => 'interest', 'name' => 'Podcasts', 'description' => 'Écoute une variété de podcasts éducatifs'],
            ['contentType' => 'interest', 'name' => 'Sculpture', 'description' => 'Crée des œuvres tactiles en argile'],
            ['contentType' => 'interest', 'name' => 'Histoire locale', 'description' => 'Passionné par l\'histoire de sa communauté'],
            ['contentType' => 'interest', 'name' => 'Ornithologie', 'description' => 'Observe et écoute les oiseaux dans la nature'],
            ['contentType' => 'interest', 'name' => 'Théâtre audio', 'description' => 'Participe à des productions de théâtre audio'],
            ['contentType' => 'interest', 'name' => 'Échecs adaptés', 'description' => 'Joue aux échecs avec des pièces modifiées'],
            ['contentType' => 'interest', 'name' => 'Jardinage sensoriel', 'description' => 'Cultive un jardin axé sur les textures et les parfums'],
            ['contentType' => 'interest', 'name' => 'Musées tactiles', 'description' => 'Visite des expositions conçues pour l\'exploration tactile'],

            // Do
            ['contentType' => 'do', 'name' => 'Communiquer clairement', 'description' => 'Parler lentement et distinctement'],
            ['contentType' => 'do', 'name' => 'Offrir de l\'aide', 'description' => 'Proposer de l\'assistance, mais attendre l\'approbation'],
            ['contentType' => 'do', 'name' => 'Respecter l\'espace personnel', 'description' => 'Maintenir une distance appropriée'],
            ['contentType' => 'do', 'name' => 'Être patient', 'description' => 'Accorder du temps supplémentaire pour les réponses ou les actions'],
            ['contentType' => 'do', 'name' => 'Utiliser un langage respectueux', 'description' => 'Employer des termes appropriés et non discriminatoires'],
            ['contentType' => 'do', 'name' => 'Écouter attentivement', 'description' => 'Prêter une oreille attentive aux besoins exprimés'],
            ['contentType' => 'do', 'name' => 'Adapter l\'environnement', 'description' => 'Rendre l\'espace accessible et confortable'],
            ['contentType' => 'do', 'name' => 'Encourager l\'indépendance', 'description' => 'Soutenir les efforts d\'autonomie'],
            ['contentType' => 'do', 'name' => 'Fournir des informations accessibles', 'description' => 'Offrir des documents en formats alternatifs'],
            ['contentType' => 'do', 'name' => 'Inclure dans les activités', 'description' => 'Inviter à participer à des événements sociaux'],
            ['contentType' => 'do', 'name' => 'Respecter les choix', 'description' => 'Honorer les décisions personnelles'],
            ['contentType' => 'do', 'name' => 'Utiliser des aides visuelles', 'description' => 'Employer des images ou des gestes pour clarifier la communication'],
            ['contentType' => 'do', 'name' => 'Offrir des pauses', 'description' => 'Permettre des moments de repos pendant les activités'],
            ['contentType' => 'do', 'name' => 'Maintenir le contact visuel', 'description' => 'Établir une connexion visuelle lors des conversations'],
            ['contentType' => 'do', 'name' => 'Fournir des instructions claires', 'description' => 'Donner des directives étape par étape'],
            ['contentType' => 'do', 'name' => 'Respecter la confidentialité', 'description' => 'Maintenir la discrétion sur les informations personnelles'],
            ['contentType' => 'do', 'name' => 'Offrir des options', 'description' => 'Présenter des choix pour favoriser l\'autonomie'],
            ['contentType' => 'do', 'name' => 'Utiliser la technologie assistive', 'description' => 'Intégrer des outils d\'aide appropriés'],
            ['contentType' => 'do', 'name' => 'Créer un environnement calme', 'description' => 'Réduire les stimuli sensoriels si nécessaire'],
            ['contentType' => 'do', 'name' => 'Encourager l\'expression personnelle', 'description' => 'Valoriser les opinions et les idées'],
            ['contentType' => 'do', 'name' => 'Adapter le rythme', 'description' => 'Ajuster la vitesse des activités selon les besoins'],
            ['contentType' => 'do', 'name' => 'Offrir un soutien émotionnel', 'description' => 'Être à l\'écoute et offrir du réconfort'],
            ['contentType' => 'do', 'name' => 'Faciliter la mobilité', 'description' => 'S\'assurer que les chemins sont dégagés et accessibles'],
            ['contentType' => 'do', 'name' => 'Promouvoir l\'inclusion', 'description' => 'Encourager la participation dans tous les aspects de la vie'],
            ['contentType' => 'do', 'name' => 'Respecter les routines', 'description' => 'Maintenir la cohérence dans les activités quotidiennes'],

            // Dont
            ['contentType' => 'dont', 'name' => 'Ne pas supposer l\'incapacité', 'description' => 'Éviter de présumer des limitations'],
            ['contentType' => 'dont', 'name' => 'Ne pas parler à la place', 'description' => 'Laisser la personne s\'exprimer par elle-même'],
            ['contentType' => 'dont', 'name' => 'Ne pas toucher sans permission', 'description' => 'Respecter l\'espace personnel et l\'autonomie'],
            ['contentType' => 'dont', 'name' => 'Ne pas crier', 'description' => 'Parler normalement, sauf indication contraire'],
            ['contentType' => 'dont', 'name' => 'Ne pas utiliser un langage infantilisant', 'description' => 'Communiquer de manière adulte et respectueuse'],
            ['contentType' => 'dont', 'name' => 'Ne pas fixer du regard', 'description' => 'Éviter de dévisager ou de fixer les aides techniques'],
            ['contentType' => 'dont', 'name' => 'Ne pas ignorer', 'description' => 'Inclure la personne dans les conversations et activités'],
            ['contentType' => 'dont', 'name' => 'Ne pas surprotéger', 'description' => 'Permettre la prise de risques raisonnables'],
            ['contentType' => 'dont', 'name' => 'Ne pas faire de commentaires sur le handicap', 'description' => 'Éviter les remarques non sollicitées sur la condition'],
            ['contentType' => 'dont', 'name' => 'Ne pas imposer d\'aide', 'description' => 'Attendre que l\'aide soit demandée ou acceptée'],
            ['contentType' => 'dont', 'name' => 'Ne pas utiliser de termes offensants', 'description' => 'Éviter tout langage discriminatoire ou péjoratif'],
            ['contentType' => 'dont', 'name' => 'Ne pas généraliser', 'description' => 'Reconnaître l\'individualité de chaque personne'],
            ['contentType' => 'dont', 'name' => 'Ne pas interrompre', 'description' => 'Laisser le temps nécessaire pour s\'exprimer'],
            ['contentType' => 'dont', 'name' => 'Ne pas faire de bruit excessif', 'description' => 'Être conscient des sensibilités auditives'],
            ['contentType' => 'dont', 'name' => 'Ne pas envahir l\'espace personnel', 'description' => 'Respecter les limites physiques'],
            ['contentType' => 'dont', 'name' => 'Ne pas sous-estimer', 'description' => 'Reconnaître les capacités et le potentiel'],
            ['contentType' => 'dont', 'name' => 'Ne pas utiliser le handicap comme définition', 'description' => 'Voir la personne au-delà de sa condition'],
            ['contentType' => 'dont', 'name' => 'Ne pas faire de promesses irréalistes', 'description' => 'Être honnête sur ce qui est possible'],
            ['contentType' => 'dont', 'name' => 'Ne pas comparer', 'description' => 'Éviter les comparaisons avec d\'autres personnes'],
            ['contentType' => 'dont', 'name' => 'Ne pas forcer la participation', 'description' => 'Respecter le choix de ne pas participer'],
            ['contentType' => 'dont', 'name' => 'Ne pas ignorer les signaux de fatigue', 'description' => 'Être attentif aux signes de fatigue ou d\'inconfort'],
            ['contentType' => 'dont', 'name' => 'Ne pas présumer des préférences', 'description' => 'Demander plutôt que de supposer les goûts'],
            ['contentType' => 'dont', 'name' => 'Ne pas utiliser de parfums forts', 'description' => 'Être conscient des sensibilités olfactives'],
            ['contentType' => 'dont', 'name' => 'Ne pas négliger l\'accessibilité', 'description' => 'S\'assurer que les espaces sont accessibles à tous'],
            ['contentType' => 'dont', 'name' => 'Ne pas parler uniquement à l\'accompagnateur', 'description' => 'S\'adresser directement à la personne'],

            // Other
            ['contentType' => 'other', 'name' => 'Préférence pour la routine', 'description' => 'Apprécie un emploi du temps structuré'],
            ['contentType' => 'other', 'name' => 'Sensibilité au bruit', 'description' => 'Peut être perturbé par des sons forts ou soudains'],
            ['contentType' => 'other', 'name' => 'Communication non verbale', 'description' => 'Utilise des gestes ou des expressions faciales pour communiquer'],
            ['contentType' => 'other', 'name' => 'Besoin de pauses fréquentes', 'description' => 'Nécessite des moments de repos réguliers'],
            ['contentType' => 'other', 'name' => 'Préférence pour la lumière tamisée', 'description' => 'Se sent plus à l\'aise dans un environnement moins lumineux'],
            ['contentType' => 'other', 'name' => 'Utilisation d\'un appareil de communication', 'description' => 'Communique à l\'aide d\'un dispositif électronique'],
            ['contentType' => 'other', 'name' => 'Besoin d\'un environnement calme', 'description' => 'Fonctionne mieux dans des espaces tranquilles'],
            ['contentType' => 'other', 'name' => 'Sensibilité tactile', 'description' => 'Peut être sensible à certaines textures'],
            ['contentType' => 'other', 'name' => 'Préférence pour les instructions visuelles', 'description' => 'Comprend mieux avec des supports visuels'],
            ['contentType' => 'other', 'name' => 'Besoin de temps de transition', 'description' => 'Nécessite du temps pour passer d\'une activité à une autre'],
            ['contentType' => 'other', 'name' => 'Utilisation d\'un chien d\'assistance', 'description' => 'Accompagné d\'un chien guide ou d\'assistance'],
            ['contentType' => 'other', 'name' => 'Sensibilité aux odeurs', 'description' => 'Peut être affecté par des parfums ou odeurs fortes'],
            ['contentType' => 'other', 'name' => 'Besoin de prévisibilité', 'description' => 'Apprécie d\'être informé à l\'avance des changements'],
            ['contentType' => 'other', 'name' => 'Utilisation de pictogrammes', 'description' => 'Communique à l\'aide de symboles visuels'],
            ['contentType' => 'other', 'name' => 'Préférence pour les petits groupes', 'description' => 'Se sent plus à l\'aise dans des environnements moins peuplés'],
            ['contentType' => 'other', 'name' => 'Besoin de stimulation sensorielle', 'description' => 'Bénéficie d\'activités sensorielles spécifiques'],
            ['contentType' => 'other', 'name' => 'Utilisation d\'un fauteuil roulant électrique', 'description' => 'Se déplace de manière autonome en fauteuil motorisé'],
            ['contentType' => 'other', 'name' => 'Sensibilité à la température', 'description' => 'Nécessite un contrôle précis de la température ambiante'],
            ['contentType' => 'other', 'name' => 'Communication par écrit', 'description' => 'Préfère communiquer par écrit plutôt qu\'oralement'],
            ['contentType' => 'other', 'name' => 'Besoin de repères spatiaux', 'description' => 'Utilise des marqueurs ou des repères pour s\'orienter'],
            ['contentType' => 'other', 'name' => 'Utilisation d\'un appareil auditif', 'description' => 'Porte des aides auditives pour améliorer l\'audition'],
            ['contentType' => 'other', 'name' => 'Préférence pour les activités structurées', 'description' => 'Apprécie les activités avec des règles claires'],
            ['contentType' => 'other', 'name' => 'Besoin de soutien émotionnel', 'description' => 'Peut nécessiter un accompagnement émotionnel régulier'],
            ['contentType' => 'other', 'name' => 'Utilisation de techniques de relaxation', 'description' => 'Pratique des exercices de respiration ou de méditation']
            ];

        return $characteristic_items[array_rand($characteristic_items)];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this// ->afterInstantiate(function(Base $base): void {})
            ;
    }
}
