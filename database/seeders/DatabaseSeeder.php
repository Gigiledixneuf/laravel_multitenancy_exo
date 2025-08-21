<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $tenants = [
            ['id' => 'google',   'name' => 'Google',   'domain' => 'google.localhost'],
            ['id' => 'meta',     'name' => 'Meta',     'domain' => 'meta.localhost'],
            ['id' => 'facebook', 'name' => 'Facebook', 'domain' => 'facebook.localhost'],
            ['id' => 'tinder',   'name' => 'Tinder',   'domain' => 'tinder.localhost'],
            ['id' => 'apple',    'name' => 'Apple',    'domain' => 'apple.localhost'],
            ['id' => 'amazon',   'name' => 'Amazon',   'domain' => 'amazon.localhost'],
            ['id' => 'netflix',  'name' => 'Netflix',  'domain' => 'netflix.localhost'],
            ['id' => 'spotify',  'name' => 'Spotify',  'domain' => 'spotify.localhost'],
            ['id' => 'twitter',  'name' => 'Twitter',  'domain' => 'twitter.localhost'],
            ['id' => 'airbnb',   'name' => 'Airbnb',   'domain' => 'airbnb.localhost'],
            ['id' => 'slack',    'name' => 'Slack',    'domain' => 'slack.localhost'],
            ['id' => 'uber',     'name' => 'Uber',     'domain' => 'uber.localhost'],
            ['id' => 'microsoft','name' => 'Microsoft','domain' => 'microsoft.localhost'],
            ['id' => 'linkedin', 'name' => 'LinkedIn', 'domain' => 'linkedin.localhost'],
            ['id' => 'paypal',   'name' => 'PayPal',   'domain' => 'paypal.localhost'],
            ['id' => 'tiktok',   'name' => 'TikTok',   'domain' => 'tiktok.localhost'],
            ['id' => 'reddit',   'name' => 'Reddit',   'domain' => 'reddit.localhost'],
            ['id' => 'discord',  'name' => 'Discord',  'domain' => 'discord.localhost'],
            ['id' => 'snapchat', 'name' => 'Snapchat', 'domain' => 'snapchat.localhost'],
            ['id' => 'youtube',  'name' => 'YouTube',  'domain' => 'youtube.localhost'],
        ];

        foreach ($tenants as $tenantData) {
            $tenant = Tenant::create([
                'id'   => $tenantData['id'],
                'name' => $tenantData['name'],
            ]);

            $tenant->domains()->create([
                'domain'    => $tenantData['domain'],
                'tenant_id' => $tenant->id,
            ]);

            // Créer 10 posts pour ce tenant
            $tenant->run(function () use ($faker) {
                for ($i = 0; $i < 10; $i++) {
                    Post::create([
                        'name'        => $faker->sentence(3),
                        'description' => $faker->sentence(6),
                        'content'     => $faker->paragraph(3),
                    ]);
                }
            });
        }
    }
}
