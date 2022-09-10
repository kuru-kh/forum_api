<?php

namespace App\Repository;

use App\Models\Forum;
use Illuminate\Database\Eloquent\Collection;

class ForumRepository
{
    public function getAll(string $type = 'approved', string $search = '') : Collection
    {
        $data = Forum::with(['user'])->when($search, function($query) use($search) {
            $query->where('title', 'LIKE', '%' . $search . '%')
                ->orWhereHas('user', function ($query) use($search){
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
        })
       
        ->when($type == 'approved', function($query) {
            $query->where('is_approved', 1);
        })
        ->when($type == 'unapproved', function($query) {
            $query->where('is_approved', 0);
        })
        ->get();

        return $data;
    }

    public function get(int $id) : Forum | null
    {
        return Forum::with('user', 'comments')->where('id', $id)->first();
    }

    public function create(array $data) : Forum
    {
        return Forum::create($data);
    }

    public function update(int $id, array $data) : Forum | array
    {
        $forum = $this->get($id);
        if (empty($forum)) {
            return ['error' => 'Forum not found'];
        }
        $forum->update($data);

        return $forum;
    }

    public function delete(int $user_id, int $id) : array
    {
        $forum = $this->get($id);
        if (empty($forum) || $forum->user_id !=  $user_id) {
            return ['error' => 'Forum not found'];
        }
        $forum->delete();

        return ['success' => 'Deleted successfully'];
    }
}