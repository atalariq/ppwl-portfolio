<?php

namespace App\Http\Controllers;

use App\Jobs\SendContactNotification;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function create(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|min:10|max:5000',
        ]);

        $contactMessage = ContactMessage::create($validated);

        SendContactNotification::dispatch($contactMessage);

        return back()->with('success', 'Pesan berhasil dikirim. Terima kasih!');
    }
}
