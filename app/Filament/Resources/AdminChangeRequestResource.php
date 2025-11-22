<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminChangeRequestResource\Pages;
use App\Filament\Resources\AdminChangeRequestResource\RelationManagers;
use App\Models\AdminChangeRequest;
use App\Models\ChangeRequest;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminChangeRequestResource extends Resource
{
    protected static ?string $model = ChangeRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->required(),
                TextInput::make('remarks')->visible(auth()->user()->is_admin),
                Select::make('status')
                    ->options([
                        'rejected' => 'Reject',
                        'complete' => 'Complete',
                        'submitted' => 'Submitted',
                    ])->visible(auth()->user()->is_admin)

            ])->columns(1);     //

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('subject')->sortable()->searchable(),
                TextColumn::make('description')->limit(50)->html(),
                TextColumn::make('status')->sortable()->searchable()->color('info'),
                TextColumn::make('created_at')->dateTime(),
                TextColumn::make('user.email')->label('Created by'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ViewAction::make()->color('primary')
            ])
;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdminChangeRequests::route('/'),
            'create' => Pages\CreateAdminChangeRequest::route('/create'),
            'edit' => Pages\EditAdminChangeRequest::route('/{record}/edit'),
        ];
    }
}
