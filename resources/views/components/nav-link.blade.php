@props(['active'=>false,'type'=>'a'])

@php
    // if($active==True)
    //     $active = FAlse  
@endphp
{{-- @if($type=='a') --}}
<a {{ $attributes }} class=" {{ $active ? 'bg-gray-900 text-white':'text-gray-300'}} hover:bg-gray-700 hover:text-white rounded-md px-3 py-2"
    aria-current={{$active ? 'page':'false'}}>
    {{ $slot }}
</a>
{{-- @else
<button {{ $attributes }} class=" {{ $active ? 'bg-gray-900 text-white':'text-gray-300'}} hover:bg-gray-700 hover:text-white rounded-md px-3 py-2"
    aria-current={{$active ? 'page':'false'}}>
    {{ $slot }}
</button>
@endif --}}