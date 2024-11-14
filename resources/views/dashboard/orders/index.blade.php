@extends('layouts.dashboard')
@push('token')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush


@section('content')
    <x-molecules.card>
        <x-slot:header class="pt-6 border-0">
            <x-slot:title>
                <div class="my-1 d-flex align-items-center position-relative me-5">
                    <x-atoms.icon class="position-absolute ms-5" icon="magnifier" path="2" size="3" />
                    <x-atoms.input data-kt-villages-table-filter="search" class="w-250px ps-13"
                        placeholder="Cari Kelurahan" />
                </div>
            </x-slot:title>
            <x-slot:toolbar>
                <div class="d-flex justify-content-end">
                    <x-atoms.button id="button_add_village" class="me-4" color="primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_add_village">
                        <x-atoms.icon class="fs-3" icon="plus-square" path="3" />
                        Tambah Data
                    </x-atoms.button>

                    {{-- <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-filter fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Filter
                    </button>
                    <div id="filter-menu" class="menu menu-sub menu-sub-dropdown w-300px w-md-550px" data-kt-menu="true">
                        <div class="py-5 px-7">
                            <div class="fs-5 text-dark fw-bold">Filter Options</div>
                        </div>
                        <div class="border-gray-200 separator"></div>
                        <div class="py-5 px-7" data-kt-villages-table-filter="form">
                            <div class="mb-10">
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-semibold">Kecamatan:</label>
                                    <x-atoms.select id="subdistrict-filter" class="fw-bold" name="subdistrict-filter"
                                        :items="$subdistricts" select2="true" data-placeholder="Select option"
                                        data-allow-clear="true" data-kt-villages-subdistrict-filter="subdistrict"
                                        data-hide-search="true" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="px-6 btn btn-light btn-active-light-primary fw-semibold me-2"
                                    data-kt-menu-dismiss="true" data-kt-villages-table-filter="reset">Reset</button>
                                <button type="submit" class="px-6 btn btn-primary fw-semibold" data-kt-menu-dismiss="true"
                                    data-kt-villages-table-filter="filter">Apply</button>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </x-slot:toolbar>
        </x-slot:header>
        <x-slot:body class="py-4">
            <input type="hidden" id="table-url" value="{{ route('villages.table') }}">
            <x-molecules.table class="mb-0 fs-6 gy-5" id="kt_villages_table">
                <x-slot:head>
                    <tr class="text-gray-400 text-start fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Kelurahan</th>
                        <th class="min-w-125px">Kecamatan</th>
                        <th class="min-w-125px text-end">Action</th>
                    </tr>
                </x-slot:head>
                <x-slot:body>
                </x-slot:body>
            </x-molecules.table>
        </x-slot:body>
    </x-molecules.card>

    <!--begin::Modal Add Kelurahan-->
    <x-molecules.modal id="kt_modal_add_village" class="mw-650px">
        <x-slot:title>Tambah Kelurahan</x-slot:title>
        <x-slot:body>
            <form id="kt_modal_add_village_form" class="form" action="#">
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" :required="false">
                        <span class="required">Nama Kelurahan</span>
                        <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                            data-bs-content="Kelurahan harus unik.">
                            <x-atoms.icon icon="information" path="3" size="7" />
                        </span>
                    </x-atoms.label>
                    <x-atoms.input name="name" placeholder="Masukkan nama Kelurahan" />
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label value="Kecamatan" class="mb-2 fs-6 fw-semibold" />
                    <x-atoms.select class="fw-bold" name="subdistrict_id" :items="$subdistricts" value="id" select2="true"
                        data-placeholder="Pilih kecamatan" data-allow-clear="true" data-hide-search="true" />
                </div>
                <div class="text-center pt-15">
                    <x-atoms.button type="reset" class="me-3" color="light"
                        data-kt-villages-modal-action="close">Close</x-atoms.button>
                    <x-atoms.button type="submit" id="submit_add_village" color="primary"
                        data-url="{{ route('villages.store') }}" data-kt-villages-modal-action="submit"></x-atoms.button>
                </div>
            </form>
        </x-slot:body>
    </x-molecules.modal>
    <!--end::Modal Add Kelurahan-->

    <!--begin::Modal Edit Kelurahan-->
    <x-molecules.modal id="kt_modal_edit_village" class="mw-650px">
        <x-slot:title>Edit Kelurahan</x-slot:title>
        <x-slot:body>
            <form id="kt_modal_edit_village_form" class="form" method="POST" enctype="multipart/form-data">
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" :required="false">
                        <span class="required">Nama Kelurahan</span>
                        <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                            data-bs-content="Kelurahan harus unik.">
                            <x-atoms.icon icon="information" path="3" size="7" />
                        </span>
                    </x-atoms.label>
                    <x-atoms.input name="name" placeholder="Masukkan nama Kelurahan" />
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label value="Kecamatan" class="mb-2 fs-6 fw-semibold" />
                    <x-atoms.select class="fw-bold" name="subdistrict_id" :items="$subdistricts" value="id"
                        select2="true" data-placeholder="Pilih kecamatan" data-allow-clear="true"
                        data-hide-search="true" />
                </div>
                <div class="text-center pt-15">
                    <x-atoms.button type="reset" class="me-3" color="light"
                        data-kt-villages-modal-action="close">Close</x-atoms.button>
                    <x-atoms.button type="submit" id="submit_edit_village" color="primary" data-id=""
                        data-kt-villages-modal-action="submit"></x-atoms.button>
                </div>
            </form>
        </x-slot:body>
    </x-molecules.modal>
    <!--end::Modal Edit Kelurahan-->

    @push('scripts')
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/custom/apps/regions/village/table.js') }}"></script>
        <script src="{{ asset('assets/js/custom/apps/regions/village/add.js') }}"></script>
        <script src="{{ asset('assets/js/custom/apps/regions/village/edit.js') }}"></script>
    @endpush
@endsection
