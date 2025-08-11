<?php

namespace Tests\Feature\Auth;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('login');

        $response
            ->assertStatus(200)
            ->assertSee('login');
    }

    public function testErrors(): void
    {
        $response = $this->post('login', [
            'email' => '',
            'password' => '',
        ]);
        $response->assertStatus(302)->assertSessionHasErrors(['email', 'password']);
    }

    public function testWait(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_WAIT]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);
        $response->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error', 'You are now logged in.');
    }

    public function testActive(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);
        $response->assertStatus(302)
            ->assertRedirect('/cabinet')
            ->assertSessionHas('error', 'You are now logged in.');
        $this->assertAuthenticated();
    }
}
