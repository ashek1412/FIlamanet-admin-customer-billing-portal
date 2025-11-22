@php
  $formPanelPosition = filament('filament-auth-ui-enhancer')->getFormPanelPosition();
  $mobileFormPanelPosition = filament('filament-auth-ui-enhancer')->getMobileFormPanelPosition();
  $emptyPanelBackgroundImageUrl = filament('filament-auth-ui-enhancer')->getEmptyPanelBackgroundImageUrl();
  $emptyPanelBackgroundImageOpacity = filament('filament-auth-ui-enhancer')->getEmptyPanelBackgroundImageOpacity();
  $showEmptyPanelOnMobile = filament('filament-auth-ui-enhancer')->getShowEmptyPanelOnMobile();
@endphp
<x-filament-panels::layout.base :livewire="$livewire">
  <div
    @class([
      'custom-auth-wrapper flex w-full min-h-[calc(100vh-30px)]',
      'lg:flex-row-reverse' => $formPanelPosition === 'left',
      'lg:flex-row' => $formPanelPosition === 'right',
      'flex-col' => $mobileFormPanelPosition === 'bottom' && $showEmptyPanelOnMobile,
      'flex-col-reverse' => $mobileFormPanelPosition === 'top' && $showEmptyPanelOnMobile,
    ])
  >
    <!-- Empty Container -->
    <div @class([
            'custom-auth-empty-panel relative justify-center px-4',
            'bg-[var(--empty-panel-background-color)]',
            'hidden lg:flex lg:flex-col lg:flex-grow' => $showEmptyPanelOnMobile === false,
            'flex flex-col flex-grow' => $showEmptyPanelOnMobile === true
            ])
    >
      @if($emptyPanelBackgroundImageUrl)
        <div class="absolute inset-0 h-full w-full bg-cover bg-center"
             style="background-image: url('{{ $emptyPanelBackgroundImageUrl }}'); opacity: {{ $emptyPanelBackgroundImageOpacity }}; background-position: center;">
        </div>
      @endif
    </div>

    <!-- Form Container -->
    <div class="custom-auth-form-panel flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-10 xl:px-20 w-full lg:w-[var(--form-panel-width)] bg-[var(--form-panel-background-color)]">
      <div class="custom-auth-form-wrapper mx-auto w-full max-w-sm">
        {{ $slot }}
      </div>
    </div>
  </div>
  <footer class="bg-black">
    <div class="w-full mx-auto max-w-screen-xl px-2 py-2 justify-center text-white text-sm text-center">

      Copyright © {{ date('Y') }} Air Alliance Limited. All rights reserved. Air Alliance Limited is the authorized service contractor of UPS and a concern of Bengal Airlift Limited


    </div>
  </footer>
</x-filament-panels::layout.base>
