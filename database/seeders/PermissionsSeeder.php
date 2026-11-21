<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Models\Security\Module;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'web', 'description' => 'Administrador del sistema.']);
        $roleCustomer = Role::create(['name' => 'customer', 'guard_name' => 'web', 'description' => 'Cliente de la tienda en línea.']);
        $roleStaff = Role::create(['name' => 'staff', 'guard_name' => 'web', 'description' => 'Empleado de la tienda en línea.']);

        $admin = User::create([
            'name' => 'Admin',
            'lastname' => 'BullShop',
            'email' => 'serchypq04@gmail.com',
            'password' => bcrypt('[68~@[->#>]8!!|{9]_[[@$<-8?&<}~4'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole($roleAdmin);
        $staff = User::create([
            'name' => 'Staff',
            'lastname' => 'BullShop',
            'email' => 'bullshop.mex@gmail.com',
            'password' => bcrypt('4{3}*7#3&-<}-(|[{85]^,_748?..?.9'),
            'email_verified_at' => now(),
        ]);
        $staff->assignRole($roleStaff);
    /*
        $customer = User::create([
            'name' => 'Sergio',
            'lastname' => 'Peralta',
            'email' => 'serchypq04@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);
        $customer->assignRole($roleCustomer);*/

        // CREAR MODULOS PARA EL SISTEMA
        Module::create([
            'name' => 'Eventos',
            'description' => 'Modulo para gestionar los eventos de la tienda.',
            'key' => 'event',
        ]);


        // CREAR PERMISOS PARA ADMIN Y STAFF

        // Permisos para el módulo de Punto de Venta (POS)
        Permission::create(['name' => 'event.pos.index', 'guard_name' => 'web', 'description' => 'Permiso para acceder al Punto de Venta (POS).', 'module_key' => 'event']);
        Permission::create(['name' => 'event.pos.store', 'guard_name' => 'web', 'description' => 'Permiso para realizar ventas en el Punto de Venta (POS).', 'module_key' => 'event']);

        // Permisos para apartados de eventos
        Permission::create(['name' => 'order.staff.index', 'guard_name' => 'web', 'description' => 'Permiso para acceder a la lista de apartados de eventos.', 'module_key' => 'event']);
        Permission::create(['name' => 'order.staff.show', 'guard_name' => 'web', 'description' => 'Permiso para ver el detalle de un apartado de evento.', 'module_key' => 'event']);
        Permission::create(['name' => 'order.staff.update', 'guard_name' => 'web', 'description' => 'Permiso para confirmar pago y entrega.', 'module_key' => 'event']);
        Permission::create(['name' => 'order.staff.delete', 'guard_name' => 'web', 'description' => 'Permiso para cancelar pedidos.', 'module_key' => 'event']);

        $adminPermissions = Permission::all();
        $roleAdmin->syncPermissions($adminPermissions);

        $permissions = Permission::where('module_key', 'event')->get();

        $roleStaff->givePermissionTo([
            'event.pos.index',
            'event.pos.store',
            'order.staff.index',
            'order.staff.show',
            'order.staff.update',
        ]);
        
    }
}
