<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
    @var Generator pour les faux noms
     **/
    private Generator $faker;

    private UserPasswordHasherInterface $hasher;

    public  function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;

    }
    public function load(ObjectManager $manager): void
    {
       /**
        * boucle For pour creer 50 fixtures d'ingredients que je stocke dans tableau $ingredients[]
       **/
            $ingredients = [];
        for ($i=0;$i<50;$i++)
            {
                $ingredient = new Ingredient();
                $ingredient->setName($this->faker->word())
                    ->setPrice(mt_rand(0,100));
                $ingredients[] = $ingredient;
                $manager->persist($ingredient);
            }

        /**
         * Idem pour Recette et 2ème boucle For incluse dans le 1 er For
         * pour attribuer des ingredients aux recettes créees avec AddIngredients
        **/

        for ($j = 0;$j<25;$j++)
        {
            $recette = new  Recette();
            $recette->setName($this->faker->word())
                ->setPrice(mt_rand(0,100))
                ->setTime(mt_rand(0,1)==1 ? mt_rand(1,1440): null)
                ->setNbPeople(mt_rand(0,1)==1 ?mt_rand(0,50):null)
                ->setDifficulty(mt_rand(0,1)==1 ?mt_rand(0,5):null)
                ->setDescription($this->faker->text(199))
                ->setPrice(mt_rand(0,1)==1 ?mt_rand(0,1000):null)
                ->setIsFavorite(mt_rand(0,1)==1 ? true : false);
            //2ème For
            for ($k=0;$k<mt_rand(5,15);$k++)
            {
                $recette->addIngredient($ingredients[mt_rand(0,count($ingredients) -1)]);
            }
            $manager->persist($recette);
        }

        //Users
        for ($i=0;$i<10;$i++)
        {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0,1)===1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $manager->persist($user);
        }

        $manager->flush();
    }
}
