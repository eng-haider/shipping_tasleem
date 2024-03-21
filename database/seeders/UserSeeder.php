<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //add super admin user   
        $superadmin = User::where('phone', '07700000000')->first();
        if(!$superadmin){
            $superadmin = User::create([
                'name' => 'super admin',
                'phone' => '07700000000'
            ]);
            //assign super admin role to super admin user
            $superadmin->assignRole('super-admin');
        }
    }
}
