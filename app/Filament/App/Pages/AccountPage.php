<?php

namespace App\Filament\App\Pages;

use App\Http\Controllers\AccountController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AccountPage extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.app.pages.account-page';

    protected static ?int $navigationSort=4;

    public ?array $data = [];

    public ?array $eppicris = [];
    public ?array $ifcicris = [];

    public ?array $contacts = [];

    public function getHeading(): string
    {
        return 'Customer Profile';
    }

    public static function getNavigationLabel(): string
    {
        return 'Profile';
    }

    public function mount(): void
    {
        try {
             $cache_key="account_info_".Auth::user()->customer_id;

            if (Cache::has($cache_key)) {

                $array = Cache::get($cache_key);
                $this->data=$array;
            }
            else
            {
                $acController=new AccountController();
                $res=$acController->getAccount();

                $this->data=$res;
                Cache::put($cache_key, $this->data, now()->addMinutes(60)); // Cache for 10 minute


            }





            if(count($this->data['customericris'])>0)
            {
                foreach ($this->data['customericris'] as $icris)
                {
                    if($icris['xproj']=="Export")
                        $this->eppicris[]=$icris['xid'];
                    else
                        $this->ifcicris[]=$icris['xid'];
                }

            }

            if(count($this->data['facusotmercontacts'])>0)
            {
                foreach ($this->data['facusotmercontacts'] as $val)
                {
                   $this->contacts[]=$val;
                }

            }




        } catch (Halt $exception) {
           dd($exception->getMessage());
        }
    }





}
