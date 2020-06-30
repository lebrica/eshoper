<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use phpDocumentor\Reflection\Types\Context;
use App\DataFixtures\CategoryFixtures;

class AppFixtures extends CategoryFixtures
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 1; $i < 3; $i++) {
            $title = $faker->word;
            $product_code = $faker->randomNumber();
            $price = $faker->numberBetween(30,250);
            $description = $faker->sentence(15);
            $brand = $faker->word;
            $new = $faker->numberBetween(0,1);
            $recommend = $faker->numberBetween(0,1);

          //  $category = $this->getReference('14', Category::class);

            $category = $manager->getReference(Category::class, $faker->numberBetween(1,8));


            $product = new Product();
            $product->setTitle($title);
            $product->setProductCode($product_code);
            $product->setImage('/images/home/product4.jpg');
            $product->setPrice($price);
            $product->setNew($new);
            $product->setDescription($description);
            $product->setRecommended($recommend);
            $product->setBrand($brand);
            $product->setCategory_id($category);

            $manager->persist($product);
        }
        $manager->flush();
    }

}
