<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    public function testNew()
    {
        $user = User::new(
            $name = 'name',
            $email = 'email',
        );

        self::assertNotEmpty($user);

        self::assertEquals($name, $user->name);
        self::assertEquals($email, $user->email);

        self::assertNotEmpty($user->password);

        self::assertTrue($user->isActive());
    }
}
