<?php

namespace App\Models;

use App\Http\Controllers\AccountController;
use Illuminate\Database\Eloquent\Model;



class ChangeRequest extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
