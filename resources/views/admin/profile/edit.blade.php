@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
    <x-ui.page-header title="Profile" description="Manage your account details, avatar, and password." />

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <div class="card card-body">
            @include('admin.profile.partials.update-profile-information-form')
        </div>

        <div class="card card-body">
            @include('admin.profile.partials.update-password-form')
        </div>
    </div>
@endsection
