<?php

namespace App\Filament\App\Pages;

use App\Models\Invoice;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ISPSListPage extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation=false;

    protected static string $view = 'filament.app.pages.i-s-p-s-list-page';

    public function getHeading(): string
    {
        return '';
    }

    public static function getNavigationLabel(): string
    {
        return 'DWS';
    }

    public function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('xpbdocno')->label('HAWB'),
                TextColumn::make('xdate')->label('Date')->date('Y-m-d'),
             //   TextColumn::make('xglref')->label('Invoice No')->searchable(),


            ])->query(Invoice::query())
            ->actions([
                // Define the custom action
                Action::make('view')
                    ->label('view')
                    ->icon('heroicon-o-eye')  // Icon (optional)
                    ->color('danger')  // Red color (optional)
                    ->url(fn (Invoice $record): string => route('viewinvoice', ['id' =>$record['xglref']]))
                    ->openUrlInNewTab()

            ])->filters(
                [
                    SelectFilter::make('xpbbztype')->label('Movement')
                        ->options([
                            'Export' => 'Export',
                            'Import' => 'Import',

                        ]),
                    Filter::make('mupd')
                        ->label('Created Date Range')
                        ->form([
                            // Start Date Picker
                            \Filament\Forms\Components\DatePicker::make('start_date')
                                ->label('Start Date') ->native(false)
                                ->default(now()->subMonths(3)),
                            // End Date Picker
                            \Filament\Forms\Components\DatePicker::make('end_date')
                                ->label('End Date') ->native(false)
                                ->default(now()),
                        ])->columns(2)
                        ->query(function (Builder $query, array $data): void {
                            // Apply date range filter to the query
                            if (isset($data['start_date']) && isset($data['end_date'])) {
                                $query->whereBetween('mupd', [
                                    $data['start_date'],  // Start date
                                    $data['end_date'],    // End date
                                ]);
                            }
                        }),

                ],layout: FiltersLayout::AboveContent
            );


    }
}
