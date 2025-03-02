@section('title', $pageTitle)
<x-app-layout>
  <div class="card mb-3">
    <div class="card-body flex flex-col p-6">
      <div class="card-text h-full space-y-4">
        {!! Helper::getNotice('page_deposit_invoice') !!}
      </div>
    </div>
  </div>
  <div id="app">
    <deposit-index />
  </div>

  @push('scripts')
    @vite('resources/js/modules/account/deposit/index.js')
  @endpush
</x-app-layout>
