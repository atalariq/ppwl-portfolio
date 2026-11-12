<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('admin.contacts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Contact::create($this->validateContact($request));

        return redirect()->route('admin.about.edit')->with('success', 'Contact added.');
    }

    public function edit(Contact $contact): View
    {
        return view('admin.contacts.edit', ['contact' => $contact]);
    }

    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $contact->update($this->validateContact($request));

        return redirect()->route('admin.about.edit')->with('success', 'Contact updated.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.about.edit')->with('success', 'Contact deleted.');
    }

    private function validateContact(Request $request): array
    {
        return $request->validate([
            'label' => 'required|string|max:100',
            'value' => 'required|string|max:255',
            'url' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! preg_match('/^(https?:\/\/|mailto:|tel:)/i', (string) $value)) {
                        $fail('The :attribute must start with http, https, mailto, or tel.');
                    }
                },
            ],
            'icon' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);
    }
}
