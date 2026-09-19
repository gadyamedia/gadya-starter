<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->publishDocument(config('site'));
    }

    public function test_the_published_pages_render_in_the_site_layout(): void
    {
        $home = config('site.pages.home');

        $this->get('/')->assertOk()->assertSee($home['heading'])->assertSee('<gadya-built-by', false);
        $this->get('/pricing')->assertOk();
        $this->get('/a-page-that-does-not-exist')->assertNotFound();
    }

    public function test_articles_search_and_the_sitemap_use_the_site(): void
    {
        $this->get('/blog')->assertOk();
        $this->get('/search?q=party')->assertOk();
        $this->get('/sitemap.xml')->assertOk();
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap:');
    }

    public function test_only_people_who_work_on_the_site_get_into_the_admin(): void
    {
        $editor = User::factory()->create();
        $editor->forceFill(['role' => 'editor'])->save();

        $customer = User::factory()->create();
        $customer->forceFill(['role' => 'customer'])->save();

        $this->actingAs($editor)->get('/admin')->assertOk();
        $this->actingAs($customer)->get('/admin')->assertForbidden();
    }

    public function test_the_role_can_never_be_mass_assigned(): void
    {
        $user = User::query()->create(['name' => 'Eve', 'email' => 'eve@example.com', 'password' => 'secret-password', 'role' => 'admin']);

        $this->assertNotSame('admin', $user->fresh()->role);
    }
}
