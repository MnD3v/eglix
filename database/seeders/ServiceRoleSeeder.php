<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceRole;

class ServiceRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'MC (Maître de Cérémonie)',
                'description' => 'Dirige le culte et coordonne les différentes parties',
                'color' => '#FF2600',
                'is_active' => true,
            ],
            [
                'name' => 'Prédicateur',
                'description' => 'Prêche le message principal du culte',
                'color' => '#22C55E',
                'is_active' => true,
            ],
            [
                'name' => 'Lecteur',
                'description' => 'Lit les passages bibliques pendant le culte',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Prieur d\'ouverture',
                'description' => 'Dirige la prière d\'ouverture du culte',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Prieur de clôture',
                'description' => 'Dirige la prière de clôture du culte',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Chorale',
                'description' => 'Dirige les chants et la louange',
                'color' => '#EC4899',
                'is_active' => true,
            ],
            [
                'name' => 'Accompagnateur musical',
                'description' => 'Joue la musique d\'accompagnement',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Accueil',
                'description' => 'Accueille les fidèles à l\'entrée',
                'color' => '#6B7280',
                'is_active' => true,
            ],
            [
                'name' => 'Collecte',
                'description' => 'Collecte les offrandes pendant le culte',
                'color' => '#F97316',
                'is_active' => true,
            ],
            [
                'name' => 'Technique',
                'description' => 'Gère la sonorisation et l\'éclairage',
                'color' => '#6366F1',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            ServiceRole::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}