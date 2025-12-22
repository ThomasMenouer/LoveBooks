<?php

namespace App\Tests\Unit\Factory;

use App\Domain\Users\Entity\Users;
use App\Tests\Factory\Users\Entity\UsersFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * Tests unitaires pour la factory UsersFactory.
 *
 * Ces tests vérifient que la factory génère des utilisateurs valides avec :
 * - Un email au format valide et unique
 * - Un mot de passe correctement hashé (sécurité)
 * - Les rôles par défaut Symfony
 * - La possibilité de personnaliser les données
 */
class UsersFactoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    /**
     * Test de base : vérifie qu'on peut créer un utilisateur
     * et que les champs obligatoires sont remplis.
     */
    public function testCanCreateUser(): void
    {
        $user = UsersFactory::createOne();

        // Vérifie que c'est bien une instance de Users
        $this->assertInstanceOf(Users::class, $user->_real());

        // Les champs essentiels doivent être remplis
        $this->assertNotEmpty($user->getEmail());
        $this->assertNotEmpty($user->getName());
        $this->assertNotEmpty($user->getPassword());
    }

    /**
     * Vérifie que l'email généré respecte un format valide.
     * Utilise une expression régulière basique : quelquechose@quelquechose.quelquechose
     */
    public function testEmailIsValid(): void
    {
        $user = UsersFactory::createOne();

        // assertMatchesRegularExpression : vérifie que la chaîne correspond au pattern regex
        // Pattern : .+ (1+ caractères) @ .+ (1+ caractères) . .+ (1+ caractères)
        $this->assertMatchesRegularExpression('/^.+@.+\..+$/', $user->getEmail());
    }

    /**
     * Vérifie que chaque utilisateur créé a un email unique.
     * Important car l'email est souvent utilisé comme identifiant de connexion.
     */
    public function testEmailIsUnique(): void
    {
        // Crée 5 utilisateurs
        $users = UsersFactory::createMany(5);

        // Extrait tous les emails dans un tableau
        // fn($user) => $user->getEmail() est une arrow function (PHP 7.4+)
        $emails = array_map(fn($user) => $user->getEmail(), $users);

        // array_unique supprime les doublons
        $uniqueEmails = array_unique($emails);

        // Si tous les emails sont uniques, les deux tableaux ont la même taille
        $this->assertCount(count($emails), $uniqueEmails);
    }

    /**
     * Vérifie que le mot de passe est hashé avec bcrypt.
     * CRUCIAL pour la sécurité : ne jamais stocker de mots de passe en clair !
     *
     * '$2y$' est le préfixe des hash bcrypt en PHP.
     */
    public function testPasswordIsHashed(): void
    {
        $user = UsersFactory::createOne();

        // assertStringStartsWith : vérifie que la chaîne commence par le préfixe
        // $2y$ = algorithme bcrypt (le standard pour hasher les mots de passe)
        $this->assertStringStartsWith('$2y$', $user->getPassword());
    }

    /**
     * Vérifie qu'on peut personnaliser les valeurs lors de la création.
     */
    public function testCanOverrideDefaults(): void
    {
        $user = UsersFactory::createOne([
            'email' => 'custom@example.com',
            'name' => 'Custom Name',
            'isVerified' => true,
        ]);

        $this->assertSame('custom@example.com', $user->getEmail());
        $this->assertSame('Custom Name', $user->getName());
        $this->assertTrue($user->isVerified());
    }

    /**
     * Vérifie que chaque utilisateur a au moins le rôle ROLE_USER.
     *
     * Dans Symfony, ROLE_USER est automatiquement ajouté à tous les utilisateurs
     * authentifiés, même si leur tableau de rôles est vide.
     */
    public function testDefaultRolesIsEmpty(): void
    {
        $user = UsersFactory::createOne();

        // assertContains : vérifie que le tableau contient la valeur
        $this->assertContains('ROLE_USER', $user->getRoles());
    }
}
