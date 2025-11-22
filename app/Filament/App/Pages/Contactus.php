<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

class Contactus extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.contactus';

    protected static ?int $navigationSort=8;
    public $data;

    public static function getNavigationLabel(): string
    {
        return 'Contact Us';
    }

    public function getHeading(): string
    {
        return 'Contact Us';
    }

    public function mount(): void
    {
        try {
            $this->data=\App\Models\ContactUs::all()->toArray();
            //dd($this->data);
        } catch (Halt $exception) {
            dd($exception->getMessage());
        }
    }
}
