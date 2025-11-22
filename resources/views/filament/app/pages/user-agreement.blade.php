<x-filament-panels::page>
  <div class="space-y-4">
    {{$this->form}}
  </div>
  <x-filament-panels::form wire:submit="submit"  >
    <div class="w-full text-center">
      <x-filament::button type="submit" size="sm" wire:submit="save">
        Submit
      </x-filament::button>
    </div>

  </x-filament-panels::form>
</x-filament-panels::page>
