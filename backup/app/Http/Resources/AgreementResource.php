<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgreementResource extends JsonResource
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
            'token' => $this->token,
            'client_name' => $this->client_name,
            'subject' => $this->subject,
            'initial_text' => $this->initial_text,
            'created_at' => (string) $this->created_at,
            'seen_at' => (string) $this->seen_at,
            'accepted_at' => (string) $this->accepted_at,
            'notes' => $this->notes,
            'options' => AgreementOptionResource::collection($this->options),
            'links' => [
                'show' => route('agreements.show', ['token' => $this->token]),
                'show_nomark' => route('agreements.show', ['token' => $this->token, 'nomark']),
            ]
        ];
    }
}
