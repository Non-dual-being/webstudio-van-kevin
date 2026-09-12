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

    public function test_education_case_uses_the_central_project_data(): void
    {
        $this->get('/werk/onderwijsformulier')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Work/Show')
                ->where('auth.user', null)
                ->where('project', config('projects.onderwijsformulier'))
                ->where('project.title', 'Onderwijsformulier')
                ->missing('project.demoUrl'));
    }

    public function test_home_and_work_share_the_education_project(): void
    {
        $project = config('projects.onderwijsformulier');

        $this->get('/')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->where('featuredProject', $project));

        $this->get('/werk')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Work/Index')
                ->has('projects', 1)
                ->where('projects.0', $project));
    }

    public function test_unknown_project_slugs_return_not_found(): void
    {
        $this->get('/werk/onbekend-project')->assertNotFound();
        $this->get('/werk/onderwijsformulier.title')->assertNotFound();
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function publicPages(): array
    {
        return [
            'home' => ['/', 'Home'],
            'work' => ['/werk', 'Work/Index'],
            'education form' => ['/werk/onderwijsformulier', 'Work/Show'],
            'services' => ['/diensten', 'Services/Index'],
            'websites' => ['/diensten/websites', 'Services/Websites'],
            'dashboards' => ['/diensten/dashboards', 'Services/Dashboards'],
            'webshops' => ['/diensten/webshops', 'Services/Webshops'],
            'about' => ['/over-mij', 'About'],
            'contact' => ['/contact', 'Contact'],
            'privacy' => ['/privacy', 'Privacy'],
        ];
    }
}
