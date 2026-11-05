@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
    <x-ui.page-header title="Profil saya" description="Perbarui identitas, avatar, dan password akun pengelola." />

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <x-ui.card title="Informasi profil">
            @include('post.profile.partials.update-profile-information-form')
        </x-ui.card>

        <x-ui.card title="Ubah password">
            @include('post.profile.partials.update-password-form')
        </x-ui.card>
    </div>
@endsection
