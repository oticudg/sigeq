<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();
        
        App\User::create([
            'name'      => 'Root',
            'last_name' => 'Root',
            'num_id'    => '99999999',
            'email'     => 'root@sahum.gob.ve',
            'password'  => bcrypt('secret'),
        ]);

        App\Models\Permisologia\Role::create([
            'name'          => 'Administrador',
            'slug'          => 'SuperAdmin',
            'description'   => 'Acceso total a los Modulos.',
            'from_at'       => \Carbon\Carbon::parse('08:00:00'),
            'to_at'         => \Carbon\Carbon::parse('17:00:00'),
            'special'       => 'all-access'
        ]);

        DB::table('role_user')->insert([
            'user_id' => 1,
            'role_id' => 1
        ]);

        $num = App\Models\Permisologia\Permission::all()->count();
        for ($i = 1; $i <= $num; $i++) { 
            DB::table('permission_role')->insert([
                'role_id' => 1,
                'permission_id' => $i
            ]);
        }

        for ($i = 1; $i <= $num; $i++) { 
            DB::table('permission_user')->insert([
                'user_id' => 1,
                'permission_id' => $i
            ]);
        }
    }
}
