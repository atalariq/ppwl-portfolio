@extends('layouts.admin')

@section('title', 'Edit Contact')

@section('content')
    <x-ui.page-header title="Edit contact" :description="$contact->label" />

    <form action="{{ route('admin.contacts.update', $contact) }}" method="POST" class="mt-6 max-w-2xl space-y-5">
        @csrf
        @method('PUT')
        @include('admin.contacts.partials.form', ['contact' => $contact])
    </form>
@endsection
