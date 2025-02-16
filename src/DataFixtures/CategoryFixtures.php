<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends DevFixtures
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            "Gâteau",
            "Noël",
            "Bonbon",
            "Printemps",
            "Soin",
            "Automne",
            "Hiver",
            "Sucré",
            "Été",
            "Chocolat",
            "Fruit",
            "Amour",
            "Divers",
            "Fleur",
        ];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
