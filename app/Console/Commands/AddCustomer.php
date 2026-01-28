<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerList;
use Illuminate\Support\Facades\Auth;

class AddCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('customer')->where('customer_id', '!=', null)->get()->toArray();

        foreach ($users as $key => $user) {


            if (count($user['customer']) == 0) {

                $customerapi = CustomerList::where('id', $user['customer_id'])->first();

                Customer::create([
                    'user_id' => $user['id'],
                    'name' => $customerapi['dname'],
                    'icris' => trim($customerapi['icris']),
                    'code' => $customerapi['xcus'],
                    'created_at' => now(),
                    'created_by' => 1,
                ]);

                $this->info($user['customer_id'] . " " . $customerapi['dname']);
            }
        }
    }
}
