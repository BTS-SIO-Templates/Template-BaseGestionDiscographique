<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Style;
use App\Entity\Artiste;
use App\Entity\Album;
use App\Entity\Morceau;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPassword;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPassword = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // gestion des users
        $faker = Factory::create('fr_FR');
        $genres=["male","female"];
        for ($i = 0; $i < 20; $i++) {
            $sexe=mt_rand(0,1);
            if ($sexe == 0){ $type = "men";}
            else {$type = "women";}
       
        $user = new User();
        $user->setNom($faker->lastName())
             ->setPrenom($faker->firstName($genres[$sexe]))
             ->setEmail($faker->email())
             ->setSexe($sexe)
             ->setIsVerified(true)
            ->setPassword( $this->userPassword->hashPassword($user,"test1234"))
             ->setAvatar("https://randomuser.me/api/portraits/".$type."/".$i.".jpg");
                $manager->persist($user);    
        }
        $admin = new User();
        $admin->setNom("Admin")
             ->setPrenom("Personne")
             ->setEmail("admin@gmail.com")
             ->setSexe($sexe)
             ->setRoles(["ROLE_ADMIN"])
             ->setIsVerified(true)
             ->setPassword( $this->userPassword->hashPassword($admin,"admin1234"))
             ->setAvatar("https://randomuser.me/api/portraits/".$type."/".$i.".jpg");
                $manager->persist($admin);    

        // gestion des styles
        $LesStyles = $this->chargeFichier("style.csv");
        
        foreach ($LesStyles as $value)
            {
                $style=new Style();
                $style->setId(intval($value[0]))
                        ->setNom($value[1])
                        ->setCouleur($faker->hexColor());
                $manager->persist($style);
                $this->addReference("style".$style->getId(),$style);
            }

        // gestion des artistes
        $lesArtistes=$this->chargeFichier("artiste.csv");


            $genres=["men","women"];

            foreach ($lesArtistes as $value)
            {
                $artiste=new Artiste();
                $artiste->setId(intval($value[0]))
                        ->setNom($value[1])
                        ->setDescription("<p>".join("</p><p>",$faker->paragraphs(5))."</p>")
                        ->setSite($faker->url())
                        ->setImage('https://randomuser.me/api/portraits/'.$faker->randomElement($genres)."/".mt_rand(1,99).".jpg")
                        ->setType($value[2]);
                $manager->persist($artiste);
                $this->addReference("artiste".$artiste->getId(),$artiste);
            }
        
        // gestin des albums
            $lesAlbums=$this->chargeFichier("album.csv");
            foreach($lesAlbums as $value)
                {
                        $album = new Album();
                        $album ->setId(intval($value[0]))
                        ->setNom($value[1])
                        ->setDate(intval($value[2]))
                        ->setImage('https://lorempicture.point-sys.com/400/300/loisir/'.mt_rand(0,30)."/")
                        ->addStyle($this->getReference("style".$value[3],style::class))
                        ->setArtiste($this->getReference("artiste".$value[4],artiste::class));
                    $manager->persist($album);
                    $this->addReference("album".$album->getId(),$album);
                }

        // gestion des morceaux
            $lesMorceaux=$this->chargeFichier("morceau.csv");
            foreach($lesMorceaux as $value)
                {
                    $morceau = new Morceau();
                        $morceau ->setId(intval($value[0]))
                        ->setTitre($value[2])
                        ->setAlbum($this->getReference("album".$value[1],album::class))
                        ->setPiste(intval($value[4]))
                        ->setDuree(date("i:s",$value[3]));
                    $manager->persist($morceau);
                    $this->addReference("morcea".$morceau->getId(),$morceau);
                }

        $manager->flush();
    }
    public function chargeFichier($fichier)
    {
       $fichierCsv=fopen(__DIR__."/".$fichier,"r");
        while(!feof($fichierCsv))
            {
                $data[]=fgetcsv($fichierCsv);
            }
            
            fclose($fichierCsv); 
            return $data;
    }
}
