<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthlogResource\Pages;
use App\Models\AuthLog;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;

class AuthlogResource extends Resource
{
    protected static ?string $model = AuthLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Access Log';
    protected static ?string $title = 'Access Log';
    protected static ?string $navigationGroup = 'Logs';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('authenticatable_id')
                    ->label('User')
                    ->relationship(name: 'user', titleAttribute: 'email')->disabled(),
                TextInput::make('login_at')
                    ->label('Login At'),
                Toggle::make('login_successful')
                    ->label('Login Successful'),
                TextInput::make('logout_at')
                    ->label('Logout At'),
                TextInput::make('ip_address')
                    ->label('IP Address'),
                TextInput::make('user_agent')
                    ->label('User Agent'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('login_at')->sortable()->searchable(),
                TextColumn::make('user.email')->sortable()->searchable(isIndividual: true),
                BooleanColumn::make('login_successful')->sortable(),
                TextColumn::make('logout_at')->sortable()->searchable(),
                TextColumn::make('ip_address')->sortable()->searchable(),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->label('Email'),
                TernaryFilter::make('login_successful')
                    ->label('Login Success')
                    ->trueLabel('Successful')
                    ->falseLabel('Failed'),
                Filter::make('login_at')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('login_at', '>=', $date))
                            ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('login_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

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
            'index' => Pages\ListAuthlogs::route('/'),

            'view' => Pages\ViewAuthlog::route('/{record}'),
        ];
    }
}
