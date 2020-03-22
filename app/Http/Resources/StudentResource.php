<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

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
        if ($this->rgpdFields && auth()->user()->cannot('rgpd')) {
            return Arr::except(
                parent::toArray($request),
                $this->rgpdFields
            );
        }

        return parent::toArray($request);
    }
}
