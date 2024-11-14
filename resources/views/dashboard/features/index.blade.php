@extends('layouts.dashboard')
@push('token')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
  @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
  @endif

  <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
    <div class="col-md-4">
      <x-molecules.card class="card-flush h-md-100">
        <x-slot:header class="border-0">
          <x-slot:title class="text-gray-900 fw-bold">Dashboard Features</x-slot:title>
        </x-slot:header>
        <x-slot:body class="pt-2">
          @foreach ($features as $feature)
            @continue($feature->name == 'feature')

            <div class="mb-8 d-flex align-items-center">
              <x-atoms.bullet class="h-40px" type="vertical" />
              <x-atoms.checkbox class="mx-5" checked="{{ $feature->active }}">
                <x-slot:input name="{{ $feature->name }}" value="{{ $feature->name }}"></x-slot:input>
              </x-atoms.checkbox>
              <div class="flex-grow-1">
                <a href="#"
                  class="text-gray-800 text-hover-primary fw-bold fs-6 text-capitalize">{{ str_replace('-', ' ', $feature->name) }}</a>
              </div>
            </div>
          @endforeach
        </x-slot:body>
      </x-molecules.card>
    </div>
  </div>

  <!--begin::Toast-->
  <x-molecules.toast />
  <!--end::Toast-->

  @push('scripts')
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('js/custom/apps/setting/features.js') }}"></script>
    <!--end::Custom Javascript-->
  @endpush
@endsection
