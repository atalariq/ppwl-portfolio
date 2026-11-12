<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            ['label' => 'Email', 'value' => 'atalariq.dev@outlook.com', 'url' => 'mailto:atalariq.dev@outlook.com', 'icon' => 'mail', 'sort_order' => 1],
            ['label' => 'WhatsApp', 'value' => '+62 859-1069-87796', 'url' => 'https://wa.me/62859106987796', 'icon' => 'message-circle', 'sort_order' => 2],
            ['label' => 'GitHub', 'value' => 'github.com/atalariq', 'url' => 'https://github.com/atalariq', 'icon' => 'github', 'sort_order' => 3],
            ['label' => 'LinkedIn', 'value' => 'linkedin.com/in/atalariq', 'url' => 'https://linkedin.com/in/atalariq', 'icon' => 'linkedin', 'sort_order' => 4],
            ['label' => 'Website', 'value' => 'atalariq.dev', 'url' => 'https://atalariq.dev', 'icon' => 'globe', 'sort_order' => 5],
            ['label' => 'Instagram', 'value' => '@atalariq.dev', 'url' => 'https://instagram.com/atalariq.dev', 'icon' => 'instagram', 'sort_order' => 6],
        ];

        foreach ($contacts as $contact) {
            Contact::updateOrCreate(
                ['label' => $contact['label']],
                $contact,
            );
        }
    }
}
