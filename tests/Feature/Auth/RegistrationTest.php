<?php

namespace Tests\Feature\Auth;

use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_routes_are_absent(): void
    {
        $this->assertFalse(Route::has('register'));
        $this->assertFalse(Route::has('register.store'));
        $this->get('/register')->assertNotFound();
        $this->getJson('/register')->assertNotFound();
    }

    public function test_direct_registration_posts_cannot_create_accounts_even_with_an_invitation(): void
    {
        Mail::fake();
        Notification::fake();
        $owner = User::factory()->create();
        $team = $owner->personalTeam();
        $invitation = TeamInvitation::factory()->create([
            'team_id' => $team->id,
            'email' => 'invited@example.com',
            'invited_by' => $owner->id,
        ]);

        $fields = [
            'name' => 'Fictieve bezoeker', 'email' => 'invited@example.com',
            'password' => 'a-strong-fictional-password',
            'password_confirmation' => 'a-strong-fictional-password',
            'invitation' => $invitation->code,
        ];
        $this->get('/register?invitation='.$invitation->code)->assertNotFound();
        $this->post('/register', $fields)->assertNotFound();
        $this->postJson('/register?invitation='.$invitation->code, $fields)->assertNotFound();
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('teams', 1);
        $this->assertNull($invitation->fresh()->accepted_at);
        Mail::assertNothingSent();
        Notification::assertNothingSent();
    }
}
