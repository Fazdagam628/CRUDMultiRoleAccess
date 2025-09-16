<?php

namespace App\Models;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'candidate_photo',
        'leader_name',
        'coleader_name',
        'vision',
        'mission',
        'no_urut'
    ];
    public function votes(){
        return $this->hasMany(Vote::class);
    }
}
