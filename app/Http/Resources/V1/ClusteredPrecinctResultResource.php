<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ClusteredPrecinctResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            // 'res_id' => $this->res_id,
            'cl_id' => $this->cl_id,
            'candidate_name' => $this->candidate_name,
            'candidate_position' => $this->candidate_position,
            'total_turnout' => $this->total_turnout,
            'status' => $this->status,
            'province_name' => $this->province_name,
            'municipality_name' => $this->municipality_name,
            'barangay_name' => $this->barangay_name,
            'total_votes' => $this->total_votes,
            'total_turnout' => $this->total_turnout,
            'precincts' => $this->precincts,
            'reg_voters' => $this->reg_voters
        ];
    }
}
