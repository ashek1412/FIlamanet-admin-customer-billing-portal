<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\InvoiceTableWidget;
use App\Models\InvoiceList;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

use Livewire\Component;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InvoiceListPage extends Page implements HasTable
{
    use InteractsWithTable;
    public $invno;
    protected static ?string $navigationIcon = 'heroicon-o-currency-bangladeshi';
    protected static ?int $navigationSort=3;
    protected static string $view = 'filament.app.pages.invoic-list-page';

    public function getHeading(): string
    {
        return 'Invoice list';
    }

    public static function getNavigationLabel(): string
    {
        return 'Invoices';
    }

    public function mount()
    {
        $this->invno = null;
    }

    public function table(Tables\Table $table): Tables\Table
    {

        return $table
            ->columns([
                TextColumn::make('movement')->label('Movement'),
                TextColumn::make('bill_type')->label('Type'),
                TextColumn::make('name')->label('Invoice No')->searchable(),
                TextColumn::make('shipments')->label('shipments'),
                TextColumn::make('bill_cycle')->label('billing cycle'),
                TextColumn::make('from_date')->label('from')->date('Y-m-d'),
                TextColumn::make('to_date')->label('to')->date('Y-m-d'),

            ])->query(InvoiceList::query())
            ->actions([
                // Define the custom action
                Action::make('view')
                    ->label('view')
                    ->icon('heroicon-o-eye')  // Icon (optional)
                    ->color('success')  // Red color (optional)
                  ->url(fn (InvoiceList $record): string => route('viewinvoice', ['id' =>$record['name']]))
                    ->openUrlInNewTab(),
                Action::make('musak')
                    ->label('musak')
                    ->icon('heroicon-o-eye')  // Icon (optional)
                    ->color('success')  // Red color (optional)
                     ->visible(fn (Model $record): bool =>  !str_contains($record->name,'CNF-') && !str_contains($record->name,'EFD-') )
                     ->url(fn (InvoiceList $record): string => route('viewmusak', ['id' =>$record['name']]))
                     ->openUrlInNewTab(),

                Action::make('shipments')
                    ->icon('heroicon-o-eye')// Name of the action
                ->label('shipments') // Label for the action button
                  //  ->visible(fn (Model $record): bool =>  !str_contains($record->name,'CNF-') && !str_contains($record->name,'EFD-') )

                    ->action(fn ($record) => $this->loadDetails($record)), // Call the method

            ])->filters(
                    [
                SelectFilter::make('movement')->label('Movement')
                    ->options([
                        'Export' => 'Export',
                        'Import' => 'Import',

                    ]),
                        SelectFilter::make('bill_type')->label('Type')
                            ->options([
                                'EPP' => 'EPP',
                                'IFC' => 'IFC',
                                'CNF' => 'CNF',
                                'EFD' => 'EFD',



                            ]),
                        Filter::make('from_date')
                            ->label('Created Date Range')
                            ->form([
                                // Start Date Picker
                                \Filament\Forms\Components\DatePicker::make('start_date') ->seconds(false)
                                    ->label('Start Date') ->native(false)
                                    ->default(now()->startOfYear()),
                                // End Date Picker
                                \Filament\Forms\Components\DatePicker::make('end_date') ->seconds(false)
                                    ->label('End Date') ->native(false)
                                    ->default(now()),
                            ])->columns(2)
                            ->query(function (Builder $query, array $data): void {
                                // Apply date range filter to the query
                                $start=date("Y-m-d",strtotime($data['start_date']));
                                $end=date("Y-m-d",strtotime($data['end_date']));

                                if (isset($data['start_date']) && isset($data['end_date'])) {
                                    $query->where('from_date','>=',$start)
                                        ->where('to_date','<=',$end);
                               //     $query->dd();
                                }
                            }),

                        ],layout: FiltersLayout::AboveContent
            );
    }



    protected function loadDetails(Model $record): void
    {
        $this->invno = $record['name'];
    }

    protected function getFooterWidgets(): array
    {
        if ($this->invno != null) {
            return [
                InvoiceTableWidget::make(['invno' => $this->invno]),
            ];
        }
        return [];
    }




}
