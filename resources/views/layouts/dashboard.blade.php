<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  @stack('token')

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('media/logo/favicon.ico') }}" />

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" rel="stylesheet">

  <!-- Metronic Styles -->
  <link rel="stylesheet" href="{{ asset('plugins/global/plugins.bundle.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.bundle.css') }}">
  @stack('styles')

  <!-- Scripts -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
  data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
  data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true"
  data-kt-app-toolbar-enabled="true" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on"
  class="app-default">
  <!--begin::Theme mode setup on page load-->
  <script>
    var defaultThemeMode = "light";
    var themeMode;
    if (document.documentElement) {
      if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
        themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
      } else {
        if (localStorage.getItem("data-bs-theme") !== null) {
          themeMode = localStorage.getItem("data-bs-theme");
        } else {
          themeMode = defaultThemeMode;
        }
      }
      if (themeMode === "system") {
        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
      }
      document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
  </script>

  {{-- <x-atoms.page-loader /> --}}

  <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
      {{-- Header --}}
      @include('partials.header')

      {{-- Container --}}
      <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

        @include('partials.sidebar')
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
          <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="py-3 app-toolbar py-lg-6">
              <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="flex-wrap page-title d-flex flex-column justify-content-center me-3">
                  <h1 class="my-0 page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center">
                    {{ $title }}</h1>
                  <x-atoms.breadcrumb />
                </div>
              </div>
            </div>

            <div id="kt_app_content" class="app-content flex-column-fluid">
              <div id="kt_app_content_container" class="app-container container-xxl">
                @yield('content')
              </div>
            </div>
          </div>

          @include('partials.footer')
        </div>

      </div>

    </div>
  </div>

  <script src="{{ asset('plugins/global/plugins.bundle.js') }}"></script>
  <script src="{{ asset('js/scripts.bundle.js') }}"></script>
  @stack('scripts')
</body>

</html>
