@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg bg-white p-6 shadow-sm">
            @include('post.profile.partials.update-profile-information-form')
        </div>

        <div class="rounded-lg bg-white p-6 shadow-sm">
            @include('post.profile.partials.update-password-form')
        </div>
    </div>
@endsection
