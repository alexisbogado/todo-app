<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'users',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'owner',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'member_count',
    ];

    public function getMemberCountAttribute()
    {
        return $this->users->count();
    }

    public function getIsMemberAttribute()
    {
        return auth()->user() ? 
            $this->users()
            ->where('user_id', auth()->user()->id)
            ->get()
            ->count() > 0 :
            false;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->hasMany(UserBoard::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)
            ->orderBy('order');
    }
}
