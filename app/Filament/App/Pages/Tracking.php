<?php

namespace App\Filament\App\Pages;

use App\Http\Controllers\AccountController;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Tracking extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static string $view = 'filament.app.pages.tracking';
    protected static ?int $navigationSort=2;
    public function getHeading(): string
    {
        return '';
    }

    public $tracking;
    public $trackingReesult=null;
    public $view_dws=null;
    public $view_dms=null;

    public function searchTracking(): void
    {
        // dd($this->tracking);
        $this->view_dws=Auth::user()->view_dws;
        $this->view_dms=Auth::user()->view_dms;

        $this->trackingReesult=[];
        $acc=new AccountController();
        $res=$acc->getTracking($this->tracking);
       
        if(is_array($res) && count($res)>0) {
            $this->trackingReesult = $res;
        }

    }
}
