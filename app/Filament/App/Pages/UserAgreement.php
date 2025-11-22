<?php
namespace App\Filament\App\Pages;
use App\Models\Term;
use App\Models\User;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class UserAgreement extends Page implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    public $data;
    protected static string $layout = 'filament.layouts.base';
    protected static bool $shouldRegisterNavigation=false;


    protected static string $view = 'filament.app.pages.user-agreement';

    public function getBreadcrumb(): ?string
    {
        return null;
    }


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

    public function submit()
    {


           User::where('id', Auth::user()->id)->first()?->update(["is_agreed" => 1]);
         return redirect()->to(\App\Filament\App\Pages\Dashboard::getUrl(panel: 'app'));
    }

}
