<?php


use App\Filament\Pages\Tenancy\InviteToOrganization;
use App\Mail\OrganizationInvite;
use App\Models\OrganizationInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use function Pest\Livewire\livewire;

it('can invite a user to an organization', function () {
    // test if the user is administrator of an organization
});

it('cannot invite a user to an organization', function () {
    // test if the user is not administrator of an organization
});

it('invite a user to an organization', function () {
    Mail::fake();

    $user = User::factory()->withOrganization()->create();
    $this->actingAs($user);

    $organization = $user->organizations->first();

    $this->get('/admin/' . $organization->id . '/invite')
        ->assertStatus(200);


    livewire(InviteToOrganization::class)
        ->fillForm([
            'email' => '',
        ])
        ->call('invite')
        ->assertHasFormErrors(['email' => 'required']);

    $inviteEmail = 'invitation@email.com';

    livewire(InviteToOrganization::class)
        ->fillForm([
            'email' => $inviteEmail,
        ])
        ->call('invite')
        ->assertHasNoFormErrors(['email']);

    $this->assertDatabaseHas('organization_invitations', [
        'email' => $inviteEmail,
        'organization_id' => $organization->id,
    ]);

    Mail::assertSent(OrganizationInvite::class, function ($mail) use ($inviteEmail) {
        return $mail->hasTo($inviteEmail);
    });
});

it('can accept an invitation', function () {
    $user = User::factory()->withOrganization()->create();
    $this->actingAs($user);

    $invitingEmail = 'invited@email.com';

    $invitation = OrganizationInvitation::factory()->create([
        'email' => $invitingEmail,
        'organization_id' => $user->organizations->first()->id,
        'invited_by_id' => $user->id,
    ]);

    $invitedUser = User::factory()->create([
        'email' => $invitingEmail,
    ]);
    $this->actingAs($invitedUser);

    $this->get(route('organization.accept-invitation', ['token' => $invitation->token]))
        ->assertStatus(302)
        ->assertSessionDoesntHaveErrors();

    $this->assertDatabaseHas('organization_invitations', [
        'email' => $invitingEmail,
        'organization_id' => $user->organizations->first()->id,
        'invited_by_id' => $user->id,
        'accepted_at' => now()->format('Y-m-d H:i:s'),
    ]);

});
