<?php

namespace Tests\Feature\ProgramEditions;

use App\Console\Commands\NotifyDueProgramEditionEvaluations;
use App\Mail\DuedProgramEditionEvaluation;
use App\Models\ProgramEdition;
use App\Models\Student;
use App\Models\User;
use Cron\CronExpression;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        $programEdition = ProgramEdition::factory()->create([
            'evaluation_notification_date' => today(),
        ]);
        $student1 = Student::factory()->create([
            'leader_id' => auth()->user()->id,
        ]);
        $student2 = Student::factory()->create([
            'leader_id' => User::factory()->create()->id,
        ]);
        $programEdition->enroll($student1);
        $programEdition->enroll($student2);

        $this->artisan(NotifyDueProgramEditionEvaluations::class);

        Mail::assertSent(DuedProgramEditionEvaluation::class, function ($mail) use ($student1, $student2) {
            return $mail->hasTo($student1->leader->email)
                && $mail->hasTo($student2->leader->email);
        });
    }

    /** @test */
    public function the_notification_is_only_triggered_on_the_due_date()
    {
        Mail::fake();

        ProgramEdition::factory()->withStudents(1)->create([
            'evaluation_notification_date' => today()->subDay(),
        ]);

        $this->artisan(NotifyDueProgramEditionEvaluations::class);

        Mail::assertNothingSent();
    }

    /** @test */
    public function the_notification_is_scheduled_to_run_daily()
    {
        $cronExpression = collect(resolve(Schedule::class)
                ->events())
                ->first(fn (Event $event) => Str::contains($event->command, ['program_editions:notify_evaluations']))
                ->expression;

        $this->assertTrue(CronExpression::isValidExpression($cronExpression));
        $this->assertTrue(Str::endsWith($cronExpression, [' * * *']));
    }
}
