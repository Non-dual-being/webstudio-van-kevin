<?php

namespace Tests\Feature;

use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_read_or_change_private_account_and_team_routes(): void
    {
        Mail::fake();
        Notification::fake();
        $owner = User::factory()->create(['name' => 'Fictieve beheerder']);
        $team = $owner->personalTeam();
        $invitation = TeamInvitation::factory()->create([
            'team_id' => $team->id, 'invited_by' => $owner->id, 'email' => 'invite@example.com',
        ]);
        $routes = [
            ['GET', '/'.$team->slug.'/dashboard'],
            ['GET', '/settings'], ['GET', '/settings/profile'],
            ['PATCH', '/settings/profile'], ['DELETE', '/settings/profile'],
            ['GET', '/settings/security'], ['PUT', '/settings/password'],
            ['GET', '/settings/appearance'],
            ['GET', '/settings/teams'], ['POST', '/settings/teams'],
            ['GET', '/settings/teams/'.$team->slug],
            ['PATCH', '/settings/teams/'.$team->slug],
            ['DELETE', '/settings/teams/'.$team->slug],
            ['POST', '/settings/teams/'.$team->slug.'/switch'],
            ['DELETE', '/settings/teams/'.$team->slug.'/leave'],
            ['PATCH', '/settings/teams/'.$team->slug.'/members/'.$owner->id],
            ['DELETE', '/settings/teams/'.$team->slug.'/members/'.$owner->id],
            ['POST', '/settings/teams/'.$team->slug.'/invitations'],
            ['DELETE', '/settings/teams/'.$team->slug.'/invitations/'.$invitation->code],
            ['GET', '/invitations/'.$invitation->code.'/accept'],
            ['DELETE', '/invitations/'.$invitation->code],
        ];
        foreach ($routes as [$method, $path]) {
            $this->call($method, $path)->assertRedirect('/login');
            $this->json($method, $path)->assertUnauthorized()
                ->assertDontSee('Fictieve beheerder')->assertDontSee($owner->email);
        }
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('teams', 1);
        $this->assertNull($invitation->fresh()->accepted_at);
        Mail::assertNothingSent();
        Notification::assertNothingSent();
    }

    public function test_public_pages_do_not_share_account_or_team_data(): void
    {
        User::factory()->create();
        foreach (['/', '/privacy', '/contact', '/login'] as $path) {
            $this->get($path)->assertInertia(fn (Assert $page) => $page
                ->where('auth.user', null)->where('teams', [])->where('currentTeam', null)
                ->missing('submissions')->missing('deliveries')->missing('teamInvitation'));
        }
    }

    public function test_no_public_contact_read_or_export_endpoints_exist(): void
    {
        foreach (['/contact/1', '/contact/export', '/contact-submissions', '/api/contact', '/contact-mail'] as $path) {
            $this->getJson($path)->assertNotFound();
        }
    }
}
