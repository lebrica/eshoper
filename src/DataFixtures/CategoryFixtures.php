<?php


namespace App\DataFixtures;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{


    public static function ProductCategoryKey($i)
    {
        return sprintf('id_%s', $i);
    }


    public function load(ObjectManager $manager)
    {

        for ($i = 14; $i < 20; $i++) {

            $category = new Category();
            $manager->persist($category);

            $this->addReference(self::ProductCategoryKey($i), $category);
        }

    }
}