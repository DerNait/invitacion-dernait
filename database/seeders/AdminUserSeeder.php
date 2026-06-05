<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Lista de administradores. Todos pueden entrar al panel y todos reciben
        // el aviso por correo cuando alguien confirma.
        // Para agregar más, añade otra entrada aquí (o llena las variables ADMIN*_ en .env).
        $admins = [
            [
                'name' => env('ADMIN_NAME', 'Anfitrión'),
                'email' => env('ADMIN_EMAIL'),
                'password' => env('ADMIN_PASSWORD', 'password'),
            ],
            [
                'name' => env('ADMIN2_NAME', 'Anfitrión 2'),
                'email' => env('ADMIN2_EMAIL'),
                'password' => env('ADMIN2_PASSWORD', 'password'),
            ],
        ];

        foreach ($admins as $admin) {
            // Se omiten entradas sin correo.
            if (empty($admin['email'])) {
                continue;
            }

            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make($admin['password']),
                ]
            );

            $this->command->info("Admin listo: {$admin['email']}");
        }
    }
}
