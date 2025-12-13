<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use app\Http\Resources\CommissionResource;

class CommissionResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rules' => CommissionRuleResource::collection($this->resource,CommissionResource::class),
        ];
    }
}
