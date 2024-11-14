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
                    <x-atoms.input data-kt-menus-table-filter="search" class="w-250px ps-13" placeholder="Cari Menu" />
                </div>
            </x-slot:title>
            <x-slot:toolbar>
                <div class="d-flex justify-content-end">
                    <x-atoms.button id="button_add_menu" class="me-4" color="primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_add_menu">
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
                        <div class="py-5 px-7" data-kt-menus-table-filter="form">
                            <div class="mb-10">
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-semibold">Kecamatan:</label>
                                    <x-atoms.select id="subdistrict-filter" class="fw-bold" name="subdistrict-filter"
                                        :items="$subdistricts" select2="true" data-placeholder="Select option"
                                        data-allow-clear="true" data-kt-menus-subdistrict-filter="subdistrict"
                                        data-hide-search="true" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="px-6 btn btn-light btn-active-light-primary fw-semibold me-2"
                                    data-kt-menu-dismiss="true" data-kt-menus-table-filter="reset">Reset</button>
                                <button type="submit" class="px-6 btn btn-primary fw-semibold" data-kt-menu-dismiss="true"
                                    data-kt-menus-table-filter="filter">Apply</button>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </x-slot:toolbar>
        </x-slot:header>
        <x-slot:body class="py-4">
            <input type="hidden" id="table-url" value="{{ route('menus.table') }}">
            <x-molecules.table class="mb-0 fs-6 gy-5" id="kt_menus_table">
                <x-slot:head>
                    <tr class="text-gray-400 text-start fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Nama Menu</th>
                        <th class="min-w-125px">Gambar</th>
                        <th class="min-w-125px">Deskripsi</th>
                        <th class="min-w-125px">Harga</th>
                        <th class="min-w-125px">Kategori</th>
                        <th class="min-w-125px text-end">Action</th>
                    </tr>
                </x-slot:head>
                <x-slot:body>
                </x-slot:body>
            </x-molecules.table>
        </x-slot:body>
    </x-molecules.card>

    <!--begin::Modal Add Menu-->
    <x-molecules.modal id="kt_modal_add_menu" class="mw-650px">
        <x-slot:title>Tambah Menu</x-slot:title>
        <x-slot:body>
            <form id="kt_modal_add_menu_form" class="form" action="#" enctype="multipart/form-data">
                <div class="fv-row mb-7">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Image</label>
                    <div class="col-lg-9">
                        <div class="image-input image-input-outline" data-kt-image-input="true"
                            style="background-image: url('assets/media/svg/avatars/blank.svg')">
                            <div class="image-input-wrapper w-125px h-125px" style="background-image: none;"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                <i class="ki-duotone ki-pencil fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="file" name="image" id="add-image" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel image">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove image">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="form-text">Allowed file types: png, jpg, jpeg (max: 2MB)</div>
                    </div>
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" value="Nama" />
                    <x-atoms.input name="name" placeholder="Masukkan nama" />
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" value="Harga" />
                    <x-atoms.textarea name="description" placeholder="Masukkan deskripsi" row="3"></x-atoms.textarea>
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" value="Harga" />
                    <x-atoms.input type="number" name="price" placeholder="Masukkan harga" />
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label value="Kategori" class="mb-2 fs-6 fw-semibold" />
                    <x-atoms.select class="fw-bold" name="category" :items="$categories" value="id" select2="true"
                        data-placeholder="Pilih Kategori" data-allow-clear="true" data-hide-search="true" />
                </div>
                <div class="text-center pt-15">
                    <x-atoms.button type="reset" class="me-3" color="light"
                        data-kt-menus-modal-action="close">Close</x-atoms.button>
                    <x-atoms.button type="submit" id="submit_add_menu" color="primary"
                        data-url="{{ route('menus.store') }}" data-kt-menus-modal-action="submit">Submit</x-atoms.button>
                </div>
            </form>
        </x-slot:body>
    </x-molecules.modal>
    <!--end::Modal Add Menu-->

    <!--begin::Modal Edit Menu-->
    <x-molecules.modal id="kt_modal_edit_menu" class="mw-650px">
        <x-slot:title>Edit Menu</x-slot:title>
        <x-slot:body>
            <form id="kt_modal_edit_menu_form" class="form" method="POST" enctype="multipart/form-data">
                <div class="fv-row mb-7">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Image</label>
                    <div class="col-lg-9">
                        <div class="image-input image-input-outline" data-kt-image-input="true"
                            style="background-image: url('assets/media/svg/avatars/blank.svg')">
                            <div class="image-input-wrapper w-125px h-125px" style="background-image: none;"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                <i class="ki-duotone ki-pencil fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="file" name="image" id="edit-image" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel image">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove image">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="form-text">Allowed file types: png, jpg, jpeg (max: 2MB)</div>
                    </div>
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" value="Nama" />
                    <x-atoms.input name="name" placeholder="Masukkan nama" />
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" value="Harga" />
                    <x-atoms.textarea name="description" placeholder="Masukkan deskripsi"
                        row="3"></x-atoms.textarea>
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" value="Harga" />
                    <x-atoms.input type="number" name="price" placeholder="Masukkan harga" />
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label value="Kategori" class="mb-2 fs-6 fw-semibold" />
                    <x-atoms.select class="fw-bold" name="category" :items="$categories" value="id" select2="true"
                        data-placeholder="Pilih Kategori" data-allow-clear="true" data-hide-search="true" />
                </div>
                <div class="text-center pt-15">
                    <x-atoms.button type="reset" class="me-3" color="light"
                        data-kt-menus-modal-action="close">Close</x-atoms.button>
                    <x-atoms.button type="submit" id="submit_edit_menu" color="primary" data-id=""
                        data-kt-menus-modal-action="submit">Submit</x-atoms.button>
                </div>
            </form>
        </x-slot:body>
    </x-molecules.modal>
    <!--end::Modal Edit Menu-->

    @push('scripts')
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/custom/apps/menus/table.js') }}"></script>
        <script src="{{ asset('assets/js/custom/apps/menus/add.js') }}"></script>
        <script src="{{ asset('assets/js/custom/apps/menus/edit.js') }}"></script>
    @endpush
@endsection
