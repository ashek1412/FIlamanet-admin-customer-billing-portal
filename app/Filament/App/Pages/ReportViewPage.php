<?php

namespace App\Filament\App\Pages;

use App\Http\Controllers\AccountController;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Response;

class ReportViewPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $layout = 'filament.layouts.base';
    protected static string $view = 'filament.app.pages.report-view-page';

    protected static bool $shouldRegisterNavigation = false;

    public $rpturl;
    public function mount($id=null)
    {
        try {

            $id=request()->query('id');

                                   $inv=new AccountController();
                       $this->rpturl=$inv->getInvoice($id);



        } catch (Halt $exception) {
            dd($exception->getMessage());
        }
    }

    public function getRoute(): string
    {
        return 'report-view-page/{id}'; // Define your custom parameters here
    }

    public function getBreadcrumb(): ?string
    {
        return null;
    }


    public function getHeading(): string
    {
        return '';
    }
}
