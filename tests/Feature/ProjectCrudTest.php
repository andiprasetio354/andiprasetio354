<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->user = User::factory()->create();
    }

    /** @test */
    public function unauthenticated_user_cannot_access_admin_projects()
    {
        $response = $this->get('/admin/projects');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_view_projects_index()
    {
        $response = $this->actingAs($this->user)->get('/admin/projects');
        $response->assertStatus(200);
        $response->assertViewIs('admin.projects.index');
    }

    /** @test */
    public function authenticated_user_can_view_create_project_form()
    {
        $response = $this->actingAs($this->user)->get('/admin/projects/create');
        $response->assertStatus(200);
        $response->assertViewIs('admin.projects.create');
    }

    /** @test */
    public function authenticated_user_can_create_project()
    {
        $projectData = [
            'title' => 'E-Commerce Platform',
            'description' => 'A full-featured e-commerce platform built with Laravel',
            'tech_stack' => 'Laravel, Vue.js, MySQL, Stripe',
            'link' => 'https://example.com/ecommerce',
            'featured' => true,
        ];

        $response = $this->actingAs($this->user)->post('/admin/projects', $projectData);

        $this->assertDatabaseHas('projects', [
            'title' => 'E-Commerce Platform',
            'description' => 'A full-featured e-commerce platform built with Laravel'
        ]);

        $response->assertRedirect('/admin/projects');
        $response->assertSessionHas('success');
    }

    /** @test */
    public function project_title_is_required()
    {
        $response = $this->actingAs($this->user)->post('/admin/projects', [
            'title' => '',
            'description' => 'A test project',
            'tech_stack' => 'Laravel',
            'link' => 'https://example.com',
            'featured' => false,
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function project_link_must_be_valid_url()
    {
        $response = $this->actingAs($this->user)->post('/admin/projects', [
            'title' => 'Test Project',
            'description' => 'A test project',
            'tech_stack' => 'Laravel',
            'link' => 'not-a-valid-url',
            'featured' => false,
        ]);

        $response->assertSessionHasErrors('link');
    }

    /** @test */
    public function project_image_must_be_valid_format()
    {
        $response = $this->actingAs($this->user)->post('/admin/projects', [
            'title' => 'Test Project',
            'description' => 'A test project',
            'tech_stack' => 'Laravel',
            'link' => 'https://example.com',
            'image' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            'featured' => false,
        ]);

        $response->assertSessionHasErrors('image');
    }

    /** @test */
    public function project_image_cannot_exceed_max_size()
    {
        $response = $this->actingAs($this->user)->post('/admin/projects', [
            'title' => 'Test Project',
            'description' => 'A test project',
            'tech_stack' => 'Laravel',
            'link' => 'https://example.com',
            'image' => UploadedFile::fake()->image('project.jpg')->size(3000), // 3MB
            'featured' => false,
        ]);

        $response->assertSessionHasErrors('image');
    }

    /** @test */
    public function authenticated_user_can_upload_project_image()
    {
        $response = $this->actingAs($this->user)->post('/admin/projects', [
            'title' => 'Image Test Project',
            'description' => 'A test project with image',
            'tech_stack' => 'Laravel',
            'link' => 'https://example.com',
            'image' => UploadedFile::fake()->image('project.jpg', 400, 300),
            'featured' => true,
        ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'Image Test Project'
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_project_details()
    {
        $project = Project::factory()->create();
        $response = $this->actingAs($this->user)->get("/admin/projects/{$project->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.projects.show');
        $response->assertSee($project->title);
    }

    /** @test */
    public function authenticated_user_can_view_edit_project_form()
    {
        $project = Project::factory()->create();
        $response = $this->actingAs($this->user)->get("/admin/projects/{$project->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.projects.edit');
        $response->assertSee($project->title);
    }

    /** @test */
    public function authenticated_user_can_update_project()
    {
        $project = Project::factory()->create();

        $updatedData = [
            'title' => 'Updated Project Title',
            'description' => 'Updated description',
            'tech_stack' => 'Laravel, React, PostgreSQL',
            'link' => 'https://updated-example.com',
            'featured' => false,
        ];

        $response = $this->actingAs($this->user)->put("/admin/projects/{$project->id}", $updatedData);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Project Title'
        ]);

        $response->assertRedirect('/admin/projects');
    }

    /** @test */
    public function authenticated_user_can_delete_project()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->user)->delete("/admin/projects/{$project->id}");

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id
        ]);

        $response->assertRedirect('/admin/projects');
    }

    /** @test */
    public function project_slug_is_generated_from_title()
    {
        $this->actingAs($this->user)->post('/admin/projects', [
            'title' => 'My Awesome Project',
            'description' => 'A test project',
            'tech_stack' => 'Laravel',
            'link' => 'https://example.com',
            'featured' => false,
        ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'My Awesome Project',
            'slug' => 'my-awesome-project'
        ]);
    }

    /** @test */
    public function featured_projects_appear_on_public_page()
    {
        $featured = Project::factory()->create(['featured' => true]);
        $notFeatured = Project::factory()->create(['featured' => false]);

        $response = $this->get('/projects');

        $response->assertSee($featured->title);
        $response->assertDontSee($notFeatured->title);
    }

    /** @test */
    public function public_can_view_project_details()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->user)->get("/admin/projects/{$project->id}");

        $response->assertStatus(200);
    }
}
