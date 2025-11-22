<?php

namespace App\Http\Responses;
use App\Filament\App\Pages\Dashboard;
use App\Filament\App\Pages\UserAgreement;
use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends BaseLoginResponse
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): RedirectResponse|Redirector
    {

            if (Auth::user()->is_admin) {
                return redirect()->to(Dashboard::getUrl(panel: 'admin'));
            } else {
                if (Auth::user()->is_agreed != 1) {
                    return redirect()->to(UserAgreement::getUrl());
                }
                return redirect()->to(Dashboard::getUrl(panel: 'app'));
            }

           //   return redirect()->to($request);
    }
}
