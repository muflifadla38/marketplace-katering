<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}"
  data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}"
  data-kt-sticky-animation="false">
  <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
    id="kt_app_header_container">
    <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
      <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
        <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
          <span class="path1"></span>
          <span class="path2"></span>
        </i>
      </div>
    </div>
    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
      <a href="{{ route('dashboard.index') }}" class="d-lg-none">
        <img alt="Logo" src="{{ asset('media/logo/default-small.svg') }}" class="h-30px" />
      </a>
    </div>
    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
      <!--begin::Menu wrapper-->
      <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
        data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
        data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
        data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
        <!--begin::Menu-->
        <div class="px-2 my-5 menu menu-rounded menu-column menu-lg-row my-lg-0 align-items-stretch fw-semibold px-lg-0"
          id="kt_app_header_menu" data-kt-menu="true">
          <div class="flex-wrap mr-2 d-flex align-items-center">
            <h5 class="mt-2 mb-2 text-dark font-weight-bold me-5">Dashboard</h5>
            <div class="mt-2 mb-2 bg-gray-200 subheader-separator subheader-separator-ver me-4">
            </div>
            <dib class="btn btn-light font-weight-bold me-4"><b>Render Time</b>
              : {{ round(microtime(true) - LARAVEL_START, 3) }}</dib>
          </div>
        </div>
        <!--end::Menu-->
      </div>
      <!--end::Menu wrapper-->
      <!--begin::Navbar-->
      <div class="flex-shrink-0 app-navbar">
        <!--begin::Search-->
        <div class="app-navbar-item align-items-stretch ms-2 ms-md-3">
          <!--begin::Search-->
          <div id="kt_header_search" class="header-search d-flex align-items-stretch" data-kt-search-keypress="true"
            data-kt-search-min-length="2" data-kt-search-enter="enter" data-kt-search-layout="menu"
            data-kt-menu-trigger="auto" data-kt-menu-overflow="false" data-kt-menu-permanent="true"
            data-kt-menu-placement="bottom-end">
            <!--begin::Search toggle-->
            <div class="d-flex align-items-center" data-kt-search-element="toggle" id="kt_header_search_toggle">
              <div
                class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px">
                <i class="ki-duotone ki-magnifier fs-2 fs-lg-1">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </div>
            </div>
            <!--end::Search toggle-->
            <!--begin::Menu-->
            <div data-kt-search-element="content" class="p-4 menu menu-sub menu-sub-dropdown w-325px w-md-375px">
              <!--begin::Wrapper-->
              <div data-kt-search-element="wrapper">
                <!--begin::Form-->
                <form data-kt-search-element="form" class="w-100 position-relative" autocomplete="off">
                  <!--begin::Icon-->
                  <i
                    class="text-gray-500 ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-0">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  <!--end::Icon-->
                  <!--begin::Input-->
                  <input type="text" class="search-input form-control form-control-flush ps-10" name="search"
                    value="" placeholder="Search..." data-kt-search-element="input" />
                  <!--end::Input-->
                  <!--begin::Spinner-->
                  <span class="search-spinner position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-1"
                    data-kt-search-element="spinner">
                    <span class="text-gray-400 align-middle spinner-border h-15px w-15px"></span>
                  </span>
                  <!--end::Spinner-->
                  <!--begin::Reset-->
                  <span
                    class="search-reset btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none"
                    data-kt-search-element="clear">
                    <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                  <!--end::Reset-->
                </form>
                <!--end::Form-->
              </div>
              <!--end::Wrapper-->
            </div>
            <!--end::Menu-->
          </div>
          <!--end::Search-->
        </div>
        <!--end::Search-->
        <!--begin::Theme mode-->
        <div class="app-navbar-item ms-2 ms-md-3">
          <!--begin::Menu toggle-->
          <a href="#"
            class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
            data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-night-day theme-light-show fs-2 fs-lg-1">
              <span class="path1"></span>
              <span class="path2"></span>
              <span class="path3"></span>
              <span class="path4"></span>
              <span class="path5"></span>
              <span class="path6"></span>
              <span class="path7"></span>
              <span class="path8"></span>
              <span class="path9"></span>
              <span class="path10"></span>
            </i>
            <i class="ki-duotone ki-moon theme-dark-show fs-2 fs-lg-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </a>
          <!--begin::Menu toggle-->
          <!--begin::Menu-->
          <div
            class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold fs-base w-150px"
            data-kt-menu="true" data-kt-element="theme-mode-menu">
            <!--begin::Menu item-->
            <div class="px-3 my-0 menu-item">
              <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="light">
                <span class="menu-icon" data-kt-element="icon">
                  <i class="ki-duotone ki-night-day fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                    <span class="path6"></span>
                    <span class="path7"></span>
                    <span class="path8"></span>
                    <span class="path9"></span>
                    <span class="path10"></span>
                  </i>
                </span>
                <span class="menu-title">Light</span>
              </a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="px-3 my-0 menu-item">
              <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="dark">
                <span class="menu-icon" data-kt-element="icon">
                  <i class="ki-duotone ki-moon fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>
                <span class="menu-title">Dark</span>
              </a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="px-3 my-0 menu-item">
              <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="system">
                <span class="menu-icon" data-kt-element="icon">
                  <i class="ki-duotone ki-screen fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                  </i>
                </span>
                <span class="menu-title">System</span>
              </a>
            </div>
            <!--end::Menu item-->
          </div>
          <!--end::Menu-->
        </div>
        <!--end::Theme mode-->
        <!--begin::User menu-->
        <div class="app-navbar-item ms-2 ms-md-3" id="kt_header_user_menu_toggle">
          <!--begin::Menu wrapper-->
          <div class="cursor-pointer symbol symbol-30px symbol-md-40px"
            data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end">
            @if (auth()->user()->image)
              <div class="symbol-label" style="background-image:url('storage/{{ auth()->user()->image }}')">
              </div>
            @else
              <div
                class="symbol-label fs-2 fw-semibold bg-{{ role_color(auth()->user()->roles->first()->name) }} text-inverse-{{ role_color(auth()->user()->roles->first()->name) }}">
                {{ initial_letter(auth()->user()->name) }} </div>
            @endif
          </div>
          <!--begin::User account menu-->
          <div
            class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-275px"
            data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="px-3 menu-item">
              <div class="px-3 menu-content d-flex align-items-center">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px me-5">
                  @if (auth()->user()->image)
                    <div class="symbol-label" style="background-image:url('storage/{{ auth()->user()->image }}')">
                    </div>
                  @else
                    <div
                      class="symbol-label fs-2 fw-semibold bg-{{ role_color(auth()->user()->roles->first()->name) }} text-inverse-{{ role_color(auth()->user()->roles->first()->name) }}">
                      {{ initial_letter(auth()->user()->name) }} </div>
                  @endif
                </div>
                <!--end::Avatar-->
                <!--begin::Username-->
                <div class="d-flex flex-column">
                  <div class="fw-bold d-flex align-items-center fs-5"> {{ auth()->user()->name }} <span
                      class="px-2 py-1 badge badge-light-success fw-bold fs-8 ms-2">{{ auth()->user()->roles->first()->name }}</span>
                  </div>
                  <span class="fw-semibold text-muted fs-7">{{ auth()->user()->email }}</span>
                </div>
                <!--end::Username-->
              </div>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <div class="my-2 separator"></div>
            <!--end::Menu separator-->
            <!--begin::Menu item-->
            @feature('profile')
              <div class="px-5 menu-item">
                <a href="{{ route('profile.index') }}" class="px-5 menu-link">My Profile</a>
              </div>
            @endfeature
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="px-5 menu-item">
              <a href="#" class="px-5 menu-link">My Activity</a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <div class="my-2 separator"></div>
            <!--end::Menu separator-->
            <!--begin::Menu item-->
            <div class="px-5 menu-item">
              <a href="{{ route('logout') }}" class="px-5 menu-link"
                onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Logout</a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
            <!--end::Menu item-->
          </div>
          <!--end::User account menu-->
          <!--end::Menu wrapper-->
        </div>
        <!--end::User menu-->
        <!--begin::Header menu toggle-->
        <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
          <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
            <i class="ki-duotone ki-element-4 fs-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </div>
        </div>
        <!--end::Header menu toggle-->
      </div>
      <!--end::Navbar-->
    </div>
  </div>
</div>
