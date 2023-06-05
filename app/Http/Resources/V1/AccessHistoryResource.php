<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AccessHistoryResource extends JsonResource
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
            'access_code_id' => $this->access_code_id,
            'visitor' => $this->visitor,
            'access_at' => $this->municipality
        ];
    }
}
