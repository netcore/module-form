<?php

namespace Modules\Form\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormTest extends TestCase
{
    /** @test */
    public function guests_cannot_create_forms()
    {
        $this->get(route('admin::form.create'))->assertRedirect('admin/login');
    }

    /** @test */
    public function admins_can_create_forms()
    {
        $user = app(config('netcore.module-admin.user.model'))->where('is_admin', 1)->first();
        $this->be($user);

        $this->get(route('admin::form.create'))->assertStatus(200);
    }
}
