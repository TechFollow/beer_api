<?php

namespace App\DataFixtures;

use App\Entity\Beer;
use App\Entity\Brasserie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $max_line = 1000;
        $line = 0;
        $data = [];

        if (($file = fopen("../open-beer-database.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($file, 1200, ";")) !== FALSE) {
                if ($data == NULL || $data[0] == NULL || count($data) != 22) {
                    continue;
                }
                $brasserie = $this->create_brasserie($data);
                $manager->persist($brasserie);
                $beer = $this->create_beer($data, $brasserie);
                $manager->persist($beer);
                $line++;
                if ($line >= $max_line) {
                    break;
                }
            }
        }
        fclose($file);
        $user = $this->create_user();
        $manager->persist($user);
        $manager->flush();
    }

    private function create_user() : User
    {
        $user = new User();
        $user->setEmail("test@email.fr");
        $user->setPassword("root");
        $user->setRole(['admin']);
        $user->setPseudo("root");
        $user->setDateCreate(new \DateTime());
        $user->setDateUpdate(new \DateTime());
        return $user;
    }

    private function create_beer($data, Brasserie $brasserie) : Beer
    {
        $beer = new Beer();

        $beer->setName($data[0]);
        $beer->setAbv((float)$data[5]);
        $beer->setIbu((int)$data[6]);
        $beer->setBrasserie($brasserie);
        $beer->setDateCreate(new \DateTime());
        $beer->setDateUpdate(new \DateTime());
        return $beer;
    }

    private function create_brasserie($data) : Brasserie
    {
        $brasserie = new Brasserie();

        $brasserie->setName($data[15]);
        $brasserie->setStreet($data[16]);
        $brasserie->setCity($data[17]);
        $brasserie->setPostalCode("");
        $brasserie->setCountry($data[19]);
        $brasserie->setDateCreate(new \DateTime());
        $brasserie->setDateUpdate(new \DateTime());
        return $brasserie;
    }
}
