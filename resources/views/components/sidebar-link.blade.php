@props(['active' => false, 'type' => 'a'])
@if ($type === 'a')
<a class="{{$active ? 'nav-link active' : 'nav-link'}}" aria-current="{{ $active ? 'page' : 'false' }}" {{ $attributes }}>{{$slot}}</a>
@else
<a class="{{$active ? 'dropdown-item active' : 'dropdown-item'}}" aria-current="{{ $active ? 'page' : 'false' }}" {{ $attributes }}>{{$slot}}</a>
@endif
    

