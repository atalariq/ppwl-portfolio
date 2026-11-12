@extends('layouts.admin')

@section('title', 'Add Contact')

@section('content')
    <x-ui.page-header title="Add contact" description="Add a clickable contact method to the About page." />

    <form action="{{ route('admin.contacts.store') }}" method="POST" class="mt-6 max-w-2xl space-y-5">
        @csrf
        @include('admin.contacts.partials.form', ['contact' => null])
    </form>
@endsection
