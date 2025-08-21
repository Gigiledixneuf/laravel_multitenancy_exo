<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant1 = Tenant::create(['id' => 'meta', 'name' => 'Meta']);
        $tenant1->domains()->create(['domain' => 'meta.localhost', 'tenant_id' => $tenant1->id]);

        $tenant2 = Tenant::create(['id' => 'google', 'name' => 'Google']);
        $tenant2->domains()->create(['domain' => 'google.localhost', 'tenant_id' => $tenant2->id]);

    }
}
