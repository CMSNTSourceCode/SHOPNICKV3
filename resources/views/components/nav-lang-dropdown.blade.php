{{-- Currently, selected lang --}}
@php
  $currentLang = app()->getLocale();
  $currentLangFlag = config('app.available_locales')[$currentLang];
  $availableLocales = config('app.available_locales');
@endphp

<div class="leading-0 relative">
  {{-- <button @click="listView = !listView" class="inline-flex items-center rounded-lg text-center text-sm font-medium text-slate-800 focus:outline-none focus:ring-0 dark:text-white" type="button" data-bs-toggle="dropdown"
    aria-expanded="false">
    <iconify-icon class="mr-2 text-lg" icon="emojione:flag-for-{{ $currentLangFlag }}"></iconify-icon>
    <span class="dropdown-option hidden">{{ strtoupper($currentLang) }}</span>
  </button>
  <div class="dropdown-menu !top-[25px] z-10 hidden w-20 divide-y divide-slate-100 overflow-hidden rounded-md border bg-white shadow dark:border-slate-900 dark:bg-slate-800">
    <ul class="py-1 text-sm text-slate-800 dark:text-slate-200" :class="listView ? 'z-20 opacity-100 top-[55px]' : 'opacity-0 -z-20 top-5'" x-show="listView" @click.away="listView = false">
      @foreach ($availableLocales as $locale => $flag)
        <li>
          <a href="{{ route('set-locale', ['locale' => $locale]) }}"
            class="{{ $currentLang == $locale ? 'bg-slate-100 dark:bg-slate-800' : '' }} flex items-center px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
            <iconify-icon class="mt-1 text-lg" icon="emojione:flag-for-{{ $flag }}">
            </iconify-icon>
            <span class="dropdown-option ml-2">{{ strtoupper($locale) }}</span>
          </a>
        </li>
      @endforeach
    </ul>
  </div> --}}
  <div class="gtranslate_wrapper"></div>
</div>
