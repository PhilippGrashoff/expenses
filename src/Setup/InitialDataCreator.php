<?php declare(strict_types=1);

namespace Expenses\Setup;

use Expenses\Data\Core;
use Expenses\Data\User;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

class InitialDataCreator
{
    public function createInitialData(): void
    {
        $this->createAdminUser();
    }

    public function createAdminUser(): void
    {
        $generator = new ComputerPasswordGenerator();
        $generator
            ->setLength(12);
        $password = $generator->generatePassword();
        $adminUser = (new User(Core::get()->getPersistence()))->createEntity()
            ->set('name', 'admin')
            ->setPassword($password)
            ->save();

        echo 'Admin user created with password: ' . $password . PHP_EOL;
    }
}