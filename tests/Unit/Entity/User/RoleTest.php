<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function testChange(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        self::assertFalse($user->isAdmin());

        $user->changeRole(User::ROLE_ADMIN);
    }

    public function testAlready(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->expectExceptionMessage('User already exists');

        $user->changeRole(User::ROLE_ADMIN);
    }

}
