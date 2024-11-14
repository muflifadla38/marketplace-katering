@extends('layouts.auth')
@push('token')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <style>
        body {
            background-image: url('/assets/media/auth/bg10.jpeg');
        }

        [data-bs-theme="dark"] body {
            background-image: url('/assets/media/auth/bg10-dark.jpeg');
        }
    </style>
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-lg-row-fluid">
            <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                    src="{{ asset('assets/media/auth/agency.png') }}" alt="Register Image" />
                <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">Fast, Efficient and Productive</h1>
                <div class="text-gray-600 fs-base text-center fw-semibold">In this kind of post,
                    <a href="#" class="opacity-75-hover text-primary me-1">the blogger</a>introduces a person they’ve
                    interviewed
                    <br />and provides some background information about
                    <a href="#" class="opacity-75-hover text-primary me-1">the interviewee</a>and their
                    <br />work following this is a transcript of the interview.
                </div>
            </div>
        </div>
        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
            <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                    <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                        <form class="form w-100" id="kt_sign_up_form" data-kt-redirect-url="{{ route('dashboard.index') }}"
                            enctype="multipart/form-data">
                            <div class="text-center mb-11">
                                <h1 class="text-dark fw-bolder mb-3">Register</h1>
                            </div>
                            <div class="fv- mb-6">
                                <label class="col-lg-3 col-form-label fw-semibold fs-6">Profile Image</label>
                                <div class="col-lg-9">
                                    <div class="image-input image-input-outline" data-kt-image-input="true"
                                        style="background-image: url('assets/media/svg/avatars/blank.svg')">
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: none;">
                                        </div>
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="Change avatar">
                                            <i class="ki-duotone ki-pencil fs-7">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <input type="file" name="image" id="image"
                                                accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="avatar_remove" />
                                        </label>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            title="Cancel avatar">
                                            <i class="ki-duotone ki-cross fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            title="Remove avatar">
                                            <i class="ki-duotone ki-cross fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <div class="form-text">Allowed file types: png, jpg, jpeg (max: 2MB)</div>
                                </div>
                            </div>
                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Name" name="name" autocomplete="off"
                                    class="form-control bg-transparent  @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" required />

                                @error('name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($role == 'merchant')
                                <div class="fv-row mb-8">
                                    <input type="text" placeholder="Phone" name="phone" autocomplete="off"
                                        class="form-control bg-transparent  @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" required />

                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="fv-row mb-8">
                                    <input type="text" placeholder="Address" name="address" autocomplete="off"
                                        class="form-control bg-transparent  @error('address') is-invalid @enderror"
                                        value="{{ old('address') }}" required />

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="fv-row mb-8">
                                    <x-atoms.textarea placeholder="Description" name="description"
                                        required></x-atoms.textarea>
                                    {{ old('description') }}
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Email" name="email" autocomplete="off"
                                    class="form-control bg-transparent  @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required />

                                @error('email')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="fv-row mb-8" data-kt-password-meter="true">
                                <div class="mb-1">
                                    <div class="mb-2">
                                        <input type="password" placeholder="Password" name="password" autocomplete="off"
                                            class="form-control bg-transparent  @error('password') is-invalid @enderror"
                                            required />

                                        @error('password')
                                            <span class="invalid-feedback mb-2" role="alert">{{ $message }}</span>
                                        @enderror

                                        <span
                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            data-kt-password-meter-control="visibility">
                                            <i class="ki-duotone ki-eye-slash fs-2"></i>
                                            <i class="ki-duotone ki-eye fs-2 d-none"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center mb-3"
                                        data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                    </div>
                                </div>
                                <div class="text-muted">Use 8 or more characters.
                                </div>
                            </div>
                            <div class="fv-row mb-8">
                                <input type="password" placeholder="Password Confirmation" name="password_confirmation"
                                    autocomplete="off"
                                    class="form-control bg-transparent  @error('password_confirmation') is-invalid @enderror"
                                    value="{{ old('password_confirmation') }}" required />

                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
                                    <span class="indicator-label">Register</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <div class="text-gray-500 text-center fw-semibold fs-6">Already have an Account?
                                <a href="{{ route('auth.login') }}" class="link-primary">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/custom/authentication/register.js') }}"></script>
    @endpush
@endsection
