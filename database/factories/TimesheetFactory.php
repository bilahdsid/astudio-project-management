<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timesheet>
 */
class TimesheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'project_id' => Project::factory(),
            'task_name'  => $this->faker->sentence(4),
            'date'       => $this->faker->date,
            'hours'      => $this->faker->randomFloat(2, 1, 8),
        ];
    }
}
