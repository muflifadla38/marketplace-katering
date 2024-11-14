<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
  data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
  data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
  <div class="px-6 app-sidebar-logo" id="kt_app_sidebar_logo">
    <a href="{{ route('dashboard.index') }}">
      <img alt="Logo" src="{{ asset('media/logo/default-dark.svg') }}" class="h-25px app-sidebar-logo-default" />
      <img alt="Logo" src="{{ asset('media/logo/default-small.svg') }}" class="h-20px app-sidebar-logo-minimize" />
    </a>

    <!--
    if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
    1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
    2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
    3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
    4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
    }
    -->

    <div id="kt_app_sidebar_toggle"
      class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
      data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
      data-kt-toggle-name="app-sidebar-minimize">
      <i class="rotate-180 ki-duotone ki-double-left fs-2">
        <span class="path1"></span>
        <span class="path2"></span>
      </i>
    </div>
  </div>

  @php
    $segments = Request::segments();
    $menus = json_decode(File::get(config('constant.app_menus_json')));
  @endphp

  <div class="overflow-hidden app-sidebar-menu flex-column-fluid">
    <div id="kt_app_sidebar_menu_wrapper" class="my-5 app-sidebar-wrapper hover-scroll-overlay-y" data-kt-scroll="true"
      data-kt-scroll-activate="true" data-kt-scroll-height="auto"
      data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
      data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
      <div class="px-3 menu menu-column menu-rounded menu-sub-indention" id="#kt_app_sidebar_menu" data-kt-menu="true"
        data-kt-menu-expand="false">

        @each('components.molecules.nestable-menu', $menus, 'menu')
        {{-- @foreach ($menus as $menu)
          @if (Feature::someAreActive($menu->feature))
            @canany($menu->permission)
              @if ($menu->heading)
                <div class="pt-5 menu-item">
                  <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">{{ $menu->name }}</span>
                  </div>
                </div>
              @endif
            @endcanany

            @forelse ($menu->children as $parent)
              @if (Feature::someAreActive($parent->feature))
                @canany($parent->permission)
                  <div @class([
                      'menu-item',
                      'menu-accordion' => $parent->hasChild,
                      'show here' => $parent->hasChild && request()->RouteIs($parent->route),
                  ]) {{ $parent->hasChild ? 'data-kt-menu-trigger="click"' : null }}>
                    @php
                      $element = !$parent->hasChild ? 'a' : 'div';
                    @endphp
                    <{{ $element }} @class([
                        'menu-link',
                        'active' => !$parent->hasChild && request()->RouteIs($parent->route),
                    ])
                      {{ !$parent->hasChild ? 'href=' . route(reset($parent->route)) : null }}>
                      <span class="menu-icon">
                        <x-atoms.icon class="fs-2" icon="{{ $parent->icon }}" path="{{ $parent->path }}" />
                      </span>
                      <span class="menu-title">{{ $parent->name }}</span>
                      @if ($parent->hasChild)
                        <span class="menu-arrow"></span>
                      @endif
                      </{{ $element }}>

                      @if ($parent->hasChild)
                        <div class="menu-sub menu-sub-accordion">
                          @foreach ($parent->children as $child)
                            @if (Feature::someAreActive($child->feature))
                              @canany($child->permission)
                                <div class="menu-item">
                                  <a @class(['menu-link', 'active' => request()->RouteIs($child->route)]) href="{{ route(reset($child->route)) }}">
                                    <span class="menu-bullet">
                                      <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ $child->name }}</span>
                                  </a>
                                </div>
                              @endcanany
                            @endif
                          @endforeach
                        </div>
                      @endif
                  </div>
                @endif
              @endcanany
            @empty
              @if (Feature::someAreActive($menu->feature))
                @canany($menu->permission)
                  <div class="menu-item">
                    <a @class(['menu-link', 'active' => request()->RouteIs($menu->route)]) href="{{ route(reset($menu->route)) }}">
                      <span class="menu-icon">
                        <x-atoms.icon class="fs-2" icon="{{ $menu->icon }}" path="{{ $menu->path }}" />
                      </span>
                      <span class="menu-title">{{ $menu->name }}</span>
                    </a>
                  </div>
                @endcanany
              @endif
            @endforelse
          @endif
        @endforeach --}}
      </div>
    </div>
  </div>

  <div class="px-6 pt-2 pb-6 app-sidebar-footer flex-column-auto" id="kt_app_sidebar_footer">
    <a href="https://preview.keenthemes.com/html/metronic/docs"
      class="px-0 overflow-hidden btn btn-flex flex-center btn-custom btn-primary text-nowrap h-40px w-100"
      data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click"
      title="200+ in-house components and 3rd-party plugins">
      <span class="btn-label">Docs & Components</span>
      <i class="m-0 ki-duotone ki-document btn-icon fs-2">
        <span class="path1"></span>
        <span class="path2"></span>
      </i>
    </a>
  </div>
</div>
