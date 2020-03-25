<?php

namespace Sassnowski\LaravelShareableModel\Tests\Models;

use Carbon\Carbon;
use Sassnowski\LaravelShareableModel\Tests\TestCase;
use Sassnowski\LaravelShareableModel\Shareable\ShareableLinkBuilder;

class ShareableLinkBuilderTest extends TestCase
{
    /**
     * @var Upload
     */
    private $entity;

    public function setUp(): void
    {
        parent::setUp();

        $this->entity = Upload::create(['path' => '/foo/bar']);
    }

    /** @test */
    public function it_creates_an_inactive_link_by_default()
    {
        $link = (new ShareableLinkBuilder($this->entity))->build();

        $this->assertFalse($link->isActive());
    }

    /** @test */
    public function creating_an_active_link()
    {
        $link = (new ShareableLinkBuilder($this->entity))
            ->setActive()
            ->build();

        $this->assertTrue($link->isActive());
    }

    /** @test */
    public function creates_a_link_which_is_not_password_protected_by_default()
    {
        $link = (new ShareableLinkBuilder($this->entity))->build();

        $this->assertFalse($link->requiresPassword());
    }

    /** @test */
    public function creating_a_password_protected_link()
    {
        $link = (new ShareableLinkBuilder($this->entity))
            ->setPassword('super-secret')
            ->build();

        $this->assertTrue($link->requiresPassword());
    }

    /** @test */
    public function it_creates_a_link_without_expiration_date_by_default()
    {
        $link = (new ShareableLinkBuilder($this->entity))->build();

        $this->assertNull($link->expires_at);
    }

    /** @test */
    public function creating_a_link_with_an_expiration_date()
    {
        $expiresAt = Carbon::parse('2017-05-26 13:00:00');

        $link = (new ShareableLinkBuilder($this->entity))
            ->setExpirationDate($expiresAt)
            ->build();

        $this->assertTrue($expiresAt->eq($link->expires_at));
    }

    /** @test */
    public function use_prefix_to_build_url()
    {
        $link = (new ShareableLinkBuilder($this->entity))
            ->setPrefix('foo')
            ->build();

        $this->assertStringContainsString('/shared/foo', $link->url);
    }

    /** @test */
    public function it_does_not_notify_by_default()
    {
        $link = (new ShareableLinkBuilder($this->entity))
            ->build();

        $this->assertFalse($link->shouldNotify());
    }

    /** @test */
    public function build_a_link_that_notifies_on_visit()
    {
         $link = (new ShareableLinkBuilder($this->entity))
             ->notifyOnVisit()
             ->build();

        $this->assertTrue($link->shouldNotify());
    }

    /** @test */
    public function it_is_possible_to_override_the_base_url()
    {
        config(['shareable-model.base_url' => '/foobar']);

        $link = (new ShareableLinkBuilder($this->entity))->build();

        $this->assertStringContainsString('/foobar', $link->url);
    }
}
