<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => \App\Models\Project::factory()->create()->id,
            'task_name' => $this->faker->word(20),
            'priority' => $this->faker->numberBetween(0,3),
        ];
    }
}
