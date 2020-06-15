<?php

use Wingly\Pwinty\Tests\TestCase;
use Wingly\Pwinty\Tests\Fixtures\User;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class FeatureTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Eloquent::unguard();

        $this->loadLaravelMigrations();

        $this->artisan('migrate')->run();
    }

    protected function createUser(): User
    {
        return User::create([
            'email' => 'pwinty@pwinty-test.com',
            'name' => 'Test Pwinty',
            'password' => '$2y$10$lp31h6v/csGyauGkD.wIWe3.d9as/e8uMLRPBT0bR8VPl0VEY2zve',
        ]);
    }
}
