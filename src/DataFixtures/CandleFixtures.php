<?php

namespace App\DataFixtures;

use App\Entity\Candle;
use App\Entity\Category;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Monolog\Logger;

class CandleFixtures extends DevFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $allCategories = $manager->getRepository(Category::class)->findAll();
        $formattedCategories = [];

        foreach ($allCategories as $category) {
            $formattedCategories[$category->getName()] = $category;
        }

        $candles = [
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Chaleur beurrée et réconfortante", "stock" => 1, "name" => "Croissant", "categories" => ["Gâteau", "Sucré"], "path" => "croissant"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Fraîcheur sucrée et vivifiante", "stock" => 1, "name" => "Fraise et menthe", "categories" => ["Sucré", "Fruit", "Été"], "path" => "fraiseMenthe"],
            ["active" => false, "description" => "Test", "price" => 2000, "short_description" => "Pour une petite touche de piquant", "stock" => 6, "name" => "Piment", "categories" => ["Divers"], "path" => "piment"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Charme classique et floral raffiné", "stock" => 1, "name" => "Rose ancienne", "categories" => ["Fleur", "Printemps", "Amour"], "path" => "roseAncienne"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Douceur crémeuse et délicatement parfumée", "stock" => 1, "name" => "Lait d'Amande", "categories" => ["Soin"], "path" => "laitAmande"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Un parfum floral délicatement envoûtant", "stock" => 5, "name" => "Magnolia", "categories" => ["Printemps", "Fleur"], "path" => "magnolia"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Un parfum doux, romantique et envoûtant", "stock" => 1, "name" => "Secret d'Amour", "categories" => ["Amour", "Printemps"], "path" => "secretAmour"],
            ["active" => false, "description" => "Test", "price" => 2000, "short_description" => "Fraîcheur intense et revitalisante", "stock" => 2, "name" => "Menthe Glacée", "categories" => ["Sucré", "Été"], "path" => "mentheGlacee"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Caractère brut et sophistiqué", "stock" => 4, "name" => "Cuir", "categories" => ["Divers"], "path" => "cuir"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Acidité zestée et douceur sucrée", "stock" => 2, "name" => "Citron Meringué", "categories" => ["Gâteau", "Sucré", "Hiver"], "path" => "citronMeringue"],
            ["active" => false, "description" => "Test", "price" => 2000, "short_description" => "Chaleur épicée et douce magie", "stock" => 4, "name" => "Délice de Noël", "categories" => ["Noël", "Sucré", "Hiver", "Chocolat"], "path" => "deliceNoel"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Douceur fondante et sucrée", "stock" => 1, "name" => "Chamallow", "categories" => ["Bonbon", "Sucré"], "path" => "chamallow"],
            ["active" => false, "description" => "Test", "price" => 2000, "short_description" => "Chaleur sucrée et zestée", "stock" => 4, "name" => "Orange confite", "categories" => ["Sucré", "Fruit"], "path" => "orangeConfite"],
            ["active" => false, "description" => "Test", "price" => 2000, "short_description" => "Élégance florale et douceur raffinée", "stock" => 2, "name" => "Iris Jasmin", "categories" => ["Fleur", "Printemps", "Soin"], "path" => "irisJasmin"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Douceur florale et pureté apaisante", "stock" => 2, "name" => "Fleur de lin", "categories" => ["Printemps", "Fleur"], "path" => "fleurLin"],
            ["active" => false, "description" => "Test", "price" => 2000, "short_description" => "Crème douce et éclats chocolatés", "stock" => 6, "name" => "Stracciatella", "categories" => ["Chocolat", "Sucré"], "path" => "stracciatella"],
            ["active" => true, "description" => "Test", "price" => 1500, "short_description" => "Un duo frais et fruité", "stock" => 4, "name" => "Pastèque Melon", "categories" => ["Sucré", "Fruit", "Été"], "path" => "pastequeMelon"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Calme et sérénité apaisantes", "stock" => 2, "name" => "Relaxation", "categories" => ["Soin"], "path" => "relaxation"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Croquant et cœur praliné gourmand", "stock" => 1, "name" => "Rocher praliné", "categories" => ["Chocolat", "Sucré", "Gâteau"], "path" => "rocherPraline"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Un parfum gourmand et réconfortant", "stock" => 2, "name" => "Pop corn", "categories" => ["Sucré", "Bonbon"], "path" => "popCorn"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Craquant et délicieusement sucré", "stock" => 1, "name" => "P'tit Biscuit", "categories" => ["Gâteau", "Sucré"], "path" => "ptitBiscuit"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Élégance d'une rose intemporelle", "stock" => 1, "name" => "Rose éternelle", "categories" => ["Amour", "Printemps", "Fleur"], "path" => "roseVanillee"],
            ["active" => false, "description" => "Test", "price" => 2000, "short_description" => "Saveurs épicées et réconfortantes", "stock" => 3, "name" => "Gâteau de Noël", "categories" => ["Sucré", "Noël", "Gâteau"], "path" => "gateauNoel"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Un bonbon sucré", "stock" => 2, "name" => "Stroumph", "categories" => ["Bonbon", "Sucré"], "path" => "stroumph"],
            ["active" => true, "description" => "Test", "price" => 1500, "short_description" => "Pour une petite touche de piquant", "stock" => 5, "name" => "Fraise", "categories" => ["Sucré", "Fruit", "Été"], "path" => "fraise"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Douceur chocolatée et fondante", "stock" => 2, "name" => "Mousse au chocolat", "categories" => ["Chocolat", "Sucré"], "path" => "mousseChocolat"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Goût pétillant et sucré de cola", "stock" => 1, "name" => "Bonbon Cola", "categories" => ["Bonbon", "Sucré"], "path" => "bonbonCola"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Exotisme fruité et parfum envoûtant", "stock" => 2, "name" => "Monoï Pêche", "categories" => ["Fruit", "Été", "Soin"], "path" => "monoiPeche"],
            ["active" => false, "description" => "Test", "price" => 1500, "short_description" => "Un arôme herbacé et unique", "stock" => 1, "name" => "Cannabis", "categories" => ["Divers"], "path" => "cannabis"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Douceur fruitée et crémeuse", "stock" => 4, "name" => "Framboise Caramel", "categories" => ["Sucré", "Fruit", "Été"], "path" => "framboiseCaramel"],
            ["active" => true, "description" => "Test", "price" => 2000, "short_description" => "Gourmandise intense et croquante", "stock" => 1, "name" => "Chocolat Noisettes", "categories" => ["Gâteau", "Sucré", "Chocolat"], "path" => "chocolatNoisettes"],
        ];

        foreach ($candles as $candleData) {
            $candle = new Candle();
            $candle->setDescription($candleData["description"]);
            $candle->setPrice($candleData["price"]);
            $candle->setShortDescription($candleData["short_description"]);
            $candle->setStock($candleData["stock"]);
            $candle->setTitle($candleData["name"]);
            $candle->setActive($candleData["active"]);

            foreach ($candleData["categories"] as $categoryName) {
                $candle->addCategory($formattedCategories[$categoryName]);
            }

            for ($i = 0; ++$i <= 3; ) {
                $image = new Image();
                $image->setName($i);
                $image->setPath($candleData["path"] . "/$i.jpg");
                $candle->addImage($image);
                $manager->persist($image);
            }

            $manager->persist($candle);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
