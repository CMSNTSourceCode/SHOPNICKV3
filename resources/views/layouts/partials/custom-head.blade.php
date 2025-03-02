@php
  $primaryColor = setting('primary_color', '#ff5d05');

  $ringOffsetShadow = 'var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color)';
  $ringShadow = 'var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color)';
  $defaultShadow = '0 0 #0000';
  $ringOpacity = 0.8;
  $ringOffsetWidth = '1px';
@endphp

<style>
  * {
    font-family: 'Roboto', sans-serif;
  }

  :root {
    --primary-color: {{ $primaryColor }};
  }

  .text-primary {
    color: var(--primary-color) !important;
  }

  .shadow-primary {
    --tw-ring-offset-shadow: {{ $ringOffsetShadow }};
    --tw-ring-shadow: {{ $ringShadow }};
    box-shadow: {{ $defaultShadow }} !important;
    --tw-ring-opacity: {{ $ringOpacity }};
    --tw-ring-offset-width: {{ $ringOffsetWidth }};
  }

  .btn-primary,
  .bg-primary,
  .ant-btn-primary {
    background-color: var(--primary-color) !important;
  }

  .btn-outline-primary {
    border-color: var(--primary-color) !important;
    color: var(--primary-color) !important;
  }

  .btn-primary:hover {
    box-shadow: var(--primary-color) !important;
  }

  .border-primary {
    border-color: var(--primary-color) !important;
  }

  .main-menu>ul>li.menu-item-has-children>ul.sub-menu li a:hover {
    color: var(--primary-color) !important;
  }

  .main-menu>ul>li>a:hover {
    color: var(--primary-color) !important;
  }

  .main-menu>ul>li:hover>a .icon-box {
    color: var(--primary-color) !important;
  }

  .main-menu>ul>li:hover>a .text-box {
    color: var(--primary-color) !important;
  }

  .btn-primary:hover {
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--primary-color);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000) !important;
    --tw-ring-opacity: 0.8;
    --tw-ring-offset-width: 1px;
  }

  body {
    cursor: url('https://en.cursor.style/cursors/2081-watermelon-cursor.svg'), auto;
  }

  a,
  button:hover {
    cursor: url('https://en.cursor.style/pointers/2081-watermelon-pointer.svg'), auto;
  }

  ::-webkit-scrollbar {
    width: 5px;
  }

  ::-webkit-scrollbar-track {
    background: #282a38;
  }

  ::-webkit-scrollbar-thumb {
    background: var(--primary-color);
  }

  ::-webkit-scrollbar-thumb:hover {
    background: #e23388;
  }
</style>

@if (theme_config('background_color'))
  <style>
    .app-wrapper {
      background-color: {{ theme_config('background_color') }};
    }
  </style>
@elseif(theme_config('background_image'))
  <style>
    .app-wrapper {
      background-image: url('{{ theme_config('background_image') }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
  </style>
@endif
