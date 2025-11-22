<?php

namespace App\Models;

use App\Http\Controllers\AccountController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Sushi\Sushi;


class InvoicePackage extends Model
{

    use Sushi;

    protected $guarded = [];

}
