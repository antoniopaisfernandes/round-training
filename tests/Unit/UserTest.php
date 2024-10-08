<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function the_password_is_hashed_by_a_mutator()
    {
        $user = User::make([
            'password' => 'pass123',
        ]);

        $this->assertTrue(Hash::check('pass123', $user->password));
    }

    /** @test */
    public function the_user_factory_generates_known_plain_password()
    {
        $user = User::factory()->make();

        $this->assertTrue(Hash::check('password', $user->password));
    }
}
