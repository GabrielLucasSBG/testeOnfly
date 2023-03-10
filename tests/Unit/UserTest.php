<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_create_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Gabriel',
            'email' => 'gabriele@example.com',
            'password' => bcrypt('password')
        ]);

        // Verifica se o usuário foi criado corretamente
        $this->assertDatabaseHas('users', [
            'name' => 'Gabriel',
            'email' => 'gabriele@example.com',
        ]);
    }
}
