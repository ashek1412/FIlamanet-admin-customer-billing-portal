<x-filament-panels::page>

  <div class="flex items-center space-x-4 justify-center">
    <label for="username" class="text-sm font-medium text-gray-700  dark:bg-black dark:text-white">Search Tracking : </label>
    <input type="text" wire:model="tracking"
           class="ml-2 mr-2 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500  dark:bg-gray-700 dark:text-white" >
    <x-filament::button type="button" size="sm" wire:click="searchTracking">
      Search
    </x-filament::button>

  </div>
  <div class="text-sm flex items-center space-x-4 justify-center">
    @if((is_array($trackingReesult) && count($trackingReesult)>0))
      <table class="w-auto divide-y divide-gray-200 dark:bg-gray-700 dark:text-white">
        <thead class="bg-gray-100 text-slate-800">
        <tr class="divide-x divide-gray-200 dark:bg-gray-700 dark:text-white">
          <th class="px-1 py-2">Tracking no</th>
          <th class="px-1 py-2">Shipment Date</th>
          <th class="px-1 py-2">Movement</th>
          @if($view_dws)
          <th class="px-1 py-2">DWS</th>
          @endif
          @if($view_dms)
          <th class="px-1 py-2">AWB scan</th>
          @endif
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white text-slate-800">
        <tr class="divide-x divide-gray-200 dark:bg-gray-700 dark:text-white">
          <td class="px-4 py-2">{{$trackingReesult['xpbdocno']}}</td>
          <td class="px-4 py-2">{{\Carbon\Carbon::parse($trackingReesult['xdate'])->toDateString()}}</td>
          <td class="px-4 py-2">{{$trackingReesult['Movement']}}</td>
          @if($view_dws)
          <td class="px-4 py-2">

            <x-filament::button type="button" size="sm" onclick="window.open('{{url('view-isps/'.$trackingReesult['xpbdocno'])}}', '_blank')">
              View
            </x-filament::button>


          </td>
          @endif
          @if($view_dms)
          <td class="px-4 py-2">

            <x-filament::button type="button" size="sm" onclick="window.open('{{url('view-dms/'.$trackingReesult['xpbdocno'])}}', '_blank')">
              View
            </x-filament::button>

          </td>
          @endif
        </tr>
        </tbody>
      </table>
    @else
      @if(is_array($trackingReesult))
        <span class="inline-flex items-center rounded-md bg-transparent px-2 py-2 text-sm font-medium text-red-800 ring-1 ring-inset ring-red-600/10 dark:text-red-300">Not tracking information found</span>
      @endif
    @endif
  </div>

</x-filament-panels::page>

