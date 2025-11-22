<x-filament-panels::page >
  <x-filament::fieldset>
    <x-slot name="label" text="lg">
      Customer Details
    </x-slot>
    <div class="p-2 space-y-2">
        <div class="grid grid-cols-12 gap-2">
          <div class="col-span-3 col-start-1">
            <label  class="text-sm font-medium  block mb-1">Customer Name</label>
            <div class="custom-div" >{{$data['xorg']}}</div>
          </div>
          <div class="col-span-6">
            <label  class="text-sm font-medium  block mb-1">Address 1</label>
            <div class="custom-div" >{{$data['xadd1']}}</div>
          </div>
          <div class="col-span-3">
            <label  class="text-sm font-medium  block mb-1">Address 2</label>
            <div class="custom-div" >{{$data['xadd2']}}</div>
          </div>
        </div>
      <div class="grid grid-cols-12 gap-2">
        <div class="col-span-3 col-start-1">
          <label  class="text-sm font-medium  block mb-1">City</label>
          <div class="custom-div" >{{$data['xcity']}}</div>
        </div>
        <div class="col-span-2">
          <label  class="text-sm font-medium  block mb-1">Postal Code</label>
          <div class="custom-div" >{{$data['xzip']}}</div>
        </div>
        <div class="col-span-3">
          <label  class="text-sm font-medium  block mb-1">TAX Number</label>
          <div class="custom-div" >{{$data['xtaxnum']}}</div>
        </div>
        <div class="col-span-3">
          <label  class="text-sm font-medium  block mb-1">BIN </label>
          <div class="custom-div" >{{$data['xlicense']}}</div>
        </div>

      </div>
{{--      <div class="grid grid-cols-12 gap-2">--}}
{{--        <div class="col-span-2 col-start-1">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Industry</label>--}}
{{--          <div class="custom-div" >{{$data['xsic']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-2">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Territory</label>--}}
{{--          <div class="custom-div" >{{$data['xtr']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-4">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Sales Person</label>--}}
{{--          <div class="custom-div" >{{(count($data['fasalesperson'])>0)?$data['fasalesperson']['xname']:''}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-4">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Credit Control Person</label>--}}
{{--          <div class="custom-div" >{{(count($data['faccperson'])>0)?$data['faccperson']['xname']:''}}</div>--}}
{{--        </div>--}}

{{--      </div>--}}
{{--      <div class="grid grid-cols-12 gap-2">--}}
{{--        <div class="col-span-2 col-start-1">--}}
{{--          <label  class="text-sm font-medium  block mb-1">TAX Number</label>--}}
{{--          <div class="custom-div" >{{$data['xtaxnum']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-2">--}}
{{--          <label  class="text-sm font-medium  block mb-1">BIN </label>--}}
{{--          <div class="custom-div" >{{$data['xlicense']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-2">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Export</label>--}}
{{--          <div class="custom-div" >{{($data['export']==1)?'Yes':'No'}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-2">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Import </label>--}}
{{--          <div class="custom-div" >{{($data['import']==1)?'Yes':'No'}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-2">--}}
{{--          <label  class="text-sm font-medium  block mb-1">CnF </label>--}}
{{--          <div class="custom-div" >{{($data['cnf']==1)?'Yes':'No'}}</div>--}}
{{--        </div>--}}
{{--      </div>--}}
    </div>
  </x-filament::fieldset>
  <x-filament::fieldset>
    <x-slot name="label" text="lg">
      ICRIS Number
    </x-slot>
    <div class="p-2 space-y-2">
      <div class="grid grid-cols-12 gap-2">
        <div class="col-span-6 col-start-1 text-center">
          <label  class="text-sm font-bold  block mb-1 bg-gray-100 p-2 dark:text-white dark:bg-black">Export</label>
          @foreach($eppicris as $epp_icris)
          <div class="custom-div mb-1" >{{$epp_icris}}</div>
          @endforeach

        </div>
        <div class="col-span-6 text-center">
          <label  class="text-sm font-bold  block mb-1 bg-gray-100 p-2 dark:text-white dark:bg-black">Import</label>
          @foreach($ifcicris as $ifc_icris)
            <div class="custom-div mb-1" >{{$ifc_icris}}</div>
          @endforeach
        </div>

      </div>



    </div>
  </x-filament::fieldset>

{{--  <x-filament::fieldset>--}}
{{--    <x-slot name="label" text="lg">--}}
{{--      Contact Details--}}
{{--    </x-slot>--}}
{{--    <div class="p-2 space-y-2">--}}
{{--      @foreach($contacts as $contact)--}}
{{--      <div class="grid grid-cols-12 gap-2">--}}
{{--        <div class="col-span-3 col-start-1">--}}
{{--          <label  class="text-sm font-medium  block mb-1">First Name</label>--}}
{{--          <div class="custom-div" >{{$contact['firstname']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-3">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Last Name </label>--}}
{{--          <div class="custom-div" >{{$contact['lastname']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-3">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Department</label>--}}
{{--          <div class="custom-div" >{{$contact['department']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-3">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Designations </label>--}}
{{--          <div class="custom-div" >{{$contact['designations']}}</div>--}}
{{--        </div>--}}
{{--      </div>--}}

{{--      <div class="grid grid-cols-12 gap-2">--}}
{{--        <div class="col-span-3 col-start-1">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Primary email</label>--}}
{{--          <div class="custom-div" >{{$contact['email']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-3">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Secondary email </label>--}}
{{--          <div class="custom-div" >{{$contact['emailother']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-2">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Primary Phone</label>--}}
{{--          <div class="custom-div" >{{$contact['phone1']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-2">--}}
{{--          <label  class="text-sm font-medium  block mb-1">Secondary Phone</label>--}}
{{--          <div class="custom-div" >{{$contact['phone2']}}</div>--}}
{{--        </div>--}}
{{--        <div class="col-span-2">--}}
{{--          <label  class="text-sm font-medium  block mb-1">National ID</label>--}}
{{--          <div class="custom-div" >{{$contact['nid']}}</div>--}}
{{--        </div>--}}

{{--      </div>--}}
{{--        <hr class="py-0.5 my-8 bg-gray-400 border-0 dark:bg-gray-700">--}}
{{--      @endforeach--}}
{{--    </div>--}}
{{--  </x-filament::fieldset>--}}
</x-filament-panels::page>
