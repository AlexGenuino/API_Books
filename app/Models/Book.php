<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'books';

    protected $fillable = [
        'id_google'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'users_books');

    }
}
