@props(['pageTitle' => 'Default Title', 'postTitle' => null, 'meta_seo' => null])

<!-- Dev By CMSNT.CO | FB.COM/CMSNT.CO | ZALO.ME/0947838128 | MMO Solution -->
<!-- Version: 1.0.1-p1 -->
<!-- Dev Status: Stable -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="light layout-boxed null nav-floating horizontalMenu">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @hasSection('description')
    <meta name="description" content="@yield('description')">
  @else
    <meta name="description" content="{{ setting('description') }}">
  @endif
  @hasSection('keywords')
    <meta name="keywords" content="@yield('keywords')">
  @else
    <meta name="keywords" content="{{ setting('keywords') }}">
  @endif
  <meta name="author" content="{{ setting('author') }}">
  <meta name="robots" content="index, follow">
  <meta name="googlebot" content="index, follow">
  <meta name="google" content="notranslate">
  <meta name="generator" content="{{ strtoupper($_SERVER['HTTP_HOST']) }}">

  <meta name="application-name" content="{{ setting('title') }}">
  <meta property="og:image" content="{{ asset(setting('favicon')) }}">
  <meta property="og:image:secure_url" content="{{ asset(setting('favicon')) }}">
  <meta property="og:image:width" content="128">
  <meta property="og:image:height" content="128">
  <meta property="og:image:alt" content="{{ setting('title') }}">
  <meta property="og:title" content="{{ setting('title') }}">
  <meta property="og:site_name" content="{{ setting('title') }}">
  <meta property="og:description" content="{{ setting('description') }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">

  <link rel="shortcut icon" href="{{ asset(setting('favicon')) }}" type="image/x-icon">

  @hasSection('postTitle')
    <title>@yield('postTitle')</title>
  @endif
  @hasSection('title')
    <title>@yield('title') - {{ setting('title') }}</title>
  @else
    @hasSection('pageTitle')
      <title>@yield('pageTitle')</title>
    @else
      <title>{{ setting('title') }}</title>
    @endif
  @endif

  <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  {{-- Scripts --}}
  <script>
    window.webData = @json([
        'csrfToken' => csrf_token(),
    ]);
    window.userData = @json(auth()->user());
  </script>

  @vite(['resources/css/app.scss', 'resources/js/custom/store.js'])

  @include('layouts.partials.custom-head')

  @stack('css')
  @yield('css')

  {!! Helper::getNotice('header_script') !!}
</head>

<body class="font-inter dashcode-app" id="body_class">
  <div class="app-wrapper">

    <!-- BEGIN: Sidebar Navigation -->
    <x-sidebar-menu />
    <!-- End: Sidebar -->

    @if (theme_config('enable_custom_theme', false))
      <!-- BEGIN: Settings -->
      <x-dashboard-settings />
      <!-- End: Settings -->
    @endif

    <div class="flex min-h-screen flex-col justify-between">
      <div>
        <!-- BEGIN: header -->
        <x-dashboard-header />
        <!-- BEGIN: header -->

        <div class="content-wrapper transition-all duration-150 ltr:ml-0 rtl:mr-0 xl:ltr:ml-[248px] xl:rtl:mr-[248px]" id="content_wrapper">
          <div class="page-content">
            <div class="container-fluid transition-all duration-150" id="page_layout">
              <main id="content_layout">
                <!-- Page Content -->
                <div class="mb-3">
                  @include('components.x-alert')
                </div>

                {{ $slot }}
              </main>
            </div>
          </div>
        </div>
      </div>

      <!-- BEGIN: footer -->
      <x-dashboard-footer />
      <!-- BEGIN: footer -->

    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>

  @vite(['resources/js/app.js', 'resources/js/main.js', 'resources/js/functions.js'])

  @stack('scripts')
  @yield('scripts')

  {!! Helper::getNotice('footer_script') !!}

  <script>
    window.gtranslateSettings = {
      "default_language": "vi",
      "native_language_names": true,
      "globe_color": "#66aaff",
      "wrapper_selector": ".gtranslate_wrapper",
      "flag_size": 28,
      "alt_flags": {
        "en": "usa"
      },
      "globe_size": 24
    }
  </script>
  <script src="https://cdn.gtranslate.net/widgets/latest/globe.js" defer></script>
</body>

</html>

<!-- Dev By CMSNT.CO | FB.COM/CMSNT.CO | ZALO.ME/0947838128 | MMO Solution -->
