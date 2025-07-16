<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    public function testRequest()
    {
        $user = User::register(
            $name = 'name',
            $email = 'email',
            $password = 'password',
        );

        self::assertNotEmpty($user);

        self::assertEquals($name, $user->name);
        self::assertEquals($email, $user->email);

        self::assertNotEmpty($user->password);
        self::assertNotEquals($password, $user->password);

        self::assertTrue($user->isWait());
        self::assertTrue($user->isActive());
    }

    public function testAlreadyVerified()
    {
        $user = User::register('name', 'email', 'password');

        $user->verify();

        $this->expectExceptionMessage('User already verified.');
        $user->verify();
    }
}
