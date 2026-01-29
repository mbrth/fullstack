<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un utilisateur admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Créer un utilisateur normal de test
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Créer 5 utilisateurs supplémentaires
        $users = User::factory(5)->create();

        // Créer 50 avis pour différents utilisateurs
        \App\Models\Review::factory(50)->create([
            'user_id' => $users->random()->id,
        ]);

        // Créer quelques avis pour l'utilisateur de test
        \App\Models\Review::factory(10)->create([
            'user_id' => $testUser->id,
        ]);
    }
}
