<?php

namespace Tests\Feature\Auth;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     */
    public function testForm(): void
    {
        $response = $this->get('/register');

        $response
            ->assertStatus(200)
            ->assertSee('Register');
    }

    public function testErrors(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $response->assertStatus(302)->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function testSuccess(): void
    {
        $user = User::factory()->make();

        $response = $this->post('/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);
        $response->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'You are now logged in.');
    }

    public function testVerifyIncorrect(): void
    {

        $response = $this->get('/verify/' . Str::uuid());
        $response->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('error', 'You are now logged in.');
    }

    public function testVerify(): void
    {
        $user = User::factory()->create([
            'status' => User::STATUS_ACTIVE,
            'verify_token' => Str::uuid(),
        ]);

        $response = $this->get('/verify/' . $user->verify_token);

        $response->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'You are now logged in.');
    }
}
