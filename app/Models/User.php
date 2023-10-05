<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionHistory extends Model
{
    protected $table = 'history';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define other relationships as needed
}
