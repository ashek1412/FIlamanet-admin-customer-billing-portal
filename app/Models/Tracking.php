<?php

namespace App\Models;

use App\Http\Controllers\AccountController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Sushi\Sushi;


class Tracking extends Model
{

    use Sushi;

    protected $guarded = [];



    public function getRows(): array
    {
        $arr=[];
        $inv=new AccountController();
        $arr=$inv->getInvoiceList();
        //dd($arr);
        return $arr;
    }





}
