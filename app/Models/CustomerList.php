<?php

namespace App\Models;

use App\Http\Controllers\AccountController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class CustomerList extends Model
{
    use HasFactory, Sushi;
    protected $guarded = [];
    protected $table = "customer_list";

    protected $schema = [
        'xcus' => 'string',
        'dname' => 'string',
        'icris' => 'string',
        'details' => 'string',
        'id' => 'integer',
    ];

    public function getRows(): array
    {
        $arr = [];
        $inv = new AccountController();
        $arr = $inv->getCustomerList();




        $cnt = 0;
        foreach ($arr as $value) {
            $arr[$cnt]['details'] =  $value['xcus'] . "-" . $value['dname'] . " (" . trim($value['icris'] . ")");
            $cnt++;
        }

        return $arr;
    }

    protected function sushiShouldCache(): bool
    {
        return false;
    }
}
