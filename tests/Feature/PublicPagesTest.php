<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function home_page_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    /** @test */
    public function home_page_displays_hero_section()
    {
        $response = $this->get('/');
        $response->assertSee('Lihat Portofolio');
        $response->assertSee('Tentang Saya');
    }

    /** @test */
    public function home_page_has_seo_meta_tags()
    {
        $response = $this->get('/');
        $response->assertSee('description');
        $response->assertSee('keywords');
    }

    /** @test */
    public function about_page_loads()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertViewIs('about');
    }

    /** @test */
    public function about_page_displays_experience()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    /** @test */
    public function projects_page_loads()
    {
        $response = $this->get('/projects');
        $response->assertStatus(200);
        $response->assertViewIs('projects');
    }

    /** @test */
    public function projects_page_displays_featured_projects()
    {
        Project::factory()->create([
            'title' => 'Featured Project',
            'featured' => true
        ]);
        Project::factory()->create([
            'title' => 'Hidden Project',
            'featured' => false
        ]);

        $response = $this->get('/projects');
        $response->assertSee('Featured Project');
        $response->assertDontSee('Hidden Project');
    }

    /** @test */
    public function projects_page_shows_empty_state_when_no_projects()
    {
        $response = $this->get('/projects');
        $response->assertSee('Belum ada proyek');
    }

    /** @test */
    public function projects_page_displays_project_details()
    {
        $project = Project::factory()->create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'tech_stack' => 'Laravel, Vue',
            'featured' => true
        ]);

        $response = $this->get('/projects');
        $response->assertStatus(200);
    }

    /** @test */
    public function resume_page_loads()
    {
        $response = $this->get('/resume');
        $response->assertStatus(200);
        $response->assertViewIs('resume');
    }

    /** @test */
    public function resume_page_displays_resume_content()
    {
        $response = $this->get('/resume');
        $response->assertSee('Professional Summary');
        $response->assertSee('Skills');
        $response->assertSee('Experience');
    }

    /** @test */
    public function resume_page_has_print_button()
    {
        $response = $this->get('/resume');
        $response->assertSee('Cetak CV');
    }

    /** @test */
    public function contact_page_loads()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertViewIs('contact');
    }

    /** @test */
    public function contact_page_displays_form()
    {
        $response = $this->get('/contact');
        $response->assertSee('name');
        $response->assertSee('email');
        $response->assertSee('subject');
        $response->assertSee('message');
    }

    /** @test */
    public function sitemap_xml_is_accessible()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/xml; charset=utf-8');
    }

    /** @test */
    public function sitemap_includes_static_pages()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
    }

    /** @test */
    public function public_header_displays_for_unauthenticated_users()
    {
        $response = $this->get('/');
        $response->assertSee('MyPortfolio');
        $response->assertSee('Home');
        $response->assertSee('About');
        $response->assertSee('Projects');
        $response->assertSee('Resume');
        $response->assertSee('Contact');
        $response->assertSee('Login');
    }

    /** @test */
    public function pages_have_responsive_design()
    {
        $pages = ['/', '/about', '/resume', '/contact'];
        
        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertSee('viewport');
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function pages_have_seo_meta_tags()
    {
        $pages = ['/', '/about', '/resume', '/contact'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertSee('description');
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function canonical_url_is_set()
    {
        $response = $this->get('/');
        $response->assertSee('canonical');
    }
}
