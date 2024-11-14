@extends('layouts.dashboard')
@push('token')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
  <link href="{{ asset('plugins/custom/jquerynestable/jquery.nestable.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
  @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
  @endif

  <x-molecules.card>
    <x-slot:header class="pt-6 border-0">
      <x-slot:title>Menu Setting</x-slot:title>
      <x-slot:toolbar>
        <div class="d-flex justify-content-end">
          <x-atoms.button id="button_add_menu" class="me-4" color="primary" data-bs-toggle="modal"
            data-bs-target="#kt_modal_add_menu">
            <x-atoms.icon icon="plus" />
            Add Menu
          </x-atoms.button>
          <x-atoms.button id="button_update_menu" color="secondary" data-url="{{ route('menus.update', 'save') }}">
            <x-atoms.icon icon="save-2" path="2" />
            <span class="indicator-label">Save</span>
            <span class="indicator-progress">Please wait...
              <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
            </span>
          </x-atoms.button>
        </div>
      </x-slot:toolbar>
    </x-slot:header>
    <x-slot:body class="py-4">
      <div class="my-10" id="kt_menu_list">
        <ol class="dd-list">
          @foreach ($menus as $key => $menu)
            @if ($menu->heading)
              <li class="dd-item" data-id="{{ $menu->id }}" data-name="{{ $menu->name }}"
                data-heading="{{ $menu->heading }}" data-feature="{{ implode(',', $menu->feature) }}"
                data-permission="{{ implode(',', $menu->permission) }}" data-icon="{{ $menu->icon }}"
                data-hasChild="{{ $menu->hasChild }}">
                <div class="dd-handle">
                  <x-molecules.nestable-list label="{{ $menu->name }} I" target="kt_modal_edit_menu">
                    <x-slot:button></x-slot:button>
                  </x-molecules.nestable-list>
                </div>

                @if (isset($menu->children))
                  @forelse ($menu->children as $parent)
                    <ol class="dd-list">
                      <li class="dd-item" data-id="{{ $parent->id }}" data-name="{{ $parent->name }}"
                        data-feature="{{ implode(',', $parent->feature) }}"
                        data-permission="{{ implode(',', $parent->permission) }}" data-icon="{{ $parent->icon }}"
                        data-path="{{ $parent->path }}" data-route="{{ implode(',', $parent->route) }}"
                        data-hasChild="{{ $menu->hasChild }}">
                        <div class="dd-handle">
                          <x-molecules.nestable-list label="{{ $parent->name }} II" target="kt_modal_edit_menu">
                            <x-slot:button></x-slot:button>
                          </x-molecules.nestable-list>
                        </div>

                        @if ($parent->hasChild)
                          <ol class="dd-list">
                            @foreach ($parent->children as $child)
                              <li class="dd-item" data-id="{{ $child->id }}" data-name="{{ $child->name }}"
                                data-feature="{{ implode(',', $child->feature) }}"
                                data-permission="{{ implode(',', $child->permission) }}"
                                data-route="{{ implode(',', $child->route) }}" data-hasChild="{{ $menu->hasChild }}"
                                data-children="[]">
                                <div class="dd-handle">
                                  <x-molecules.nestable-list label="{{ $child->name }} III" target="kt_modal_edit_menu">
                                    <x-slot:button></x-slot:button>
                                  </x-molecules.nestable-list>
                                </div>
                              </li>
                            @endforeach
                          </ol>
                        @endif
                      </li>
                    </ol>
                    @if ($menu->hasChild)
                    @endif
                  @empty
              <li class="dd-item" data-id="{{ $menu->id }}" data-name="{{ $menu->name }}">
                <div class="dd-handle">
                  <x-molecules.nestable-list label="{{ $menu->name }} V" target="kt_modal_edit_menu">
                    <x-slot:button></x-slot:button>
                  </x-molecules.nestable-list>
                </div>
              </li>
            @endforelse
          @endif
          </li>
        @else
          <li class="dd-item" data-id="{{ $menu->id }}" data-name="{{ $menu->name }}"
            data-heading="{{ $menu->heading }}" data-feature="{{ implode(',', $menu->feature) }}"
            data-permission="{{ implode(',', $menu->permission) }}" data-icon="{{ $menu->icon }}"
            data-path="{{ $menu->path }}" data-route="{{ implode(',', $menu->route) }}"
            data-hasChild="{{ $menu->hasChild }}" data-children="[]">
            <div class="dd-handle">
              <x-molecules.nestable-list label="{{ $menu->name }} IV" target="kt_modal_edit_menu">
                <x-slot:button></x-slot:button>
              </x-molecules.nestable-list>
            </div>
          </li>
          @endif
          @endforeach
        </ol>
      </div>

      {{-- <div class="dd" id="nestable_list_2">
        <ol class="dd-list">
          <li class="dd-item" data-id="1">
            <div class="dd-handle">
              Item 1
            </div>
          </li>
          <li class="dd-item" data-id="2">
            <div class="dd-handle">
              Item 2
            </div>
            <ol class="dd-list">
              <li class="dd-item" data-id="3">
                <div class="dd-handle">
                  Item 3
                </div>
              </li>
              <li class="dd-item" data-id="4">
                <div class="dd-handle">
                  Item 4
                </div>
              </li>
              <li class="dd-item" data-id="5">
                <div class="dd-handle">
                  Item 5
                </div>
                <ol class="dd-list">
                  <li class="dd-item" data-id="6">
                    <div class="dd-handle">
                      Item 6
                    </div>
                  </li>
                  <li class="dd-item" data-id="7">
                    <div class="dd-handle">
                      Item 7
                    </div>
                  </li>
                  <li class="dd-item" data-id="8">
                    <div class="dd-handle">
                      Item 8
                    </div>
                  </li>
                </ol>
              </li>
              <li class="dd-item" data-id="9">
                <div class="dd-handle">
                  Item 9
                </div>
              </li>
              <li class="dd-item" data-id="10">
                <div class="dd-handle">
                  Item 10
                </div>
              </li>
            </ol>
          </li>
          <li class="dd-item" data-id="11">
            <div class="dd-handle">
              Item 11
            </div>
          </li>
          <li class="dd-item" data-id="12">
            <div class="dd-handle">
              Item 12
            </div>
          </li>
        </ol>
      </div> --}}
    </x-slot:body>
  </x-molecules.card>

  <!--begin::Modal - Add Menu-->
  <x-molecules.modal id="kt_modal_add_menu" class="mw-650px">
    <x-slot:title>Add Menu</x-slot:title>
    <x-slot:body>
      <form id="kt_modal_add_menu_form" class="form" method="POST" enctype="multipart/form-data">
        <div class="mb-8 d-flex flex-column fv-row">
          <x-atoms.label value="Name" />
          <x-atoms.input name="add-name" placeholder="Name" />
        </div>
        <div class="mb-8 d-flex flex-column fv-row">
          <x-atoms.label value="feature" />
          <x-atoms.input name="add-feature" placeholder="Feature Name" />
        </div>
        <div class="mb-8 d-flex flex-column fv-row">
          <x-atoms.label value="icon" />
          <x-atoms.input name="add-icon" placeholder="Icon" />
        </div>
        <div class="mb-8 d-flex flex-column fv-row">
          <x-atoms.label value="path" />
          <x-atoms.input name="add-path" placeholder="Path Icon Number" />
        </div>
        <div class="mb-8 d-flex flex-column fv-row">
          <x-atoms.label value="route" />
          <x-atoms.input name="add-route" placeholder="Menu Route" />
        </div>
        <div class="mb-8 d-flex flex-stack">
          <div class="me-5">
            <x-atoms.label value="Menu Type" :required="false" />
            <div class="fs-7 fw-semibold text-muted">The menu will become a heading/ menu</div>
          </div>
          <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" name="add-heading" value="false" />
            <span class="form-check-label fw-semibold text-muted">Heading</span>
          </label>
        </div>
      </form>
    </x-slot:body>
    <x-slot:footer>
      <x-atoms.button color="light" data-bs-dismiss="modal">Close</x-atoms.button>
      <x-atoms.button id="submit_add_menu" color="primary" data-url="{{ route('menus.store') }}">
        <span class="indicator-label">Submit</span>
        <span class="indicator-progress">Please wait...
          <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
        </span>
      </x-atoms.button>
    </x-slot:footer>
  </x-molecules.modal>
  <!--end::Modal - Add Menu-->

  <!--begin::Modal - Edit Menu-->
  <x-molecules.modal id="kt_modal_edit_menu">
    <x-slot:title>Edit Menu</x-slot:title>
    <x-slot:body>
      <p>Modal body text goes here.</p>
    </x-slot:body>
    <x-slot:footer>
      <x-atoms.button color="light" data-bs-dismiss="modal">Close</x-atoms.button>
      <x-atoms.button color="primary" data-bs-dismiss="modal">
        <span class="indicator-label">Submit</span>
        <span class="indicator-progress">Please wait...
          <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
        </span></x-atoms.button>
    </x-slot:footer>
  </x-molecules.modal>
  <!--end::Modal - Edit Menu-->

  <!--begin::Modal - Update Menu-->
  <x-molecules.modal id="kt_modal_update_menu">
    <x-slot:title>Update Menu</x-slot:title>
    <x-slot:body>
      <p>Modal body text goes here.</p>
    </x-slot:body>
    <x-slot:footer>
      <x-atoms.button color="light" data-bs-dismiss="modal">Close</x-atoms.button>
      <x-atoms.button color="primary" data-bs-dismiss="modal">
        <span class="indicator-label">Save</span>
        <span class="indicator-progress">Please wait...
          <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
        </span></x-atoms.button>
    </x-slot:footer>
  </x-molecules.modal>
  <!--end::Modal - Edit Menu-->

  @push('scripts')
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('plugins/custom/jquerynestable/jquery.nestable.js') }}"></script>
    <script src="{{ asset('js/custom/apps/setting/menus.js') }}"></script>
    </script>
    <!--end::Custom Javascript-->
  @endpush
@endsection
