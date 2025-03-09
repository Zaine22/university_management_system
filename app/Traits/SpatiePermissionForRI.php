<?php

namespace App\Traits;

use Spatie\Permission\Models\Permission;

trait SpatiePermissionForRI
{
    public function assignAdminPermissions()
    {
        $permissionInstance = new Permission;

        $permissions = [
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

        $this->syncPermissions($permissions);
    }

    public function assignManagerPermissions()
    {
        $permissionInstance = new Permission;

        $permissions = [
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

        $this->syncPermissions($permissions);
    }

    public function assignTeacherPermissions()
    {
        $permissionInstance = new Permission;

        $permissions = [
            $permissionInstance->getSelectPermission('Attendance', ['view', 'edit']),
            $permissionInstance->getSelectPermission('Batch', ['view']),
            $permissionInstance->getSelectPermission('Course', ['view']),
            $permissionInstance->getSelectPermission('Exam', ['view', 'edit']),
            $permissionInstance->getSelectPermission('Result', ['view']),
            $permissionInstance->getSelectPermission('Rollcall', ['view', 'edit']),
            $permissionInstance->getSelectPermission('Student', ['view']),
            $permissionInstance->getSelectPermission('Timetable', ['view']),
        ];

        $this->syncPermissions($permissions);
    }

    public function assignFinancePermissions()
    {
        $permissionInstance = new Permission;

        $permissions = [
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

        $this->syncPermissions($permissions);
    }

    public function assignStaffPermissions()
    {
        $permissionInstance = new Permission;

        $permissions = [
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

        $this->syncPermissions($permissions);
    }
}
