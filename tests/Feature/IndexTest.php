<?php

it('index route works', function () {
   $this->get('/')->assertStatus(200);
});
