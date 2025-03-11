<?php

namespace Database\Seeders;

use Chiiya\FilamentAccessControl\Database\Seeders\FilamentAccessControlSeeder;
use Chiiya\FilamentAccessControl\Enumerators\RoleName;
use Chiiya\FilamentAccessControl\Models\FilamentUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminDatabaseSeeder extends Seeder
{
    public static array $users = [
        [
            'first_name' => 'Elisha',
            'last_name' => 'Witte',
            'email' => 'hello@chiiya.moe',
        ],
        [
            'first_name' => 'Jonas',
            'last_name' => 'Emde',
            'email' => 'emde@mindtwo.de',
        ],
    ];

    public static array $permissions = [
        'sitemaps.view',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(FilamentAccessControlSeeder::class);

        /** @var Role $role */
        $role = Role::findByName(RoleName::SUPER_ADMIN, 'filament');

        foreach (self::$users as $user) {
            $password = config('site.admin_password');
            $admin = FilamentUser::query()->create(array_merge($user, [
                'password' => Hash::make($password ?: Str::random(40)),
                'expires_at' => now()->addMonths(12),
            ]));
            $admin->assignRole($role);
        }

        foreach (self::$permissions as $permission) {
            Permission::query()->create([
                'name' => $permission,
                'guard_name' => 'filament',
            ]);
            $role->givePermissionTo($permission);
        }
    }
}
