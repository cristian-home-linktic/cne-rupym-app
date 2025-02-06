<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<mixed>|Arrayable<array-key,mixed>|\JsonSerializable
     */
    public function toArray(Request $request)
    {
        return parent::toArray($request);
    }
}
