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
          <x-atoms.icon class=" fs-3 position-absolute ms-5" icon="magnifier" path="2" />
          <input type="text" data-kt-logs-table-filter="search" class="form-control form-control-solid w-250px ps-13"
            placeholder="Search log" />
        </div>
        <!--end::Search-->
      </x-slot:title>
      <x-slot:toolbar>
        <div class="d-flex justify-content-end" data-kt-logs-table-toolbar="base">
          <!--begin::Date Range-->
          <div class="">
            <button type="button" class="btn btn-light-info me-3" data-kt-menu-trigger="click"
              data-kt-menu-placement="bottom-end">
              <i class="ki-duotone ki-calendar fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>Date Range</button>
            <div id="date-range-menu" class="menu menu-sub menu-sub-dropdown w-300px w-md-550px" data-kt-menu="true">
              <div class="py-5 px-7">
                <div class="fs-5 text-dark fw-bold">Date Range</div>
              </div>
              <div class="border-gray-200 separator"></div>
              <div class="py-5 px-7" data-kt-logs-date-range-filter="form">
                <div class="mb-10">
                  <!--begin::Log Type Date Range Options-->
                  <label class="form-label fs-6 fw-semibold">Date Range:</label>
                  <x-atoms.input id="date-range-filter" name="date_range" data-placeholder="Pick date range"
                    data-kt-logs-date-range-filter="date" />
                  <!--end::Log Type Date Range Options-->
                </div>
                <!--begin::Filter Button-->
                <div class="d-flex justify-content-end">
                  <button type="reset" class="px-6 btn btn-light btn-active-light-primary fw-semibold me-2"
                    data-kt-menu-dismiss="true" data-kt-logs-date-range-filter="reset">Reset</button>
                  <button type="submit" class="px-6 btn btn-primary fw-semibold" data-kt-menu-dismiss="true"
                    data-kt-logs-date-range-filter="filter">Apply</button>
                </div>
                <!--end::Filter Button-->
              </div>
            </div>
          </div>
          <!--end::Filter-->
          <!--begin::Filter-->
          <div class="">
            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
              data-kt-menu-placement="bottom-end">
              <i class="ki-duotone ki-filter fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>Filter</button>
            <div id="filter-menu" class="menu menu-sub menu-sub-dropdown w-300px w-md-550px" data-kt-menu="true">
              <div class="py-5 px-7">
                <div class="fs-5 text-dark fw-bold">Filter Options</div>
              </div>
              <div class="border-gray-200 separator"></div>
              <div class="py-5 px-7" data-kt-logs-table-filter="form">
                <div class="mb-10">
                  <!--begin::Log Type Filter Options-->
                  <div class="mb-5">
                    <label class="form-label fs-6 fw-semibold">Log Type:</label>
                    <x-atoms.select id="log-type-filter" class="fw-bold" name="log-type-filter" :items="$logTypes"
                      select2="true" data-placeholder="Select option" data-allow-clear="true"
                      data-kt-logs-table-filter="log-type" data-hide-search="true" />
                  </div>
                  <!--end::Log Type Filter Options-->
                  <!--begin::Table Filter Options-->
                  <div class="mb-5">
                    <label class="form-label fs-6 fw-semibold">Table:</label>
                    <x-atoms.select id="table-filter" class="fw-bold" name="table-filter" :items="$tables"
                      property="table" select2="true" data-placeholder="Select option" data-allow-clear="true"
                      data-kt-logs-table-filter="table" data-hide-search="true" />
                  </div>
                  <!--end::Table Filter Options-->
                  <!--begin::Interface Filter Options-->
                  <div class="mb-5">
                    <label class="form-label fs-6 fw-semibold">Interface:</label>
                    <x-atoms.select id="interface-filter" class="fw-bold" name="interface-filter" :items="$interfaces"
                      select2="true" data-placeholder="Select option" data-allow-clear="true"
                      data-kt-logs-interface-filter="interface" data-hide-search="true" />
                  </div>
                  <!--end::Interface Filter Options-->
                </div>
                <!--begin::Filter Button-->
                <div class="d-flex justify-content-end">
                  <button type="reset" class="px-6 btn btn-light btn-active-light-primary fw-semibold me-2"
                    data-kt-menu-dismiss="true" data-kt-logs-table-filter="reset">Reset</button>
                  <button type="submit" class="px-6 btn btn-primary fw-semibold" data-kt-menu-dismiss="true"
                    data-kt-logs-table-filter="filter">Apply</button>
                </div>
                <!--end::Filter Button-->
              </div>
            </div>
          </div>
          <!--end::Filter-->
        </div>
        <!--begin::Delete Selected Button-->
        <div class="d-flex justify-content-end align-items-center d-none" data-kt-logs-table-toolbar="selected">
          <div class="fw-bold me-5">
            <span class="me-2" data-kt-logs-table-select="selected_count"></span>Selected
          </div>
          <button type="button" class="btn btn-danger" data-kt-logs-table-select="delete_selected">Delete
            Selected</button>
        </div>
        <!--end::Delete Selected Button-->
      </x-slot:toolbar>
    </x-slot:header>
    <x-slot:body class="py-4">
      <input type="hidden" id="table-url" value="{{ route('activity-logs.table') }}">
      <x-molecules.table id="kt_table_logs" class="fs-6 gy-5">
        <x-slot:head>
          <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-100px">Date</th>
            <th class="min-w-125px">User</th>
            <th class="text-center min-w-80px">Interface</th>
            <th class="min-w-125px">Subject</th>
            <th class="text-center min-w-80px">Log Type</th>
            <th class="text-center min-w-80px">Table</th>
            <th class="text-center min-w-80px"">Actions</th>
            <th style=" width: 0; padding: 0;"></th>
            <th class="rounded-end" style="width: 1em; padding: 0;"></th>
          </tr>
        </x-slot:head>
        <x-slot:body></x-slot:body>
      </x-molecules.table>
    </x-slot:body>
  </x-molecules.card>
  <!--end::Card body-->

  <!--begin::Modal Log Detail-->
  <div class="modal fade" id="kt_modal_detail_log" data-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <!--begin::Modal header-->
        <div class="modal-header" id="kt_modal_detail_log_header">
          <h2 class="fw-bold">Log Detail</h2>
          <!--begin::Close button-->
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-logs-modal-action="close"
            data-bs-dismiss="modal">
            <x-atoms.icon class="fs-1" icon="cross" path="2" />
          </div>
          <!--end::Close button-->
        </div>
        <!--end::Modal header-->
        <!--begin::Modal body-->
        <div class="mx-5 modal-body scroll-y mx-xl-15 my-7">
          <form id="kt_modal_detail_log_form" class="form">
            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_detail_log_scroll" data-kt-scroll="true"
              data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
              data-kt-scroll-dependencies="#kt_modal_detail_log_header"
              data-kt-scroll-wrappers="#kt_modal_detail_log_scroll" data-kt-scroll-offset="300px">
              <!--begin::Name-->
              <div class="fv-row mb-7">
                <x-atoms.label class="mb-2 fw-semibold fs-6" value="Name" required="false" />
                <x-atoms.input id="detail_log_name" name="detail_log_name" class="mb-3 mb-lg-0" placeholder="Name"
                  value="" disabled />
              </div>
              <!--end::Name-->
              <!--begin::Email-->
              <div class="fv-row mb-7">
                <x-atoms.label class="mb-2 fw-semibold fs-6" value="Email" required="false" />
                <x-atoms.input type="email" id="detail_log_email" name="detail_log_email" class="mb-3 mb-lg-0"
                  placeholder="example@gmail.com" value="" disabled />
              </div>
              <!--end::Email-->
              <!--begin::Subject-->
              <div class="fv-row mb-7">
                <x-atoms.label class="mb-2 fw-semibold fs-6" value="Subject" required="false" />
                <x-atoms.textarea id="detail_log_subject" name="detail_log_subject" class="mb-3 mb-lg-0"
                  placeholder="Subject" value="" rows="2" disabled />
              </div>
              <!--end::Interface-->
              <!--begin::Interface-->
              <div class="fv-row mb-7">
                <x-atoms.label class="mb-2 fw-semibold fs-6" value="Interface" required="false" />
                <x-atoms.input id="detail_log_interface" name="detail_log_interface" class="mb-3 mb-lg-0"
                  placeholder="Interface" value="" disabled />
              </div>
              <!--end::Interface-->
              <!--begin::Table-->
              <div class="fv-row mb-7">
                <x-atoms.label class="mb-2 fw-semibold fs-6" value="Table" required="false" />
                <x-atoms.input id="detail_log_table" name="detail_log_table" class="mb-3 mb-lg-0" placeholder="Table"
                  value="" disabled />
              </div>
              <!--end::Table-->
              <!--begin::Table ID-->
              <div class="fv-row mb-7">
                <x-atoms.label class="mb-2 fw-semibold fs-6" value="Table ID" required="false" />
                <x-atoms.input id="detail_log_table_id" name="detail_log_table_id" class="mb-3 mb-lg-0"
                  placeholder="Table ID" value="" disabled />
              </div>
              <!--end::Table ID-->
              <!--begin::Log Type-->
              <div class="mb-7">
                <x-atoms.label class="mb-5 fw-semibold fs-6" value="Log Type" required="false" />
                @foreach ($logTypes as $type)
                  <div class="d-flex fv-row">
                    <x-atoms.checkbox type="radio">
                      <x-slot:input id="kt_edit_modal_update_type_option_{{ $type }}"
                        class="me-3 detail_log_type" name="detail_log_type" value="{{ $type }}"
                        disabled></x-slot:input>
                      <x-slot:label>{{ $type }}</x-slot:label>
                    </x-atoms.checkbox>
                  </div>
                  <div class='my-5 separator separator-dashed'></div>
                @endforeach
              </div>
              <!--end::Type-->
            </div>
            <!--begin::Close Button-->
            <div class="text-center pt-15">
              <button type="reset" class="edit-discard btn btn-light me-3" data-kt-logs-modal-action="cancel"
                data-bs-dismiss="modal">Close</button>
            </div>
            <!--end::Close Button-->
          </form>
        </div>
        <!--end::Modal body-->
      </div>
    </div>
  </div>
  <!--end::Modal Log Detail-->

  @push('scripts')
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>

    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('js/custom/apps/activity-log/table.js') }}"></script>
    <script src="{{ asset('js/custom/apps/activity-log/detail.js') }}"></script>
    <!--end::Custom Javascript-->
  @endpush
@endsection
