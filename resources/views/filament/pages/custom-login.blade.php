<x-layouts.auth>
  <div class="w-full max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <div class="mb-6 text-center">
      <img src="{{ asset('images/logo.png') }}" class="h-12 mx-auto mb-4" alt="Logo">
      <h1 class="text-2xl font-bold">Welcome Back</h1>
      <p class="text-sm text-gray-500">Login to your admin account</p>
    </div>

    <form wire:submit.prevent="authenticate" class="space-y-4">
      {{ $this->form }}

      <button type="submit"
              class="w-full py-2 font-semibold text-white bg-primary-600 rounded hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
        Login
      </button>
    </form>

    <div class="mt-4 text-center text-xs text-gray-400">
      &copy; {{ date('Y') }} Your Company. All rights reserved.
    </div>
  </div>
</x-layouts.auth>
