@extends('layouts.app')

@section('title', 'Education — Portfolio')

@section('content')
    <h1 class="text-2xl font-bold">Education</h1>

    @foreach($educations as $edu)
        <div class="mt-4 rounded border bg-white p-4">
            <div class="flex items-center justify-between gap-4">
                <h2 class="font-semibold">{{ $edu['institution'] }}</h2>
                <span class="text-sm text-gray-500">{{ $edu['period'] }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $edu['program'] }}</p>
            <ul class="mt-2 list-disc pl-5 text-sm">
                @foreach($edu['details'] as $d)
                    <li>{{ $d }}</li>
                @endforeach
            </ul>
        </div>
    @endforeach

    <h2 class="mt-8 text-xl font-semibold">Organization Experience</h2>

    @foreach($organizations as $org)
        <div class="mt-4 rounded border bg-white p-4">
            <div class="flex items-center justify-between gap-4">
                <h3 class="font-semibold">{{ $org['name'] }}</h3>
                <span class="text-sm text-gray-500">{{ $org['period'] }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $org['role'] }}</p>
            <ul class="mt-2 list-disc pl-5 text-sm">
                @foreach($org['details'] as $d)
                    <li>{{ $d }}</li>
                @endforeach
            </ul>
        </div>
    @endforeach
@endsection
