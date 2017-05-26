<?php

namespace Sassnowski\LaravelShareableModel\Tests\Models;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;

class ShareableLinkTest extends TestCase
{
    /** @test */
    public function it_requires_a_password_if_it_has_been_set()
    {
        $link = new ShareableLink(['password' => 'super-secret']);

        $this->assertTrue($link->requiresPassword());
    }

    /** @test */
    public function it_does_not_requires_a_password_if_it_has_been_left_empty()
    {
        $link = new ShareableLink(['password' => null]);

        $this->assertFalse($link->requiresPassword());
    }

    /** @test */
    public function it_is_expired_if_the_expiration_date_is_in_the_past()
    {
        Carbon::setTestNow();

        $link = new ShareableLink(['expires_at' => Carbon::now()->subDay()]);

        $this->assertTrue($link->isExpired());
    }

    /** @test */
    public function it_is_not_expired_if_the_expiration_date_is_in_the_future()
    {
        Carbon::setTestNow();

        $link = new ShareableLink(['expires_at' => Carbon::now()->addMinute()]);

        $this->assertFalse($link->isExpired());
    }

    /** @test */
    public function it_is_not_expired_if_no_expiration_date_was_set()
    {
        $link = new ShareableLink(['expires_at' => null]);

        $this->assertFalse($link->isExpired());
    }

    /** @test */
    public function link_is_active()
    {
        $link = new ShareableLink(['active' => true]);

        $this->assertTrue($link->isActive());
    }

    /** @test */
    public function link_is_inactive()
    {
        $link = new ShareableLink(['active' => false]);

        $this->assertFalse($link->isActive());
    }
}
