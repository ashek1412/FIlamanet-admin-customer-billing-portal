<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\logoutResponse as BaseLogoutResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class LogoutResponse extends BaseLogoutResponse
{
    public function toResponse($request): RedirectResponse
    {
        Cache::delete('account_info');

        if(Filament::getCurrentPanel()->getId()==='admin') {


            return redirect()->to(Filament::getLoginUrl());
        }

        return parent::toResponse($request);
    }
}
