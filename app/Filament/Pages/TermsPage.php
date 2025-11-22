<?php

namespace App\Filament\Pages;

use App\Models\Term;
use Exception;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class TermsPage extends Page implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Settings';

    protected static string $view = 'filament.pages.terms';

    public ?array $data = [];
    protected $model=[];

    public function getHeading(): string
    {
        return '';
    }

    public static function getNavigationLabel(): string
    {
        return 'Terms & Conditions';
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

                RichEditor::make('description')->label("Terms and Conditions"),
                // ...
            ])
            ->statePath('data');

    }


    public function updateForm(): void
    {
        try {
            $formvalues = $this->form->getState();
            if (count($this->model) > 0) {

                Term::where('id', $this->model[0]['id'])->first()?->update($formvalues);
            } else {
                Term::create($formvalues);
            }

            Notification::make()->title('Saved successfully')->success()->send();
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
            Notification::make()->title('Failed')->danger()->send();
        }

    }

}
