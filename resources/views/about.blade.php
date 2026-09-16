@extends('layouts.app')

@section('title', 'About — Portfolio')

@section('content')
    <h1 class="text-2xl font-bold">About</h1>
    <p class="mt-4">I am Atalariq Barra, Software Engineering student at UGM. My technical journey started when I tried installing Linux, accidentally wiped Windows, and had to learn the command line to fix my machine.</p>
    <p class="mt-2">I apply that background to managing WordPress and CMS platforms, focusing on site reliability, speed optimization, and clean publishing workflows.</p>

    <p class="text-sm text-gray-600">Email: {{ $email }}</p>
    <p class="text-sm text-gray-600">Contact: {{ $contact }}</p>

    <h2 class="mt-6 text-xl font-semibold">Skills</h2>
    <ul class="mt-2 space-y-2">
        @foreach($skills as $category => $value)
            <li><span class="font-medium">{{ $category }}:</span> {{ $value }}</li>
        @endforeach
    </ul>
@endsection
