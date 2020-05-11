<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramEditionResource extends JsonResource
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
            'program_id' => $this->program_id,
            'name' => $this->name,
            'company_id' => $this->company_id,
            'cost' => $this->cost,
            'supplier' => $this->supplier,
            'supplier_certifications' => $this->supplier_certifications,
            'teacher_name' => $this->teacher_name,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'location' => $this->location,
            'goals' => $this->goals,
            'evaluation_notification_date' => $this->evaluation_notification_date,
            'created_by' => $this->created_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'students_count' => $this->students_count,
            'program' => $this->whenLoaded('program', $this->program),
            'company' => CompanyResource::make($this->whenLoaded('company')),
            'schedules' => $this->whenLoaded('schedules', $this->schedules),
            'manager' => UserResource::make($this->whenLoaded('manager')),
            'enrollments' => EnrollmentCollectionResource::make($this->whenLoaded('enrollments')),
            'students' => StudentCollectionResource::make($this->whenLoaded('students')),
        ];
    }
}
