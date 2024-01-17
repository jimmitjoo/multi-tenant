<?php

use App\Models\User;

it('has a user resource', function () {

    $user = User::factory()->withOrganization()->create();
    $this->actingAs($user);

    $this->get('/admin/' . $user->organizations->first()->id)
        ->assertOk();
});

it('can list users in organization', function () {

    $user = User::factory()->withOrganization()->create();
    $this->actingAs($user);

    $inOrganization = User::factory(5)
        ->withOrganization($user->organizations->first()->id)
        ->create();

    $this->get('/admin/' . $user->organizations->first()->id . '/users')
        ->assertOk()
        ->assertSee($inOrganization->first()->name)
        ->assertSee($inOrganization->last()->name);
});
