<?php

namespace App\Filament\App\Pages;

use App\Models\Term;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Pages\Page;

class TermsCondition extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.terms-condition';
    protected static ?int $navigationSort=7;

    public $data;

    public function getHeading(): string
    {
        return '';
    }
    public function mount(): void
    {
        $this->model=Term::all()->toArray();


        if(count($this->model)>0)
            $this->form->fill(['description'=>$this->model[0]['description']]);
        else
            $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                RichEditor::make('description')->label("")
                    ->toolbarButtons([

                    ]),
                // ...
            ])
            ->statePath('data');

    }
}
