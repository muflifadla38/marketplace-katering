@extends('layouts.dashboard')
@push('token')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
  <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush


@section('content')
  @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
  @endif

  <!--begin::Card body-->
  <x-molecules.card>
    <x-slot:header class="pt-6 border-0">
      <x-slot:title>
        <!--begin::Search-->
        <div class="my-1 d-flex align-items-center position-relative">
          <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
            <span class="path1"></span>
            <span class="path2"></span>
          </i>
          <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13"
            placeholder="Search user" />
        </div>
        <!--end::Search-->
      </x-slot:title>
      <x-slot:toolbar>
        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
          <!--begin::Filter-->
          <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
            data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-filter fs-2">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>Filter</button>
          <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
            <div class="py-5 px-7">
              <div class="fs-5 text-dark fw-bold">Filter Options</div>
            </div>
            <div class="border-gray-200 separator"></div>
            <div class="py-5 px-7" data-kt-user-table-filter="form">
              <!--begin::Filter Options-->
              <div class="mb-10">
                <label class="form-label fs-6 fw-semibold">Role:</label>
                <x-atoms.select class="fw-bold" name="role-filter" :items="$roles" select2="true"
                  data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="role"
                  data-hide-search="true" />
              </div>
              <!--end::Filter Options-->
              <!--begin::Filter Button-->
              <div class="d-flex justify-content-end">
                <button type="reset" class="px-6 btn btn-light btn-active-light-primary fw-semibold me-2"
                  data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset</button>
                <button type="submit" class="px-6 btn btn-primary fw-semibold" data-kt-menu-dismiss="true"
                  data-kt-user-table-filter="filter">Apply</button>
              </div>
              <!--end::Filter Button-->
            </div>
          </div>
          <!--end::Filter-->
          <x-atoms.button id="button_add_user" color="primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
            <x-atoms.icon icon="plus" />
            Add User
          </x-atoms.button>
        </div>
        <!--begin::Delete Selected Button-->
        <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
          <div class="fw-bold me-5">
            <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected
          </div>
          <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete
            Selected</button>
        </div>
        <!--end::Delete Selected Button-->
      </x-slot:toolbar>
    </x-slot:header>
    <x-slot:body class="py-4">
      <input type="hidden" id="table-url" value="{{ route('users.table') }}">
      <x-molecules.table id="kt_table_users" class="fs-6 gy-5">
        <x-slot:head>
          <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="w-10px rounded-start">
              <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                <input class="form-check-input" type="checkbox" data-kt-check="true"
                  data-kt-check-target="#kt_table_users .form-check-input" value="all" />
              </div>
            </th>
            <th class="min-w-125px">User</th>
            <th class="min-w-125px">Email</th>
            <th class="min-w-125px">Role</th>
            <th class="min-w-125px">Joined Date</th>
            <th class="text-end min-w-100px"">Actions</th>
            <th style=" width: 0; padding: 0;"></th>
            <th class="rounded-end" style="width: 1em; padding: 0;"></th>
          </tr>
        </x-slot:head>
        <x-slot:body></x-slot:body>
      </x-molecules.table>
    </x-slot:body>
  </x-molecules.card>
  <!--end::Card body-->

  <!--begin::Modal Add user-->
  <div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <!--begin::Modal header-->
        <div class="modal-header" id="kt_modal_add_user_header">
          <h2 class="fw-bold">Add User</h2>
          <!--begin::Close button-->
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
            <i class="ki-duotone ki-cross fs-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </div>
          <!--end::Close button-->
        </div>
        <!--end::Modal header-->
        <!--begin::Modal body-->
        <div class="mx-5 modal-body scroll-y mx-xl-15 my-7">
          <form id="kt_modal_add_user_form" class="form" method="POST" enctype="multipart/form-data">
            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true"
              data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
              data-kt-scroll-dependencies="#kt_modal_add_user_header"
              data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
              <!--begin::Profile Image Input-->
              <div class="fv-row mb-7">
                <label class="mb-5 d-block fw-semibold fs-6">Profile Image</label>
                <style>
                  .image-input-placeholder {
                    background-image: url('assets/media/svg/files/blank-image.svg');
                  }

                  [data-bs-theme="dark"] .image-input-placeholder {
                    background-image: url('assets/media/svg/files/blank-image-dark.svg');
                  }
                </style>
                <div class="image-input image-input-outline image-input-placeholder image-input-empty"
                  data-kt-image-input="true">
                  <!--begin::Preview existing Profile Image-->
                  <div class="image-input-wrapper w-125px h-125px" style="background-image: none;">
                  </div>
                  <!--begin::Label-->
                  <label class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change Profile Image">
                    <i class="ki-duotone ki-pencil fs-7">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                    <input type="file" id="add_user_image" name="avatar" accept=".png, .jpg, .jpeg" />
                    <input type="hidden" name="avatar_remove" />
                  </label>
                  <!--end::Label-->
                  <!--begin::Cancel-->
                  <span
                    class="shadow remove-add-image btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel Profile Image">
                    <i class="ki-duotone ki-cross fs-2">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                  <!--end::Cancel-->
                  <!--begin::Remove-->
                  <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove Profile Image">
                    <i class="ki-duotone ki-cross fs-2">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                  <!--end::Remove-->
                </div>
                <!--begin::Hint-->
                <div class="form-text">Allowed file types: png, jpg, jpeg (max: 2MB)</div>
                <!--end::Hint-->
              </div>
              <!--end::Profile Image Input-->
              <!--begin::Name Input-->
              <div class="fv-row mb-7">
                <x-atoms.label class="fs-6" value="Full Name" />
                <x-atoms.input id="add_user_name" name="add_user_name" class="mb-3 mb-lg-0" placeholder="Full name" />
              </div>
              <!--end::Name Input-->
              <!--begin::Email Input-->
              <div class="fv-row mb-7">
                <label class="mb-2 required fw-semibold fs-6">Email</label>
                <input type="email" id="add_user_email" name="add_user_email"
                  class="mb-3 form-control form-control-solid mb-lg-0" placeholder="example@gmail.com" value=""
                  required />
              </div>
              <!--end::Email Input-->
              <!--begin::Password Input-->
              <div class="fv-row mb-7">
                <label class="mb-2 required fw-semibold fs-6">Password</label>
                <input type="password" id="add_user_password" name="add_user_password"
                  class="mb-3 form-control form-control-solid mb-lg-0" value="" required />
              </div>
              <!--end::Password Input-->
              <!--begin::Role Input-->
              <div class="mb-7">
                <label class="mb-5 required fw-semibold fs-6">Role</label>
                @foreach ($roles as $role)
                  <div class="d-flex fv-row">
                    <x-atoms.checkbox type="radio" checked="{{ $role->name == 'staff' }}">
                      <x-slot:input id="kt_add_modal_update_role_option_{{ $role->name }}"
                        class="me-3 add_user_role" name="add_user_role" value="{{ $role->name }}"></x-slot:input>
                      <x-slot:label>{{ $role->name }}</x-slot:label>
                    </x-atoms.checkbox>
                  </div>
                  <div class='my-5 separator separator-dashed'></div>
                @endforeach
              </div>
              <!--end::Role Input-->
            </div>
            <!--begin::Submit Button-->
            <div class="text-center pt-15">
              <button type="reset" class="add-discard btn btn-light me-3"
                data-kt-users-modal-action="cancel">Discard</button>
              <button type="button" class="btn btn-primary" data-kt-users-modal-action="submit">
                <span class="indicator-label">Submit</span>
                <span class="indicator-progress">Please wait...
                  <span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>
              </button>
            </div>
            <!--end::Submit Button-->
          </form>
        </div>
        <!--end::Modal body-->
      </div>
    </div>
  </div>
  <!--end::Modal Add user-->

  <!--begin::Modal Edit user-->
  <div class="modal fade" id="kt_modal_edit_user" data-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <!--begin::Modal header-->
        <div class="modal-header" id="kt_modal_edit_user_header">
          <h2 class="fw-bold">Edit User</h2>
          <!--begin::Close button-->
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
            <i class="ki-duotone ki-cross fs-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </div>
          <!--end::Close button-->
        </div>
        <!--end::Modal header-->
        <!--begin::Modal body-->
        <div class="mx-5 modal-body scroll-y mx-xl-15 my-7">
          <form id="kt_modal_edit_user_form" class="form" method="PUT" enctype="multipart/form-data" novalidate>
            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_edit_user_scroll" data-kt-scroll="true"
              data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
              data-kt-scroll-dependencies="#kt_modal_edit_user_header"
              data-kt-scroll-wrappers="#kt_modal_edit_user_scroll" data-kt-scroll-offset="300px">
              <!--begin::Profile Image Input-->
              <div class="fv-row mb-7">
                <label class="mb-5 d-block fw-semibold fs-6">Profile Image</label>
                <style>
                  .image-input-placeholder {
                    background-image: url('assets/media/svg/files/blank-image.svg');
                  }

                  [data-bs-theme="dark"] .image-input-placeholder {
                    background-image: url('assets/media/svg/files/blank-image-dark.svg');
                  }
                </style>
                <div class="image-input image-input-outline image-input-placeholder image-input-empty"
                  data-kt-image-input="true">
                  <!--begin::Preview existing Profile Image-->
                  <div class="image-input-wrapper w-125px h-125px" style="background-image: none;">
                  </div>
                  <!--begin::Label-->
                  <label class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change Profile Image">
                    <x-atoms.icon icon="pencil" path="2" size="7" />
                    <input type="file" id="edit_user_image" name="avatar" accept=".png, .jpg, .jpeg" />
                    <input type="hidden" name="avatar_remove" />
                  </label>
                  <!--end::Label-->
                  <!--begin::Cancel-->
                  <x-molecules.tooltip
                    class="shadow remove-edit-image btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                    title="Cancel Profile Image" data-kt-image-input-action="cancel">
                    <x-atoms.icon icon="cross" path="2" size="2" />
                  </x-molecules.tooltip>
                  <!--end::Cancel-->
                  <!--begin::Remove-->
                  <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove Profile Image">
                    <i class="ki-duotone ki-cross fs-2">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                  <!--end::Remove-->
                </div>
                <!--begin::Hint-->
                <div class="form-text">Allowed file types: png, jpg, jpeg (max: 2MB)</div>
                <!--end::Hint-->
              </div>
              <!--end::Profile Image Input-->
              <!--begin::Name Input-->
              <div class="fv-row mb-7">
                <label class="mb-2 required fw-semibold fs-6">Full Name</label>
                <input type="text" id="edit_user_name" name="edit_user_name"
                  class="mb-3 form-control form-control-solid mb-lg-0" placeholder="Full name" value="" />
                <input type="hidden" id="user_id" value="">
                <input type="hidden" id="user_image" value="">
              </div>
              <!--end::Name Input-->
              <!--begin::Email Input-->
              <div class="fv-row mb-7">
                <label class="mb-2 required fw-semibold fs-6">Email</label>
                <input type="email" id="edit_user_email" name="edit_user_email"
                  class="mb-3 form-control form-control-solid mb-lg-0" placeholder="example@gmail.com" value=""
                  required />
              </div>
              <!--end::Email Input-->
              <!--begin::Password Input-->
              <div class="fv-row mb-7">
                <label class="mb-2 required fw-semibold fs-6">Password</label>
                <input type="password" id="edit_user_password" name="edit_user_password"
                  class="mb-3 form-control form-control-solid mb-lg-0" value="" required />
              </div>
              <!--end::Password Input-->
              <!--begin::Role Input-->
              <div class="mb-7">
                <label class="mb-5 required fw-semibold fs-6">Role</label>
                @foreach ($roles as $role)
                  <div class="d-flex fv-row">
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input me-3 edit_user_role" name="edit_user_role" type="radio"
                        value="{{ $role->name }}" id="kt_edit_modal_update_role_option_{{ $role->name }}" />
                      <label class="form-check-label" for="kt_modal_update_role_option_{{ $role->name }}">
                        <div class="text-gray-800 fw-bold text-capitalize">{{ $role->name }}</div>
                      </label>
                    </div>
                  </div>
                  <div class='my-5 separator separator-dashed'></div>
                @endforeach
              </div>
              <!--end::Role Input-->
            </div>
            <!--begin::Submit Button-->
            <div class="text-center pt-15">
              <button type="reset" class="edit-discard btn btn-light me-3"
                data-kt-users-modal-action="cancel">Discard</button>
              <button type="button" class="btn btn-primary" data-kt-users-modal-action="submit">
                <span class="indicator-label">Submit</span>
                <span class="indicator-progress">Please wait...
                  <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                </span>
              </button>
            </div>
            <!--end::Submit Button-->
          </form>
        </div>
        <!--end::Modal body-->
      </div>
    </div>
  </div>
  <!--end::Modal Edit user-->

  @push('scripts')
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Vendors Javascript-->

    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('js/custom/apps/user-management/users/table.js') }}"></script>
    <script src="{{ asset('js/custom/apps/user-management/users/add.js') }}"></script>
    <script src="{{ asset('js/custom/apps/user-management/users/edit.js') }}"></script>
    <!--end::Custom Javascript-->
  @endpush
@endsection
