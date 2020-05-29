<?php

namespace App\DataFixtures;

use App\Entity\Beer;
use App\Entity\User;
use App\Entity\Checkin;
use App\Entity\Brasserie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $max_line = 1000;
        $line = 0;
        $data = [];

        $this->prepare($manager);

        $user1 = $this->create_user("root", "test@email.fr", "root");
        $user2 = $this->create_user("userX", "abc@email.fr", "user");
        $manager->persist($user1);
        $manager->persist($user2);

        if (($file = fopen("../open-beer-database.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($file, 1200, ";")) !== FALSE) {
                if ($data == NULL || $data[0] == NULL || count($data) != 22) {
                    continue;
                }
                if (($line % 2) == 0) {
                    $user = $user1;
                } else {
                    $user = $user2;
                }
                $brasserie = $this->create_brasserie($data);
                $manager->persist($brasserie);
                $beer = $this->create_beer($data, $brasserie);
                $manager->persist($beer);
                $checkin = $this->create_checkin($user, $beer);
                $manager->persist($checkin);
                $line++;
                if ($line >= $max_line) {
                    break;
                }
            }
        }
        fclose($file);
        $manager->flush();
    }

    private function prepare(ObjectManager $manager)
    {
        $conn = $manager->getConnection();

        $conn->exec("ALTER TABLE beer AUTO_INCREMENT = 1;");
        $conn->exec("ALTER TABLE user AUTO_INCREMENT = 1;");
        $conn->exec("ALTER TABLE brasserie AUTO_INCREMENT = 1;");
        $conn->exec("ALTER TABLE checkin AUTO_INCREMENT = 1;");
    }

    private function create_checkin(User $user, Beer $beer) : Checkin
    {
        $checkin = new Checkin();
        $mark = (rand(0, 100) / 10);

        $checkin->setMark($mark);
        $checkin->setBeer($beer);
        $checkin->setUser($user);
        $checkin->setDateCreate(new \DateTime());
        $checkin->setDateUpdate(new \DateTime());
        return $checkin;
    }

    private function create_user($pseudo, $email, $passwd) : User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($passwd);
        $user->setRole(['role']);
        $user->setPseudo($pseudo);
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
