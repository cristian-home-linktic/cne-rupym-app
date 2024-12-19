<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User **/
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<mixed>|Arrayable<array-key,mixed>|\JsonSerializable
     */
    public function toArray(Request $request)
    {
        return parent::toArray($request);
    }
}
