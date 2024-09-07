<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Enrollment;
use App\Models\ProgramEdition;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'postal_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->randomNumber(9),
            'birth_place' => $this->faker->city,
            'nationality' => 'PORTUGAL',
            'current_job_title' => $this->faker->jobTitle,
            'current_company_id' => Company::factory(),
            'leader_id' => User::factory(),
        ];
    }

    public function withCitizenInformation()
    {
        return $this->state(fn (array $attributes) => [
            'citizen_id' => $this->faker->randomNumber(8),
            'citizen_id_validity' => now()->addYears(10),
        ]);
    }

    public function withoutCitizenInformation()
    {
        return $this->state(fn (array $attributes) => []);
    }

    public function withProgramEditions(int $count)
    {
        return $this
            ->afterCreating(function (Student $student) use ($count) {
                $programEditions = ProgramEdition::factory($count)->create([
                    'company_id' => $student->current_company_id,
                ]);

                $programEditions->each->enroll($student);

                $student->setRelation('enrolledProgramEditions', $programEditions);
                $student->setRelation('enrollments', $programEditions->map(function (ProgramEdition $programEdition) {
                    return new Enrollment([
                        'program_edition_id' => $programEdition->id,
                        'company_id' => $programEdition->company_id,
                    ]);
                }));
            })
            ->afterMaking(function (Student $student) use ($count) {
                $programEditions = ProgramEdition::factory($count)->create([
                    'company_id' => $student->current_company_id,
                ]);

                $student->setRelation('enrolledProgramEditions', $programEditions);
                $student->setRelation('enrollments', $programEditions->map(function (ProgramEdition $programEdition) {
                    return new Enrollment([
                        'program_edition_id' => $programEdition->id,
                        'company_id' => $programEdition->company_id,
                    ]);
                }));
            });
    }

    public function withoutProgramEditions()
    {
        return $this->state(fn (array $attributes) => []);
    }
}
