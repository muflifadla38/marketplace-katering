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

  <!-- List Roles -->
  <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
    <div class="col-md-4">
      <div class="card h-md-100">
        <div class="card-body d-flex flex-center">
          <button type="button" class="btn btn-clear d-flex flex-column flex-center" data-bs-toggle="modal"
            data-bs-target="#kt_modal_add_role">
            <img src="{{ asset('media/illustrations/4.png') }}" alt="" class="mw-100 mh-150px mb-7" />
            <div class="text-gray-600 fw-bold fs-3 text-hover-primary">Add New Role</div>
          </button>
        </div>
      </div>
    </div>
    @foreach ($roles as $role)
      <div class="col-md-4">
        <div class="card card-flush h-md-100">
          <div class="card-header">
            <div class="card-title">
              <h2 class="text-capitalize">{{ $role->name }}</h2>
            </div>
          </div>
          <div class="pt-1 card-body">
            <div class="mb-5 text-gray-600 fw-bold">Total users with this role: {{ $role->users->count() }}</div>
            <div class="text-gray-600 d-flex flex-column">
              @foreach (get_permission_filter($role->permissions) as $key => $permission)
                <div class="py-2 d-flex align-items-center text-capitalize">
                  <span class="bullet bg-primary me-3"></span>{{ $permission }}
                </div>

                @php
                  if ($key > 2) {
                      break;
                  }
                @endphp
              @endforeach

              @if ($role->permissions->isEmpty())
                <div class='py-2 d-flex align-items-center'>
                  <span class='bullet bg-primary me-3'></span>
                  <span>No permission</span>
                </div>
              @elseif (!$role->permissions->isEmpty() and $key >= 2)
                <div class='py-2 d-flex align-items-center'>
                  <span class='bullet bg-primary me-3'></span>
                  <em>and etc...</em>
                </div>
              @endif
            </div>
          </div>
          <div class="flex-wrap pt-0 card-footer">
            <button type="button" class="my-1 edit-role btn btn-light btn-active-primary me-2" data-bs-toggle="modal"
              data-bs-target="#kt_modal_update_role" data-id="{{ $role->id }}">Edit
              Role</button>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Create Modal -->
  <div class="modal fade" id="kt_modal_add_role" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="fw-bold">Add a Role</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
            <i class="ki-duotone ki-cross fs-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </div>
        </div>
        <div class="modal-body scroll-y mx-lg-5 my-7">
          <form id="kt_modal_add_role_form" class="form" action="#">
            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll" data-kt-scroll="true"
              data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
              data-kt-scroll-dependencies="#kt_modal_add_role_header" data-kt-scroll-wrappers="#kt_modal_add_role_scroll"
              data-kt-scroll-offset="300px">
              <div class="mb-10 fv-row">
                <label class="mb-2 fs-5 fw-bold form-label">
                  <span class="required">Role name</span>
                </label>
                <input class="form-control form-control-solid" placeholder="Enter a role name" name="name" />
                <input type="hidden" id="role_id">
              </div>
              <div class="fv-row">
                <label class="mb-2 fs-5 fw-bold form-label">Role Permissions</label>
                <div class="table-responsive">
                  <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <tbody class="text-gray-600 fw-semibold">
                      <tr>
                        <td class="text-gray-800">Administrator Access
                          <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                            data-bs-content="Allows a full access to the system">
                            <i class="ki-duotone ki-information fs-7">
                              <span class="path1"></span>
                              <span class="path2"></span>
                              <span class="path3"></span>
                            </i>
                          </span>
                        </td>
                        <td>
                          <label class="form-check form-check-custom form-check-solid me-9">
                            <input class="form-check-input" type="checkbox" value="all" id="kt_roles_select_all" />
                            <span class="form-check-label" for="kt_roles_select_all">Select
                              all</span>
                          </label>
                        </td>
                      </tr>
                      @foreach ($permissions as $permission)
                        <tr>
                          <td class="text-gray-800 text-capitalize">
                            {{ get_list_permission($permission, 'name') }}</td>
                          <td>
                            <div class="d-flex">
                              <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                <input class="checkbox-item-add form-check-input" type="checkbox"
                                  value="read {{ get_list_permission($permission, 'name') }}"
                                  name="{{ get_list_permission($permission) }}_read" />
                                <span class="form-check-label">Read</span>
                              </label>
                              <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                <input class="checkbox-item-add form-check-input" type="checkbox"
                                  value="create {{ get_list_permission($permission, 'name') }}"
                                  name="{{ get_list_permission($permission) }}_create" />
                                <span class="form-check-label">Create</span>
                              </label>
                              <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                <input class="checkbox-item-add form-check-input" type="checkbox"
                                  value="update {{ get_list_permission($permission, 'name') }}"
                                  name="{{ get_list_permission($permission) }}_update" />
                                <span class="form-check-label">Update</span>
                              </label>
                              <label class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="checkbox-item-add form-check-input" type="checkbox"
                                  value="delete {{ get_list_permission($permission, 'name') }}"
                                  name="{{ get_list_permission($permission) }}_delete" />
                                <span class="form-check-label">Delete</span>
                              </label>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="text-center pt-15">
              <button type="reset" class="btn btn-light me-3" data-kt-roles-modal-action="cancel">Discard</button>
              <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                <span class="indicator-label">Submit</span>
                <span class="indicator-progress">Please wait...
                  <span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Update Modal -->
  <div class="modal fade" id="kt_modal_update_role" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="fw-bold">Update Role</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
            <i class="ki-duotone ki-cross fs-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </div>
        </div>
        <div class="mx-5 modal-body scroll-y my-7">
          <form id="kt_modal_update_role_form" class="form" action="#">
            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll" data-kt-scroll="true"
              data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
              data-kt-scroll-dependencies="#kt_modal_update_role_header"
              data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">
              <div class="mb-10 fv-row">
                <label class="mb-2 fs-5 fw-bold form-label">
                  <span class="required">Role name</span>
                </label>
                <input class="form-control form-control-solid" placeholder="Enter a role name" name="role_name"
                  value="" />
                <input type="hidden" id="role-id" value="">
              </div>
              <div class="fv-row">
                <label class="mb-2 fs-5 fw-bold form-label">Role Permissions</label>
                <div class="table-responsive">
                  <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <tbody class="text-gray-600 fw-semibold">
                      <tr>
                        <td class="text-gray-800">Administrator Access
                          <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                            data-bs-content="Allows a full access to the system">
                            <i class="ki-duotone ki-information fs-7">
                              <span class="path1"></span>
                              <span class="path2"></span>
                              <span class="path3"></span>
                            </i>
                          </span>
                        </td>
                        <td>
                          <label class="form-check form-check-custom form-check-solid me-9">
                            <input class="form-check-input" type="checkbox" value="all" id="kt_roles_select_all" />
                            <span class="form-check-label" for="kt_roles_select_all">Select
                              all</span>
                          </label>
                        </td>
                      </tr>
                      @foreach ($permissions as $permission)
                        <tr>
                          <td class="text-gray-800 text-capitalize">
                            {{ get_list_permission($permission, 'name') }}</td>
                          <td>
                            <div class="d-flex">
                              <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                <input id="read_{{ get_list_permission($permission) }}" class="form-check-input"
                                  type="checkbox" value="read {{ get_list_permission($permission, 'name') }}"
                                  name="{{ get_list_permission($permission) }}_read" />
                                <span class="form-check-label">Read</span>
                              </label>
                              <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                <input id="create_{{ get_list_permission($permission) }}" class="form-check-input"
                                  type="checkbox" value="create {{ get_list_permission($permission, 'name') }}"
                                  name="{{ get_list_permission($permission) }}_create" />
                                <span class="form-check-label">Create</span>
                              </label>
                              <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                <input id="update_{{ get_list_permission($permission) }}" class="form-check-input"
                                  type="checkbox" value="update {{ get_list_permission($permission, 'name') }}"
                                  name="{{ get_list_permission($permission) }}_update" />
                                <span class="form-check-label">Update</span>
                              </label>
                              <label class="form-check form-check-sm form-check-custom form-check-solid">
                                <input id="delete_{{ get_list_permission($permission) }}" class="form-check-input"
                                  type="checkbox" value="delete {{ get_list_permission($permission, 'name') }}"
                                  name="{{ get_list_permission($permission) }}_delete" />
                                <span class="form-check-label">Delete</span>
                              </label>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="text-center pt-15">
              <button type="reset" class="btn btn-light me-3" data-kt-roles-modal-action="cancel">Discard</button>
              <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                <span class="indicator-label">Submit</span>
                <span class="indicator-progress">Please wait...
                  <span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script src="{{ asset('js/custom/apps/user-management/roles/add.js') }}"></script>
    <script src="{{ asset('js/custom/apps/user-management/roles/edit.js') }}"></script>
  @endpush
@endsection
