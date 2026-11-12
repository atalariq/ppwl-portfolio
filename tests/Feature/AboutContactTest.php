<?php

namespace Tests\Feature;

use App\Models\AboutProfile;
use App\Models\Contact;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_renders_profile_contacts_and_skills(): void
    {
        AboutProfile::current()->update([
            'full_name' => 'Atalariq Barra',
            'role' => 'Software Engineering Student',
            'summary' => 'A short summary.',
            'bio' => "First paragraph.\n\nSecond paragraph.",
        ]);

        Contact::create([
            'label' => 'Email',
            'value' => 'hello@example.com',
            'url' => 'mailto:hello@example.com',
            'icon' => 'mail',
            'sort_order' => 1,
        ]);

        Skill::create(['name' => 'Laravel', 'category' => 'Frameworks']);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Atalariq Barra')
            ->assertSee('First paragraph.')
            ->assertSee('hello@example.com')
            ->assertSee('mailto:hello@example.com', false)
            ->assertSee('Laravel')
            ->assertSee('Frameworks');
    }

    public function test_admin_can_update_the_about_profile(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->put(route('admin.about.update'), [
                'full_name' => 'Updated Name',
                'role' => 'Updated Role',
                'summary' => 'Updated summary.',
                'bio' => 'Updated bio.',
                'location' => 'Yogyakarta',
                'website_url' => 'https://example.com',
                'github_url' => 'https://github.com/example',
                'linkedin_url' => 'https://linkedin.com/in/example',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('about_profiles', [
            'full_name' => 'Updated Name',
            'role' => 'Updated Role',
        ]);
    }

    public function test_admin_can_create_update_and_delete_a_contact(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('admin.contacts.store'), [
            'label' => 'GitHub',
            'value' => 'github.com/example',
            'url' => 'https://github.com/example',
            'icon' => 'github',
            'sort_order' => 2,
        ])->assertRedirect(route('admin.about.edit'));

        $contact = Contact::where('label', 'GitHub')->firstOrFail();

        $this->put(route('admin.contacts.update', $contact), [
            'label' => 'GitHub Profile',
            'value' => 'github.com/example',
            'url' => 'https://github.com/example',
            'icon' => 'github',
            'sort_order' => 1,
        ])->assertRedirect(route('admin.about.edit'));

        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'label' => 'GitHub Profile']);

        $this->delete(route('admin.contacts.destroy', $contact))
            ->assertRedirect(route('admin.about.edit'));

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_contact_url_must_use_a_supported_scheme(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('admin.contacts.store'), [
            'label' => 'Bad Link',
            'value' => 'bad',
            'url' => 'javascript:alert(1)',
            'icon' => 'link',
        ])->assertSessionHasErrors('url');
    }

    public function test_non_admin_cannot_manage_about_or_contacts(): void
    {
        $contact = Contact::create([
            'label' => 'Email',
            'value' => 'hello@example.com',
            'url' => 'mailto:hello@example.com',
            'icon' => 'mail',
        ]);

        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get(route('admin.about.edit'))->assertForbidden();
        $this->actingAs($user)->delete(route('admin.contacts.destroy', $contact))->assertForbidden();
    }
}
