<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Attribute;
use App\Models\User;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\AttributeValue;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 users
        User::factory(10)->create();

        // Create 5 projects
        $projects = Project::factory(5)->create();

        // Attach 2-5 random users to each project
        $users = User::all();
        foreach ($projects as $project) {
            $project->users()->attach(
                $users->random(rand(2, 5))->pluck('id')->toArray()
            );
        }

        // Create 20 timesheets, using existing users and projects
        Timesheet::factory(20)->make()->each(function ($timesheet) use ($users, $projects) {
            $timesheet->user_id = $users->random()->id;
            $timesheet->project_id = $projects->random()->id;
            $timesheet->save();
        });

        // Create predefined attributes
        $attributesData = [
            ['name' => 'department', 'type' => 'text'],
            ['name' => 'start_date', 'type' => 'date'],
            ['name' => 'end_date', 'type' => 'date'],
        ];
        foreach ($attributesData as $data) {
            Attribute::create($data);
        }

        // Create dynamic attribute values for each project
        $attributes = Attribute::all();
        foreach ($projects as $project) {
            foreach ($attributes as $attribute) {
                $value = match ($attribute->type) {
                    'text' => 'Sample ' . ucfirst($attribute->name),
                    'date' => now()->subDays(rand(1, 30))->toDateString(),
                    'number' => rand(100, 1000),
                    'select' => 'Option' . rand(1, 3),
                    default => 'N/A',
                };

                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'entity_id'    => $project->id,
                    'value'        => $value,
                ]);
            }
        }
    }    
}
