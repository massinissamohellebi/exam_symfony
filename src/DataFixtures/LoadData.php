<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class LoadData extends Fixture
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $em)
    {
        // initialisation de l'objet Faker
        $faker = Faker\Factory::create('fr_FR');

        // créations des utilisateurs
        $users = [];
        for ($i=0; $i < 10; $i++) {
            $users[$i] = new User();
            $users[$i]->setName($faker->firstName);
            $em->persist($users[$i]);
        }

        // créations des questions
        $questions = [];
        for ($k=0; $k < 50; $k++) {
            $questions[$k] = new Question();
            $questions[$k]->setTitle($faker->text)
                ->setContent($faker->text)
            ;

            // on récupère un nombre aléatoire de Users dans un tableau
            $randomUsers = (array) array_rand($users, rand(1, count($users)));
            // puis on les ajoute au Question
            foreach ($randomUsers as $key => $value) {
                $questions[$k]->setUser($users[$key]);
            }
            $em->persist($questions[$k]);
        }

        // créations des reponses
        $answers = [];
        for ($k=0; $k < 50; $k++) {
            $answers[$k] = new Answer();
            $answers[$k]->setContent($faker->text)
                ->setStatus($faker->boolean(25))
            ;

            // on récupère un nombre aléatoire de Questions dans un tableau
            $randomQuestions = (array) array_rand($questions, rand(1, count($questions)));
            // puis on les ajoute a la reponse
            foreach ($randomQuestions as $key => $value) {
                $answers[$k]->setQuestion($questions[$key]);
            }
            $em->persist($answers[$k]);
        }

        $em->flush();
    }
}
