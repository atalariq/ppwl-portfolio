@extends('layouts.admin')

@section('title', 'Styleguide')

@section('content')
    <x-ui.page-header title="Styleguide" description="Komponen dan token yang dipakai di seluruh halaman." />

    <div class="mt-8 space-y-8">
        <x-ui.card title="Buttons">
            <div class="flex flex-wrap gap-3">
                <x-ui.button>Primary</x-ui.button>
                <x-ui.button variant="secondary">Secondary</x-ui.button>
                <x-ui.button variant="danger">Danger</x-ui.button>
                <x-ui.button variant="ghost">Ghost</x-ui.button>
                <x-ui.button size="sm">Small</x-ui.button>
            </div>
        </x-ui.card>

        <x-ui.card title="Badges & alerts">
            <div class="flex flex-wrap gap-2">
                <x-ui.badge variant="success">Published</x-ui.badge>
                <x-ui.badge variant="warning">Draft</x-ui.badge>
                <x-ui.badge>Neutral</x-ui.badge>
            </div>
            <div class="mt-4 space-y-3">
                <x-ui.alert variant="success">Perubahan berhasil disimpan.</x-ui.alert>
                <x-ui.alert variant="info">Informasi netral untuk pengguna.</x-ui.alert>
                <x-ui.alert variant="danger">Terjadi kesalahan validasi.</x-ui.alert>
            </div>
        </x-ui.card>

        <x-ui.card title="Form fields">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-ui.field label="Nama" name="demo-name" hint="Contoh input teks.">
                    <input id="demo-name" class="input" type="text" placeholder="Atalariq">
                </x-ui.field>
                <x-ui.field label="Status" name="demo-status">
                    <select id="demo-status" class="select">
                        <option>Published</option>
                        <option>Draft</option>
                    </select>
                </x-ui.field>
            </div>
        </x-ui.card>

        <div class="grid gap-4 sm:grid-cols-3">
            <x-ui.stat label="Projects" :value="31" />
            <x-ui.stat label="Published" :value="23" tone="text-success" />
            <x-ui.stat label="Draft" :value="8" tone="text-warning" />
        </div>

        <x-ui.card title="Table & empty state">
            <table class="table">
                <thead><tr><th>Project</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td>Cloud Note API</td><td><x-ui.badge variant="success">Published</x-ui.badge></td></tr>
                    <tr><td>Nyalara</td><td><x-ui.badge variant="warning">Draft</x-ui.badge></td></tr>
                </tbody>
            </table>
            <x-ui.empty-state class="mt-6" title="Belum ada data" description="Empty state dipakai saat query tidak menghasilkan baris." />
        </x-ui.card>
    </div>
@endsection
