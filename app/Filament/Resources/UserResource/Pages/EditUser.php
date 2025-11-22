<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Concerns\HasPreview;
use App\Filament\Resources\PostResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditUser extends EditRecord
{
    use HasPreview, HasPreviewModal;

    /**
     * The resource model.
     */
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Modify the data array

        //dd( $data);

        if($data['is_active'])
        {
            $data['failed_login_attempts']=0;

        }



        return $data;
    }
}
