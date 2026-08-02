<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    #[DataProvider('publicPages')]
    public function test_guests_can_visit_public_pages(string $uri, string $component): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component($component));
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function publicPages(): array
    {
        return [
            'home' => ['/', 'Home'],
            'work' => ['/werk', 'Work/Index'],
            'websites' => ['/diensten/websites', 'Services/Websites'],
            'dashboards' => ['/diensten/dashboards', 'Services/Dashboards'],
            'webshops' => ['/diensten/webshops', 'Services/Webshops'],
            'about' => ['/over-mij', 'About'],
            'contact' => ['/contact', 'Contact'],
            'privacy' => ['/privacy', 'Privacy'],
        ];
    }
}
