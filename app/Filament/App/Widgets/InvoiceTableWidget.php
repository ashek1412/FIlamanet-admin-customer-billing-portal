<?php

namespace App\Filament\App\Widgets;

use App\Http\Controllers\AccountController;
use App\Models\Invoice;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Reactive;

class InvoiceTableWidget extends BaseWidget
{
    #[Reactive]
    public ?string $invno = null;

    protected int | string | array $columnSpan = 'full';


    public function table(Table $table): Table
    {
        $type = $this->invno ? substr($this->invno, 0, 3) : '';
        
        // Clear Sushi cache and set the invoice number on the Invoice model before querying
        Invoice::clearBootedModels();
        Invoice::$currentInvoiceNumber = $this->invno;
        
        return $table
            ->query(Invoice::query())
            ->columns([

                TextColumn::make('xdate')->label('date')->date("Y-m-d")->searchable()->sortable(),
                TextColumn::make('xpbdocno')->label('tracking')->searchable(),
            //    TextColumn::make('xadd')->label('shipper'),
//                TextColumn::make('xadd')->label('consignee'),
                TextColumn::make('xcountry')->label('lane')->searchable()->sortable(),
                TextColumn::make('xpbdoctype')->label('package')->searchable()->sortable(),
                TextColumn::make('xnum')->label('pcs')->searchable()->sortable()->sortable(),
                TextColumn::make('xpbwtf')->label('weight')->searchable()->numeric('2')->suffix('Kg')->sortable()
            ])->actions([
                // Define the custom action
                Action::make('view')
                    ->visible(Auth::user()->view_dws && !str_contains($type, 'CNF'))
                    ->label('DWS')
                    ->icon('heroicon-o-eye')  // Icon (optional)
                    ->color('primary')  // Red color (optional)
                    ->url(fn (Invoice $record): string => route('viewisps', ['id' =>$record['xpbdocno']]))

                    ->openUrlInNewTab(),

                Action::make('view')
                    ->visible(Auth::user()->view_dms)
                    ->label('AWB Scan')
                //    ->visible( str_contains(Session::get('cur_invno'),'CNF-') )
                    ->icon('heroicon-o-eye')  // Icon (optional)
                    ->color('primary')  // Red color (optional)
                    ->url(fn (Invoice $record): string => route('viewdms', ['id' =>$record['xpbdocno']]))
                    ->openUrlInNewTab(),
                Action::make('view')
                    ->visible(str_contains($type, 'CNF') || str_contains($type, 'EFD'))
                    ->label('Docs')
                    ->icon('heroicon-o-eye')  // Icon (optional)
                    ->color('primary')  // Red color (optional)
                    ->url(fn (Invoice $record): string => route('viewdms', ['id' =>$type.'-'.$record['xpbdocno']]))
                    ->openUrlInNewTab(),


            ])

            ->heading($this->invno ?? 'Invoice Details');
    }



    protected function getColumns(): int | array
    {
        return 4;
    }








}
