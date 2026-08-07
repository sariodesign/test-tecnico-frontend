<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'app_name' => $this->resource['app_name'],
            'description' => $this->resource['description'],
            'favicon' => $this->resource['favicon'],
            'logo' => $this->resource['logo'],
            'contact_email' => $this->resource['contact_email'],
            'social' => $this->resource['social'],
        ];
    }
}
