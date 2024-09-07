<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courseNames = [
            'Anatomy',
            'Anesthesiology',
            'Behavioral Sciences',
            'Biochemistry',
            'Community Medicine/Public Health',
            'Dermatology',
            'Embryology',
            'Emergency Medicine',
            'Family Medicine',
            'Forensic Medicine',
            'Genetics',
            'Geriatrics',
            'Histology',
            'Immunology',
            'Infectious Diseases',
            'Internal Medicine',
            'Medical Ethics and Law',
            'Medical Informatics',
            'Medical Research and Evidence-Based Medicine',
            'Microbiology',
            'Neurology',
            'Nutrition',
            'Obstetrics and Gynecology',
            'Oncology',
            'Ophthalmology',
            'Orthopedics',
            'Otorhinolaryngology',
            'Palliative Care',
            'Pathology',
            'Pediatrics',
            'Pharmacology',
            'Physiology',
            'Psychiatry',
            'Radiology',
            'Surgery',
        ];

        return [
            'name' => $this->faker->randomElement($courseNames).' '.mt_rand(1, 9999),
        ];
    }
}
