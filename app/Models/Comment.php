<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'post_id',
        'user_id'
    ];

    public static function getComments($where = [])
    {
        $query = self::select(
            'comments.*',
            'posts.title AS post_title',
            'users.name AS user_name',
            'users.email AS user_email'
        )
            ->leftJoin('posts', 'posts.id', '=', 'comments.post_id')
            ->leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->where($where)
            ->orderBy('comments.id', 'DESC')
            ->get();

        return $query;
    }
}
