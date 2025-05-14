<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use App\Entity\Setting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SettingFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $setting = $this->createSetting();

        $manager->persist($setting);
        $manager->flush();
    }

    private function createSetting(): Setting
    {
        $setting = new Setting();

        $setting->setEmail('medecine-du-monde@gmail.com');
        $setting->setPhone('01 01 01 01 01');
        $setting->setCreatedAt(new DateTimeImmutable());
        $setting->setUpdatedAt(new DateTimeImmutable());

        return $setting;
    }
}
