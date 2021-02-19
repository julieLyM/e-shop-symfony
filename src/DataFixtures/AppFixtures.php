<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $telephone = new Category();
        $telephone->setName('Telephone')->setAlias('telephone');

        $ordinateur = new Category();
        $ordinateur->setName('Ordinateur')->setAlias('ordinateur');

        $manager->persist($telephone);
        $manager->persist($ordinateur);

        $user = new User();
        $user->setFirstname('juju')
            ->setLastname('lili')
            ->setEmail('juju@juju.eshop')
            ->setPassword('demo')
            ->setAdresse('10 rue de Paris')
            ->setZipcode(75012)
            ->setCity('Paris')
            ->setTelephone('0160609925')
            ->setCreatedAt(new \DateTime())
            ->setRoles(['ROLE_USER']);

        $manager->persist($user);

        for ($i = 0; $i < 5; $i++) {

            $product = new Product();
            $product->setName('iphone' . $i)
                    ->setAlias('testIphone' . $i)
                    ->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse exercitationem facere possimus quis repellat repellendus reprehenderit, tempora ut. Distinctio eum expedita fuga, libero odit rem repellat repellendus reprehenderit unde voluptatibus?')
                    ->setPrice(1400)
                    ->setUser($user)
                    ->setImage('https://via.placeholder.com/500')
                    ->setCreatedAt(new \DateTime())
                    ->addCategory($telephone);

            $manager->persist($product);
        }

        for ($i = 5; $i < 10; $i++) {

            $product = new Product();
            $product->setName('imac' . $i)
                ->setAlias('testMac' . $i)
                ->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse exercitationem facere possimus quis repellat repellendus reprehenderit, tempora ut. Distinctio eum expedita fuga, libero odit rem repellat repellendus reprehenderit unde voluptatibus?')
                ->setPrice(2200)
                ->setUser($user)
                ->setImage('https://via.placeholder.com/500/')
                ->setCreatedAt(new \DateTime())
                ->addCategory($ordinateur);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
