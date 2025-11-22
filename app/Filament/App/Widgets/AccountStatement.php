<?php

namespace App\Filament\App\Widgets;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;



class AccountStatement extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Statement of Account';
    protected static ?string $pollingInterval = '600s';
    protected static ?string $loadingIndicator = 'Loading account statement..';
    protected static bool $isLazy = true;



    public function placeholder(): View
    {
        return view('filament.widgets.account-statement-loading');
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\AccountStatement::query()
            )
            ->columns([
                TextColumn::make('icris')->label('icris')->searchable(),
                TextColumn::make('invoiceType')->label('Invoice type')->searchable(),
                TextColumn::make('invoicePeriod')->label('Invoice period'),
                TextColumn::make('invoiceDate')->label('Invoice date'),
                TextColumn::make('dueDate')->label('Due date')->searchable()->sortable(),
                TextColumn::make('invoiceAmount')->label('Invoice amount')->searchable()->sortable()->numeric(2)->formatStateUsing(fn($state) => $this->indian_number_format($state)),
                TextColumn::make('settledAmount')->label('Settled amount')->searchable()->sortable()->numeric(2)->formatStateUsing(fn($state) => $this->indian_number_format($state)),
                TextColumn::make('dueAmount')->label('Due amount')->searchable()->sortable()->numeric(2)->formatStateUsing(fn($state) => $this->indian_number_format($state)),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Over Due' => 'danger',
                        'Paid' => 'success',
                        'Pending' => 'warning',
                        'Due' => 'danger',
                    })



            ])->filters(
                [
                    SelectFilter::make('invoiceType')->label('Invoice Type')
                        ->options([
                            'Export' => 'Export',
                            'Import' => 'Import',

                        ]),
                    SelectFilter::make('status')->label('Status')
                        ->options(function () {
                            return  \App\Models\AccountStatement::query()
                                ->orderBy('invoiceDate')
                                ->pluck('status', 'status')
                                ->toArray();
                        })->multiple()->default(['Over Due', 'Pending','Due']),
                    Filter::make('from_date')
                        ->label('Created Date Range')
                        ->form([
                            // Start Date Picker
                            \Filament\Forms\Components\DatePicker::make('start_date')->seconds(false)
                                ->label('Start Date')->native(false)
                                ->default(now()->startOfYear()),
                            // End Date Picker
                            \Filament\Forms\Components\DatePicker::make('end_date')->seconds(false)
                                ->label('End Date')->native(false)
                                ->default(now()),
                        ])->columns(2)
                        ->query(function (Builder $query, array $data): void {
                            // Apply date range filter to the query
                            $start = date("Y-m-d", strtotime($data['start_date']));
                            $end = date("Y-m-d", strtotime($data['end_date']));

                            if (isset($data['start_date']) && isset($data['end_date'])) {
                                $query->where('invoiceDate', '>=', $start)
                                    ->where('invoiceDate', '<=', $end);
                                //     $query->dd();
                            }
                        }),

                ],
                layout: FiltersLayout::AboveContentCollapsible
            )->bulkActions([
                BulkAction::make('Export PDF')
                    ->label('Export as PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Collection $records) {
                        // Generate view data
                        $pdf = Pdf::loadView('table_pdf', [
                            'records' => $records,
                        ]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'table_export.pdf');
                    })
                    ->color('primary')
                    ->requiresConfirmation(),
            ])->poll('600s')->emptyStateHeading('No records found')
            ->emptyStateDescription('No account statements match the current filters.')
            ->emptyStateIcon('heroicon-o-document-text')

        ;
    }

    function indian_number_format($num)
    {
        // return $num;
        $fullnum = $num;
        $whole = (int) $fullnum;
        $frac  = round($fullnum - $whole, 2);
        $frac = ltrim($frac, '0');
        $num = (string)$num;

        $lastThree = substr($whole, -3);
        $restUnits = substr($whole, 0, -3);

        if ($restUnits != '')
            $lastThree = ',' . $lastThree;
        // dd($fullnum."-".$restUnits." ".$frac);
        $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
        return $restUnits . $lastThree . $frac;
    }

    public function getColumns(): int | string | array
    {
        return 'full'; // or 'full', [1, 'sm' => 2, 'lg' => 3]
    }
}
