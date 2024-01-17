<?php

use App\Models\User;

it('has a user resource', function () {

    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get('/admin')->assertRedirect(302);
});
