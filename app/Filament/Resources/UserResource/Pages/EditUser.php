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
use App\Models\CustomerList;
use App\Models\User;
use App\Models\Customer;

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

        $original = $this->getRecord();

        if ($original->customer_id !== $data['customer_id']) {

            // 2. Fetch the new customer details from the source list
            $customerApi = CustomerList::find($data['customer_id']);

            if ($customerApi) {
                // 3. Update the existing link or create a new one
                Customer::updateOrCreate(
                    // Match criteria: Look for a record belonging to this User
                    ['user_id' => $original->id],

                    // Values to sync
                    [
                        'name'       => $customerApi->dname,
                        'icris'      => trim($customerApi->icris),
                        'code'       => $customerApi->xcus,
                        'created_by' => Auth::user()->id,
                        // 'created_at' is handled automatically by Laravel's timestamps
                    ]
                );
            }
        }

        // dd(count($user[0]['customer']));
        if ($data['is_active']) {
            $data['failed_login_attempts'] = 0;
        }



        return $data;
    }
}
