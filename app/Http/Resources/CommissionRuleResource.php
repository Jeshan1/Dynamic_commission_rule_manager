<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommissionRuleResource extends JsonResource
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
            'origins' => $this->origins->pluck('airport_code')->map(fn($code) => ['code' => $code])->values(),
            'destinations' => $this->destinations->pluck('airport_code')->map(fn($code) => ['code' => $code])->values(),
            'rate_value' => (float) $this->rate_value,
            'rate_type' => $this->rate_type,
        ];
    }
}
