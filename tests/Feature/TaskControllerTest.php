<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;
    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations for the test database
        $this->artisan('migrate');
        // Call the database seeder
        $this->seed();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_store_with_valid_data()
    {
        $project_data = [
            'project_name' => $this->faker->word,
            'task_name' => $this->faker->word,
            'priority' => '1',
        ];

        $response = $this->post(route('task.store'), $project_data);
        $response->assertStatus(302);
    }

    public function test_store_to_task_model_with_invalid_data()
    {
        $response = $this->post(route('task.store'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['task_name', 'priority']);
    }

    public function test_update_task_with_valid_data()
    {
        $project_data = [
            'project_name' => $this->faker->sentence,
        ];

        $project = Project::create($project_data);
        $task_data = [
            'project_id' => $project->id,
            'task_name' => $this->faker->sentence,
            'priority' => '2',
        ];
        $task = Task::factory()->create();

        $response = $this->patch(route('task.update', $task->id), $task_data);
        $response->assertStatus(302);
    }

    public function test_update_task_with_invalid_data()
    {
        $task = Task::factory()->create();
        $response = $this->patch(route('task.update', $task->id), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['task_name', 'priority']);
    }

    public function test_priorities_are_reordered_correctly()
    {
        $tasks = Task::factory()->count(4)->create();
        $priorities = [3, 1, 4, 2];
        $expectedOrder = [];
        foreach ($priorities as $index => $priority) {
            $expectedOrder[] = [$priority, $tasks[$index]->id];
        }
        $input = ['order' => json_encode($expectedOrder)];

        $response = $this->postJson(route('task.reorder'), $input);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Priorities swapped successfully',
            ]);
    }

    public function test_task_can_be_deleted()
    {
        $task = Task::factory()->create();
        $response = $this->delete(route('task.destroy', $task->id));

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Task successfully deleted.',
            ]);
        $this->assertDeleted($task);
    }

    public function test_task_deletion_error()
    {
        $invalidTaskId = 999;
        $response = $this->delete(route('task.destroy', $invalidTaskId));

        $response->assertStatus(200)
            ->assertJson([
                "response" => false,
                "status" => 404,
                "message" => 'Task not found.',
                'data' => []
            ]);
    }

}
