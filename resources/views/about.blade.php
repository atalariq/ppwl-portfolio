@extends('layouts.app')

@section('title', 'About — Portfolio')

@section('content')
    <x-ui.page-header title="About" description="Software engineering student at UGM, focused on reliable web systems and clean publishing workflows." />

    <div class="mt-8 grid gap-8 lg:grid-cols-[1.4fr_1fr]">
        <div class="space-y-4 text-base leading-relaxed">
            <p>
                I am Atalariq Barra, a Software Engineering student at Universitas Gadjah Mada. My technical
                journey started when I tried installing Linux, accidentally wiped Windows, and had to learn
                the command line to fix my machine.
            </p>
            <p>
                I apply that background to managing WordPress and CMS platforms, focusing on site reliability,
                speed optimization, and clean publishing workflows.
            </p>
        </div>

        <aside class="card card-body">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Contact</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div>
                    <dt class="text-muted">Email</dt>
                    <dd class="font-medium">{{ $email }}</dd>
                </div>
                <div>
                    <dt class="text-muted">Contact</dt>
                    <dd class="font-medium">{{ $contact }}</dd>
                </div>
            </dl>
        </aside>
    </div>

    <section class="mt-12">
        <h2 class="text-xl font-semibold">Skills</h2>
        <dl class="mt-4 grid gap-4 sm:grid-cols-2">
            @foreach ($skills as $category => $value)
                <div class="card card-body">
                    <dt class="text-sm font-medium text-muted">{{ $category }}</dt>
                    <dd class="mt-1">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </section>
@endsection
