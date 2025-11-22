<x-filament-panels::page>
   <div class="w-full max-w-6xl mx-auto px-4 md:px-6 py-6">
         <div class="divide-y divide-slate-200">
        @foreach($data as $val)

        <!-- Accordion item -->
        <div x-data="{ expanded: false }" class="py-2">
          <h2>
            <button
              id="faqs-title-{{$val['id']}}"
              type="button"
              class="flex items-center justify-between w-full text-left font-semibold p-2 text-red-800 bg-gray-100 dark:bg-gray-700 dark:text-red-200"
              @click="expanded = !expanded"
              :aria-expanded="expanded"
              aria-controls="faqs-text-{{$val['id']}}"
            >
              <span>{{$val['title']}}</span>
              <svg class="fill-indigo-500 shrink-0 ml-8" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                <rect y="7" width="16" height="2" rx="1" class="transform origin-center transition duration-200 ease-out" :class="{'!rotate-180': expanded}" />
                <rect y="7" width="16" height="2" rx="1" class="transform origin-center rotate-90 transition duration-200 ease-out" :class="{'!rotate-180': expanded}" />
              </svg>
            </button>
          </h2>
          <div
            id="faqs-text-{{$val['id']}}"
            role="region"
            aria-labelledby="faqs-title-{{$val['id']}}"
            class="grid text-sm text-black overflow-hidden transition-all duration-300 ease-in-out"
            :class="expanded ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'"
          >
            <div class="overflow-hidden px-2 dark:bg-gray-700 dark:text-white">
              <p class="dark:bg-gray-700 dark:text-white">
                {!! $val['description'] !!}
              </p>
            </div>
          </div>
        </div>

        @endforeach

      </div>
    </div>
    <!-- Accordion item -->
</x-filament-panels::page>
