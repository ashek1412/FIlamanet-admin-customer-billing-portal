<?php

namespace App\Filament\App\Pages;

use App\Models\Faq;

use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

class FaqPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static string $view = 'filament.app.pages.faq-page';
    protected static ?int $navigationSort=7;
    public $data=null;


    public static function getNavigationLabel(): string
    {
        return 'FAQ';
    }

    public function getHeading(): string
    {
        return 'Frequently asked questions';
    }



    public function mount(): void
    {
        try {
            $this->data=Faq::all()->toArray();
            //dd($this->data);
        } catch (Halt $exception) {
            dd($exception->getMessage());
        }
    }


}
