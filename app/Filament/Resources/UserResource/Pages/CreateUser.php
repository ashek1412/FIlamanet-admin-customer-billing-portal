<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Concerns\HasPreview;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use App\Models\CustomerList;
use App\Models\User;
use App\Models\Customer;

class CreateUser extends CreateRecord
{
    use HasPreview, HasPreviewModal;

    /**
     * The resource model.
     */
    protected static string $resource = UserResource::class;


    protected function afterCreate(): void
    {
        // Your custom logic here, after the record is saved
        $user = $this->getRecord();

        $customerapi = CustomerList::where('id', $user->customer_id)->first()->toArray();
        Customer::create([
            'user_id' => $user->id,
            'name' => $customerapi['dname'],
            'icris' => trim($customerapi['icris']),
            'code' => $customerapi['xcus'],
            'created_at' => now(),
            'created_by' => Auth::user()->id,
        ]);
    }
}
