<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Models\User;
use Modules\Core\Models\Activity;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur et l'authentifier
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_access_activities_index()
    {
        $response = $this->get(route('cores.activities.index'));

        $response->assertStatus(200);
        $response->assertViewIs('core::activities.index');
    }

    public function test_can_get_activities_data()
    {
        // Créer quelques activités
        activity()->log('test log');

        $response = $this->get(route('cores.activities.data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total',
            'rows'
        ]);
    }

    public function test_can_view_activity_detail()
    {
        $activity = activity()->log('detail test');

        $response = $this->get(route('cores.activities.show', ['id' => $activity->id]));

        $response->assertStatus(200);
        $response->assertViewIs('core::activities.show');
        $response->assertSee('detail test');
    }
}
