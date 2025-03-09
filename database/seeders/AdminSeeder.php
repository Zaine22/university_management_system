<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $this->createUsers();
        // $this->assignPermissionsToRoles();
    }

    private function createUsers(): void
    {
        $usersData = [
            [
                'name' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'password' => 'superadmin222',
                'role' => 'Super Admin',
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => '1111',
                'role' => 'Admin',
            ],
            [
                'name' => 'teacher',
                'email' => 'teacher@gmail.com',
                'password' => '1111',
                'role' => 'Teacher',
            ],
            [
                'name' => 'manager',
                'email' => 'manager@gmail.com',
                'password' => '1111',
                'role' => 'Manager',
            ],
            [
                'name' => 'finance',
                'email' => 'finance@gmail.com',
                'password' => '1111',
                'role' => 'Finance',
            ],
            [
                'name' => 'staff',
                'email' => 'staff@gmail.com',
                'password' => '1111',
                'role' => 'Office Staff',
            ],

        ];

        foreach ($usersData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($data['password']),
                'remember_token' => Str::random(10),
            ]);

            $user->assignRole($data['role']);
        }
    }

    private function assignPermissionsToRoles(): void
    {
        $rolesWithPermissions = [
            'Admin' => $this->assignAdminPermissions(),
            'Manager' => $this->assignManagerPermissions(),
            'Teacher' => $this->assignTeacherPermissions(),
            'Finance' => $this->assignFinancePermissions(),
            'Office Staff' => $this->assignStaffPermissions(),
        ];

        foreach ($rolesWithPermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $role->syncPermissions($permissions);
            }
        }
    }

    // Assign permissions to Admin role
    private function assignAdminPermissions()
    {
        $permissionInstance = new Permission;

        return [
            $permissionInstance->getSelectPermission('Achievement'),
            $permissionInstance->getSelectPermission('Attendance'),
            $permissionInstance->getSelectPermission('Batch'),
            $permissionInstance->getSelectPermission('Chapter'),
            $permissionInstance->getSelectPermission('Course'),
            $permissionInstance->getSelectPermission('Employee'),
            $permissionInstance->getSelectPermission('Enrollment'),
            $permissionInstance->getSelectPermission('Event'),
            $permissionInstance->getSelectPermission('Exam'),
            $permissionInstance->getSelectPermission('GradingRule'),
            $permissionInstance->getSelectPermission('Invoice'),
            $permissionInstance->getSelectPermission('Payment'),
            $permissionInstance->getSelectPermission('Result'),
            $permissionInstance->getSelectPermission('Rollcall'),
            $permissionInstance->getSelectPermission('Student'),
            $permissionInstance->getSelectPermission('Subject'),
            $permissionInstance->getSelectPermission('Teacher'),
            $permissionInstance->getSelectPermission('Timetable'),
            $permissionInstance->getSelectPermission('Transaction'),
        ];
    }

    // Assign permissions to Manager role
    private function assignManagerPermissions()
    {
        $permissionInstance = new Permission;

        return [
            $permissionInstance->getSelectPermission('Achievement'),
            $permissionInstance->getSelectPermission('Attendance'),
            $permissionInstance->getSelectPermission('Batch'),
            $permissionInstance->getSelectPermission('Chapter'),
            $permissionInstance->getSelectPermission('Course'),
            $permissionInstance->getSelectPermission('Employee'),
            $permissionInstance->getSelectPermission('Enrollment', ['view', 'create']),
            $permissionInstance->getSelectPermission('Event'),
            $permissionInstance->getSelectPermission('Exam', ['view', 'edit']),
            $permissionInstance->getSelectPermission('GradingRule'),
            $permissionInstance->getSelectPermission('Invoice', ['view']),
            $permissionInstance->getSelectPermission('Payment', ['view', 'create']),
            $permissionInstance->getSelectPermission('Result'),
            $permissionInstance->getSelectPermission('Rollcall'),
            $permissionInstance->getSelectPermission('Student'),
            $permissionInstance->getSelectPermission('Subject'),
            $permissionInstance->getSelectPermission('Teacher'),
            $permissionInstance->getSelectPermission('Timetable', ['view']),
            $permissionInstance->getSelectPermission('Transaction', ['view', 'create']),
        ];
    }

    // Assign permissions to Teacher role
    private function assignTeacherPermissions()
    {
        $permissionInstance = new Permission;

        return [
            $permissionInstance->getSelectPermission('Attendance', ['view', 'edit']),
            $permissionInstance->getSelectPermission('Batch', ['view']),
            $permissionInstance->getSelectPermission('Course', ['view']),
            $permissionInstance->getSelectPermission('Exam', ['view', 'edit']),
            $permissionInstance->getSelectPermission('Result', ['view']),
            $permissionInstance->getSelectPermission('Rollcall', ['view', 'edit']),
            $permissionInstance->getSelectPermission('Student', ['view']),
            $permissionInstance->getSelectPermission('Timetable', ['view']),
        ];
    }

    // Assign permissions to Finance role
    private function assignFinancePermissions()
    {
        $permissionInstance = new Permission;

        return [
            $permissionInstance->getSelectPermission('Attendance', ['view']),
            $permissionInstance->getSelectPermission('Batch', ['view']),
            $permissionInstance->getSelectPermission('Course', ['view']),
            $permissionInstance->getSelectPermission('Employee', ['view']),
            $permissionInstance->getSelectPermission('Invoice'),
            $permissionInstance->getSelectPermission('Payment', ['view', 'create']),
            $permissionInstance->getSelectPermission('Student', ['view']),
            $permissionInstance->getSelectPermission('Teacher', ['view']),
            $permissionInstance->getSelectPermission('Transaction', ['view', 'create']),
        ];
    }

    // Assign permissions to Office Staff role
    private function assignStaffPermissions()
    {
        $permissionInstance = new Permission;

        return [
            $permissionInstance->getSelectPermission('Achievement'),
            $permissionInstance->getSelectPermission('Attendance'),
            $permissionInstance->getSelectPermission('Batch', ['view']),
            $permissionInstance->getSelectPermission('Chapter'),
            $permissionInstance->getSelectPermission('Course', ['view']),
            $permissionInstance->getSelectPermission('Enrollment', ['view', 'create']),
            $permissionInstance->getSelectPermission('Event'),
            $permissionInstance->getSelectPermission('Exam'),
            $permissionInstance->getSelectPermission('GradingRule'),
            $permissionInstance->getSelectPermission('Invoice', ['view']),
            $permissionInstance->getSelectPermission('Payment', ['view', 'create']),
            $permissionInstance->getSelectPermission('Result', ['view']),
            $permissionInstance->getSelectPermission('Rollcall'),
            $permissionInstance->getSelectPermission('Student'),
            $permissionInstance->getSelectPermission('Subject'),
            $permissionInstance->getSelectPermission('Timetable'),
            $permissionInstance->getSelectPermission('Transaction', ['view', 'create']),
        ];
    }
}