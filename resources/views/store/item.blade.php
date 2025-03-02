@section('title', $pageTitle)
<x-app-layout>
  <section>
    <div class="text-center mb-5">
      <h1 class="text-[20px] md:text-[30px] mb-1 ">{{ __('Vật Phẩm') }} - {{ $group->name }}</h1>
      <div class="h-[3px] bg-primary w-[170px] mx-auto"></div>
    </div>
    <div class="alert alert-danger mb-3">
      {!! $group->descr !!}
    </div>
    <div id="app">
      <item-index group-id="{{ $group->id }}" />
    </div>
  </section>

  @push('scripts')
    @vite('resources/js/modules/store/item/index.js')
  @endpush
</x-app-layout>
