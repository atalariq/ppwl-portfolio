<?php

namespace App\Http\Controllers;

use App\Models\AboutProfile;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function edit(): View
    {
        return view('admin.about.edit', [
            'profile' => AboutProfile::current(),
            'contacts' => Contact::query()->ordered()->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'role' => 'required|string|max:150',
            'summary' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'location' => 'nullable|string|max:150',
            'website_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
        ]);

        AboutProfile::current()->update($validated);

        return back()->with('success', 'About page updated.');
    }
}
