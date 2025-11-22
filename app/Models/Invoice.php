<?php

namespace App\Models;

use App\Http\Controllers\AccountController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Sushi\Sushi;


class Invoice extends Model
{

    use Sushi;

    protected $guarded = [];
    
    public static ?string $currentInvoiceNumber = null;




    public function getRows(): array
    {
        $arr=[];
        $inv=new AccountController();
        $arr=$inv->getInvoiceDetails(self::$currentInvoiceNumber);


        $cnt=0;


        foreach($arr as $value)
        {
//            $arr[$cnt]['child']="";
//            if($value['xnum']>1) {
//                if (isset($value['faexppackage'])) {
//                    $pksstring = "";
//                    foreach ($value['faexppackage'] as $ch) {
//                        $pksstring .= $ch['xeco'] . ", ";
//                    }
//                    $arr[$cnt]['child'] = $pksstring;
//                }
//                else if (isset($value['faimppackage'])) {
//                    $pksstring = "";
//                    foreach ($value['faimppackage'] as $ch) {
//                        $pksstring .= $ch['xeco'] . ", ";
//                    }
//                    $arr[$cnt]['child'] = $pksstring;
//                }
//            }

            unset($arr[$cnt]['faexppackage']);
            unset($arr[$cnt]['faimppackage']);
            unset($arr[$cnt]['faexpshipment']);

            $cnt++;

        }



        return $arr;
    }







}
