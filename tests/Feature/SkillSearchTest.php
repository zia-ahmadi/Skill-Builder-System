<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_skills_page_renders_searchable_skills_for_the_authenticated_user(): void
    {
        $user = User::factory()->create();
        $skill = $user->skills()->create([
            'name' => 'Laravel',
            'goal_hours' => 10,
            'description' => 'Build web applications',
        ]);
        $anotherUser = User::factory()->create();
        $anotherUser->skills()->create([
            'name' => 'Private skill',
            'goal_hours' => 5,
        ]);

        $response = $this->actingAs($user)->get(route('skills.index'));

        $response->assertOk()
            ->assertSee('Search skills')
            ->assertSee('data-skills-search-input', false)
            ->assertSee('data-skill-card', false)
            ->assertSee('data-search="laravel build web applications"', false)
            ->assertSee($skill->name)
            ->assertDontSee('Private skill');
    }
}
