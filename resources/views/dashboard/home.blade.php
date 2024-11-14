@extends('layouts.dashboard')

@section('content')
@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">{{ __('You are logged in!') }}</div>
    </div>
    <div class="card-body py-4">This is dashboard page</div>
</div>
@endsection
