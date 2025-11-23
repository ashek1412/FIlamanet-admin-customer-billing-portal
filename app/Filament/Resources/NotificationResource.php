<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\User;
use App\Models\UserNotification;
use App\Notifications\CustomNotification;
use Exception;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;



class NotificationResource extends Resource
{
    protected static ?string $model = UserNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationLabel = 'Notifications';

    protected static ?int $navigationSort=4;



    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
       return $infolist
           ->schema([
               TextEntry::make('title')->label('Title')
                   //->color(Color::rgb('rgb(165, 42, 42)'))
               ->extraAttributes([ 'style' => 'padding: 8px; border-radius: 4px;'])
               ,
               TextEntry::make('description')
                   ->extraAttributes([ 'style' => 'padding: 8px; border-radius: 4px;']),
               TextEntry::make('created_at')->dateTime('Y-m-d H:i'),
           ])->columns(1);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('type')
                    ->label('Notification Type')
                    ->default("info")
                   ->disabled()
                    ->required(),
                TextInput::make('title')
                    ->label('Notification Title')
                    ->required(),
                Textarea::make('description')
                    ->label('Notification Description')
                    ->required(),
               Hidden::make('created_by')->default(Auth::user()->id)
            ])->columns(1);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Notification Saved';
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
               // TextColumn::make('type')->label('Type')->sortable(),
                TextColumn::make('title')->label('Title')->limit(50),
                TextColumn::make('description')->label('Description')->limit(150),

                IconColumn::make('status')->visible(Auth::user()->is_admin)
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'sent' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                         'pending' => 'warning',
                        'sent' => 'success',
                        default => 'grey',
                    }),

                TextColumn::make('created_at')->label('Sent at')->dateTime(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (!Auth::user()->is_admin) {
                    return $query->where('status', 'sent');
                }
            })

            ->filters([
                //
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('primary'),
                Tables\Actions\EditAction::make()->visible(Auth::user()->is_admin),
                Tables\Actions\DeleteAction::make()->visible(Auth::user()->is_admin),
               // Tables\Actions\ViewAction::make(),
                Action::make('sendNotification')->visible(function ($record) {
                   return (Auth::user()->is_admin && $record->status=="pending");
                }) // Custom action
                ->label('Send')
                    ->action(function ($record)
                    {
                        try {


                            $users = User::all();
                            $cnt=0;
                            foreach ($users as $user) {
                                $title = $record->title;

                                $link = url('/app/notifications/'. $record->id.'/edit');
                                if($user->is_admin)
                                    $link = url('/admin/notifications/'. $record->id.'/edit');


                                $user->notify(  Notification::make()
                                    ->title($title)
                                    ->body(  $record->description)
                                    ->actions(
                                        [
                                        \Filament\Notifications\Actions\Action::make('view')
                                            ->button()
                                            ->url($link, shouldOpenInNewTab: false)
                                        ]
                                    )->toDatabase());

                                $cnt++;

                                 UserNotification::where('id', $record->id)->first()?->update(['status' => "sent"]);

                            }


                        }
                        catch(Exception $ex)
                        {

                        }
                        // Optionally, show a success message
                        Notification::make()
                            ->title('Notification Sent')
                            ->body('A notification has been sent to ' . $cnt.' users')
                            ->success()
                            ->send();
                    })
                    ->icon('heroicon-o-bell')
                    ->color(Color:: Green)
                    ->requiresConfirmation()
                    ->modalHeading('Send Notification')




            ]);
    }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
            'view' => Pages\ViewNotification::route('/{record}'),

        ];
    }
}
