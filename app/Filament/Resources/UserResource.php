<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\CustomerList;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    /**
     * The resource model.
     */
    protected static ?string $model = User::class;

    /**
     * The resource navigation icon.
     */
    protected static ?string $navigationIcon = 'heroicon-o-users';

    /**
     * The settings navigation group.
     */
    protected static ?string $navigationGroup = 'Settings';

    /**
     * The settings navigation sort order.
     */
    protected static ?int $navigationSort = 1;

    /**
     * Get the navigation badge for the resource.
     */
    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }

    /**
     * The resource form.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->password()
                    ->confirmed()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirm password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                Checkbox::make('is_admin')->live(),
                Checkbox::make('view_dws')->label('DWS copy'),
                Checkbox::make('view_dms')->label('DMS copy'),
                Toggle::make('is_active')
                    ->label('Active Status')
                    ->inline(false) ->onColor('success')
                    ->offColor('danger'),
                Forms\Components\Select::make('customer_id')
                    ->requiredIf('is_admin', false)
                    ->label('Customer')
                    ->options(CustomerList::all()->pluck('details', 'id'))
                    ->getSearchResultsUsing(fn (string $search): array => CustomerList::where('details', 'like', "%{$search}%")
                        ->orWhere('icris', 'like', "%{$search}%")->limit(50)->pluck('details', 'id')->toArray())
                    ->getOptionLabelUsing(fn ($value): ?string => CustomerList::find($value)?->details)
                    ->loadingMessage('Loading customers...')
                    ->searchPrompt('Search by their name or icris')
                    ->searchable(),



            ]);
    }

    /**
     * The resource table.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.details')->searchable()->limit(30)
                   ,
                IconColumn::make('is_active')
                    ->icon(fn (string $state): string => match ($state) {
                        '0' => 'heroicon-o-x-circle',
                        '1' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                        default => 'grey',
                    }),
                IconColumn::make('view_dws')->label('DWS copy')
                    ->icon(fn (string $state): string => match ($state) {
                        '0' => 'heroicon-o-x-circle',
                        '1' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                        default => 'grey',
                    }),
                IconColumn::make('view_dms')->label('DMS')
                    ->icon(fn (string $state): string => match ($state) {
                        '0' => 'heroicon-o-x-circle',
                        '1' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                        default => 'grey',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        //dd($data);
                        $data['user_id'] = auth()->id();

                        if( $data['is_admin'])
                        {
                            $data['customer_id']=null;
                            $data['is_admin']=1;
                        }
                        else
                        {
                            $data['is_admin']=0;
                        }


                        return $data;
                    }) ->successNotificationTitle('User updated')
                ,
                Tables\Actions\DeleteAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {

                        $data['user_id'] = auth()->id();

                        if( $data['is_admin'])
                        {
                            $data['customer_id']=null;
                            $data['is_admin']=1;
                        }
                        else
                        {
                            $data['is_admin']=0;
                        }


                        return $data;
                    }) ->successNotificationTitle('User Created'),
            ]);
    }

    /**
     * The resource relation managers.
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * The resource pages.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            //'agreement' => \App\Filament\Pages\UserAgreement::route('/agreement'),
        ];
    }


}
