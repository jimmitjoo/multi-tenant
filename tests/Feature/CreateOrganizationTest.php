<?php

use App\Filament\Pages\Tenancy\RegisterOrganization;
use App\Models\User;
use function Pest\Livewire\livewire;

it('has to create an organization if the user is not a member of one', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin')->assertRedirect('/admin/' . RegisterOrganization::getSlug());
});

it('can create an organization', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/' . RegisterOrganization::getSlug())->assertStatus(200);

    livewire(RegisterOrganization::class)
        ->fillForm([
            'name' => 'Test Organization',
        ])
        ->call('register')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('organizations', [
        'name' => 'Test Organization',
    ]);
});
