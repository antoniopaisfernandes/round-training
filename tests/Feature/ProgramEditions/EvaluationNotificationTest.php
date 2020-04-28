<?php

namespace Tests\Feature\ProgramEditions;

use App\Console\Commands\NotifyDueProgramEditionEvaluations;
use App\Mail\DuedProgramEditionEvaluation;
use App\ProgramEdition;
use App\Student;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EvaluationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->createAdminUser());
    }

    /** @test */
    public function it_notifies_student_leaders_of_dued_program_edition_evaluations()
    {
        Mail::fake();

        $programEdition = factory(ProgramEdition::class)->create([
            'evaluation_notification_date' => today()->subDay(),
        ]);
        $student1 = factory(Student::class)->create([
            'leader_id' => auth()->user()->id,
        ]);
        $student2 = factory(Student::class)->create([
            'leader_id' => factory(User::class)->create()->id,
        ]);
        $programEdition->enroll($student1);
        $programEdition->enroll($student2);

        $this->artisan(NotifyDueProgramEditionEvaluations::class);

        Mail::assertSent(DuedProgramEditionEvaluation::class, function ($mail) use ($student1, $student2) {
            return $mail->hasTo($student1->leader->email)
                && $mail->hasTo($student2->leader->email);
        });
    }
}
