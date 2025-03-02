@section('title', $meta_seo['title'] ?? $pageTitle)
@section('keywords', $meta_seo['keywords'] ?? '')
@section('description', $group->descr ?? '')
<x-app-layout meta-seo="ocean">
  <section>
    <div class="text-center mb-5">
      <h1 class="text-[20px] md:text-[30px] mb-1">{{ __('Tài Khoản') }} - {{ $group->name }}</h1>
      <div class="h-[3px] bg-primary w-[170px] mx-auto"></div>
    </div>
    <div class="border border-primary p-3 bg-white rounded-lg mb-3">
      {!! $group->descr !!}
    </div>
    <div id="app">
      <account-index group-id="{{ $group->id }}" />
    </div>

    @if ($group->descr_seo)
      <div class="border border-primary p-3 bg-white rounded-lg mb-3 mt-5">
        {!! $group->descr_seo !!}
      </div>
    @endif
  </section>

  @push('scripts')
    @vite('resources/js/modules/store/account/index.js')
  @endpush
</x-app-layout>
