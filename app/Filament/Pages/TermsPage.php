<?php

namespace App\Filament\Pages;

use App\Models\Term;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
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
        $term = Term::first();

        if ($term) {
            $this->form->fill(['description' => $term->description]);
        } else {
            $this->form->fill(['description' => '']);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                RichEditor::make('description')
                    ->label('Terms and Conditions')
                    ->required()
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    // ✅ Add form actions (save button)
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Terms & Conditions')
                ->action('save')
                ->color('primary'),
        ];
    }

    // ✅ Rename to 'save' to match the action
    public function save(): void
    {
        try {
            $formData = $this->form->getState();

            // Use updateOrCreate for cleaner code
            Term::updateOrCreate(
                ['id' => Term::first()?->id],
                ['description' => $formData['description']]
            );

            Notification::make()
                ->title('Saved successfully')
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title('Failed to save')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
