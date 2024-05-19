<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChildResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userData = $this->user->only(['name', 'mobile_number', 'user_name', 'account_type', 'address', 'is_active']);

        return [
            'name' => $userData['name'],
            'mobile_number' => $userData['mobile_number'],
            'user_name' => $userData['user_name'],
            'account_type' => $userData['account_type'],
            'address' => $userData['address'],
            'child' => [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'birthdate' => $this->birthdate,
                'date_of_join' => $this->date_of_join,
                'date_of_exit' => $this->date_of_exit,
                'starting_disease' => $this->starting_disease,
                'healing_date' => $this->healing_date,
                'disease_type' => $this->disease_type,
                'note' => $this->note,
                'image' => $this->image ? asset('storage/' . $this->image) : null,
            ]
        ];
    }
}
