<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionHistory extends Model
{
    protected $table = 'session_history';

    protected $fillable = ['user_id', 'session_id', 'score', 'date'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

}
