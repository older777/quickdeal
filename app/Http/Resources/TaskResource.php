<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'manager' => $this->manager ? $this->manager->name : null,
            'name' => $this->name,
            'comment' => $this->comment,
            'status' => $this->status,
            'created' => Carbon::make($this->created_at)->format('Y.m.d H:i:s'),
        ];
    }
}
