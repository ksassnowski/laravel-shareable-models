<?php

namespace Sassnowski\LaravelShareableModel\Tests\Http\Controller;

use Sassnowski\LaravelShareableModel\Tests\TestCase;
use Sassnowski\LaravelShareableModel\Tests\Models\Upload;
use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;

class ShareableLinkPasswordControllerTest extends TestCase
{
    /** @test */
    public function entering_the_correct_password_sets_an_entry_in_the_session()
    {
        $entity = Upload::create(['path' => '/path/to/file']);
        $link = ShareableLink::buildFor($entity)
            ->setActive()
            ->setPassword('super-secret')
            ->build();

        $url = url(config('shareable-model.redirect_routes.password_protected'), $link->uuid);

        $response = $this->post($url, ['password' => 'super-secret']);

        $response->assertRedirect($link->url);
        $response->assertSessionHas($link->uuid, true);
    }

    /** @test */
    public function entering_the_wrong_password_does_not_set_the_session_entry()
    {
        $entity = Upload::create(['path' => '/path/to/file']);
        $link = ShareableLink::buildFor($entity)
            ->setActive()
            ->setPassword('super-secret')
            ->build();

        $url = url(config('shareable-model.redirect_routes.password_protected'), $link->uuid);

        $response = $this->post($url, ['password' => 'wrong-password']);

        $response->assertRedirect($link->url);
        $response->assertSessionMissing($link->uuid);
    }

    /** @test */
    public function show_default_password_form_when_accessing_a_password_protected_route()
    {
        $entity = Upload::create(['path' => '/path/to/file']);
        $link = ShareableLink::buildFor($entity)
            ->setActive()
            ->setPassword('super-secret')
            ->build();

        $url = url(config('shareable-model.redirect_routes.password_protected'), $link->uuid);
        $response = $this->get($url);

        $response->assertStatus(200);
    }
}
