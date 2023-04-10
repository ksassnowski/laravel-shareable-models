<?php

declare(strict_types=1);

namespace Sassnowski\LaravelShareableModel\Tests\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Sassnowski\LaravelShareableModel\Events\LinkWasVisited;
use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;
use Sassnowski\LaravelShareableModel\Tests\Models\Upload;
use Sassnowski\LaravelShareableModel\Tests\TestCase;

class ValidateShareableLinkTest extends TestCase
{
    /**
     * @var Upload
     */
    private $entity;

    public function setUp(): void
    {
        parent::setUp();

        $this->entity = Upload::create(['path' => '/path/to/file']);
    }

    /** @test */
    public function can_access_an_active_link()
    {
        $this->withoutExceptionHandling();
        $link = ShareableLink::buildFor($this->entity)
            ->setActive()
            ->build();

        $response = $this->get($link->url);

        $response->assertStatus(200);
        $response->assertSee($this->entity->path);
    }

    /** @test */
    public function redirect_if_link_is_inactive()
    {
        $link = ShareableLink::buildFor($this->entity)->build();

        $response = $this->get($link->url);

        $response->assertRedirect(config('shareable-model.redirect_routes.inactive'));
    }

    /** @test */
    public function can_not_access_an_expired_link()
    {
        $link = ShareableLink::buildFor($this->entity)
            ->setActive()
            ->setExpirationDate(Carbon::now()->subDay())
            ->build();

        $response = $this->get($link->url);

        $response->assertRedirect(config('shareable-model.redirect_routes.expired'));
    }

    /** @test */
    public function redirects_to_password_prompt_when_trying_to_access_a_password_protected_link()
    {
        $link = ShareableLink::buildFor($this->entity)
            ->setActive()
            ->setPassword('super-secret')
            ->build();

        $response = $this->get($link->url);

        $expectedUrl = url(config('shareable-model.redirect_routes.password_protected'), $link->uuid);
        $response->assertRedirect($expectedUrl);
    }

    /** @test */
    public function can_access_a_password_protected_link_if_the_correct_password_was_entered()
    {
        $link = ShareableLink::buildFor($this->entity)
            ->setActive()
            ->setPassword('super-secret')
            ->build();

        session([$link->uuid->toString() => true]);

        $response = $this->get($link->url);

        $response->assertStatus(200);
        $response->assertSee($link->shareable->path);
    }

    /** @test */
    public function it_triggers_an_event_when_a_link_is_visited_an_configured_to_do_so()
    {
        Event::fake();

        $link = ShareableLink::buildFor($this->entity)
            ->setActive()
            ->notifyOnVisit()
            ->build();

        $this->get($link->url);

        Event::assertDispatched(LinkWasVisited::class);
    }

    /** @test */
    public function it_does_not_trigger_an_event_if_the_link_not_configured_to_do_so()
    {
        Event::fake();

        $link = ShareableLink::buildFor($this->entity)
            ->setActive()
            ->build();

        $this->get($link->url);

        Event::assertNotDispatched(LinkWasVisited::class);
    }
}
