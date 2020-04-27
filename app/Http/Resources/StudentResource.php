<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            $this->mergeWhen(auth()->user()->can('rgpd'), [
                'citizen_id' => $this->citizen_id,
                'citizen_id_validity' => $this->citizen_id_validity,
            ]),
            'email' => $this->email,
            'phone' => $this->phone,
            'birth_place' => $this->birth_place,
            'nationality' => $this->nationality,
            'current_job_title' => $this->current_job_title,
            'current_company_id' => $this->current_company_id,
            'company' => $this->company,
            'leader_id' => $this->leader_id,
            'leader' => $this->whenLoaded('leader', $this->leader),
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'enrollments' => $this->whenLoaded('enrollments', $this->enrollments),
            'enrolled_program_editions' => $this->whenLoaded('enrolledProgramEditions', $this->enrolledProgramEditions),
        ];
    }
}
