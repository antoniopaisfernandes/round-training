<?php

namespace App\Console\Commands;

use App\Mail\DuedProgramEditionEvaluation;
use App\ProgramEdition;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyDueProgramEditionEvaluations extends Command
{
    protected $signature = 'program_editions:notify_evaluations';
    protected $description = 'Notify users the due evaluations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ProgramEdition::dueToEvaluate()
            ->with('enrollments')
            ->get()
            ->each(function (ProgramEdition $programEdition) {
                Mail::to($programEdition->emails_to_notify_of_due_evaluation)
                    ->send(new DuedProgramEditionEvaluation($programEdition));
            });
    }
}
