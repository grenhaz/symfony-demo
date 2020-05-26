<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Movie;

/**
 * Clase para la generación de la BBDD.
 * 
 * @author obarcia
 */
class AppFixtures extends Fixture
{
    private $appKernel;
    private $passwordEncoder;

    public function __construct(KernelInterface $appKernel, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->appKernel            = $appKernel;
        $this->passwordEncoder      = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        // Usuario
        $user = new User();
        $user->setUsername("user");
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'user'));
        $manager->persist($user);
        
        // Categorías
        $categories = ["Romance","Terror","Suspense","Misterio","Drama","Desastres","Musical"];
        $cobjs = [];
        foreach ($categories as $c) {
            $category = new Category();
            $category->setName($c);
            $manager->persist($category);
            $cobjs[] = $category;
        }
        
        // Películas
        $path = $this->appKernel->getProjectDir();
        $images = iterator_count(new \FilesystemIterator($path."/data/images", \FilesystemIterator::SKIP_DOTS));
        for ($i = 0; $i < 100; $i ++) {
            $movie = new Movie();
            $movie->setName("Movie ".($i + 1));
            $movie->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec risus massa, pellentesque id lacinia sit amet, blandit a orci. Donec quam diam, varius id lorem at, finibus consectetur metus. Sed pellentesque iaculis enim. Proin odio augue, maximus non fringilla eu, tincidunt vel turpis. Maecenas in arcu tellus. Mauris tristique ac enim vel condimentum. Sed ultrices leo vel vestibulum vulputate. Donec in sem ut quam bibendum tempus. In porttitor dolor erat, sit amet dignissim ex aliquam et. Nam hendrerit nec urna ut aliquet. Phasellus a dapibus tortor, eu dignissim metus. Duis dolor turpis, pharetra auctor erat nec, congue hendrerit ex. Nulla vel odio et dolor pretium pharetra suscipit at nibh. Ut libero turpis, viverra et dolor eget, faucibus consectetur nulla. Nam nibh lectus, tincidunt at odio a, vehicula congue nisl.");
            $movie->setDateAdded(new \DateTime());
            $movie->setAlert((int)rand(0, 1));
            $movie->setYear((int)rand(2000, 2020));
            $movie->setDuration((int)rand(60, 180));
            $movie->setImage("movie".((int)rand(1, $images)).".jpg");
            $movie->setViews((int)rand(0, 1000));
            $ncats = (int)rand(1, count($cobjs) - 1);
            for ($j = 0; $j < $ncats; $j ++) {
                $cat = $cobjs[rand(0, count($cobjs) - 1)];
                if (!$movie->getCategory()->contains($cat)) {
                    $movie->getCategory()[] = $cat;
                }
            }
            $manager->persist($movie);
        }

        $manager->flush();
    }
}
