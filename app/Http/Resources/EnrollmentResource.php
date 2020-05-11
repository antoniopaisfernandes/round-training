<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'program_edition_id' => $this->program_edition_id,
            'program_edition' => ProgramEditionResource::make($this->whenLoaded('programEdition')),
            'student_id' => $this->student_id,
            'student' => StudentResource::make($this->whenLoaded('student')),
            'company_id' => $this->company_id,
            'company' => CompanyResource::make($this->whenLoaded('company')),
            'hours_attended' => $this->hours_attended,
            'global_evaluation' => $this->global_evaluation,
            'evaluation_comments' => $this->evaluation_comments,
            'program_should_be_repeated' => $this->program_should_be_repeated,
            'should_be_repeated_in_months' => $this->should_be_repeated_in_months,
        ];
    }
}
