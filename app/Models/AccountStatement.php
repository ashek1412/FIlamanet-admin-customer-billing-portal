<?php

namespace App\Models;

use App\Http\Controllers\AccountController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Sushi\Sushi;


class AccountStatement extends Model
{

    use Sushi;

    protected $guarded = [];



    public function getRows(): array
    {
        // $arr=[];
        //   $inv=new AccountController();
        //  $arr=$inv->getInvoiceList();
        $userId = Auth::id();

        return Cache::remember("statement_acc_{$userId}", now()->addMinutes(30), function () {
            $acController = new AccountController();
            $this->cus_code = $acController->getAccount();

            if(!isset( $this->cus_code['xcus']))
                return [];

            $this->cus_code = $this->cus_code['xcus'];

            $actColor = new AccountController();

            $data = $actColor->getDynamicsData($this->cus_code);


            if (!empty($data)) {
                return array_map(function ($item) {
                    unset($item['@odata.etag']);
                   // dd($item);
                    return $item;
                }, $data);
            }
            return [];
        });
    }

    protected function sushiShouldCache(): bool
    {
        return false;
    }
}
