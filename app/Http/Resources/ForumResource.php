<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'is_approved' => $this->is_approved,
            'user'=> $this->user->name ?? '',
            'user_id'=> $this->user_id,
            'posted' => Carbon::parse($this->created_at)->diffForHumans(),
            'comments' => CommentResource::collection($this->comments)
        ];
    }
}
