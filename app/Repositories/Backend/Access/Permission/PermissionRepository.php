<?php

namespace App\Repositories\Backend\Access\Permission;

use App\Repositories\BaseRepository;
use App\Models\Access\Permission\Permission;
use Session;

/**
 * Class PermissionRepository.
 */
class PermissionRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Permission::class;

/**
 * limit the permissions visible to the end user to just ones that have permissions.client_visible set to 1.
 * @version 1.0
 * @author lutzjw
 * @date:   2018-05-01T10:51:52-0500
 * @since   1.0
 * @param   string                   $type     permission type to find (default = site)
 * @param   string                   $order_by which column to sort by
 * @param   string                   $sort     sort in asscending or descending order
 * @return  permissions              collection of permissions.
 */

    public function getAllPermissions($type = '1', $order_by = 'display_name', $sort = 'asc')
    {
        // if a type is included, only spit back that types permissions (defaults to site)

        return $this->query()
            ->where('client_visible', 1)
            ->where('permission_type_id', $type)
            ->orderBy($order_by, $sort)
            ->get();
    }

    // returns specified users site permissions
    public function getUserSitePermissions($user_id) {
        
        $sitePermissions = $this->query()
        ->selectRaw('permission_role.permission_id AS "permission_id", permissions.display_name AS "permission_name"')
        ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id' )
        ->join('roles', 'permission_role.role_id', '=', 'roles.id' )
        ->join('role_user', 'role_user.role_id', '=', 'roles.id' )
        ->where('role_user.user_id', $user_id)
        ->where('permissions.permission_type_id', 1)
        ->where('roles.site_id', SESSION::get('site_id'))
        ->distinct();
        $sitePermissions->get();

        return $sitePermissions;
    }


    // returns specified users course permissions, if a course_id is included - return just that courses
    public function getUserCoursePermissions($user_id, $course_id = null) {
        
        $coursePermissions = $this->query()
        ->selectRaw('permissions.id AS "permission_id", permissions.display_name AS "permission_name", courses.name AS "course_name" ')
        ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id' )
        ->join('roles', 'permission_role.role_id', '=', 'roles.id' )
        ->join('role_user', 'role_user.role_id', '=', 'roles.id' )
        ->join('courses', 'courses.id', '=', 'role_user.course_id' )
        ->where('role_user.user_id', $user_id)
        ->where('permissions.permission_type_id', 2)
        ->where('roles.site_id', SESSION::get('site_id'));

        if($course_id) {
            $coursePermissions->where('courses.id', $course_id);
        }

        $coursePermissions->get();

        return $coursePermissions;
    }


    // returns specified users event permissions, if a event_id is included - return just that events
    public function getUserEventPermissions($user_id, $event_id = null) {
        
        $eventPermissions = $this->query()
        ->selectRaw('permissions.id AS "permission_id", permissions.display_name AS "permission_name", events.start_time AS "event_start_time", courses.name AS "course_name" ')
        ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id' )
        ->join('roles', 'permission_role.role_id', '=', 'roles.id' )
        ->join('event_user', 'event_user.role_id', '=', 'roles.id' )
        ->join('events', 'event_user.event_id', '=', 'events.id' )
        ->join('course_instances', 'course_instances.id', '=', 'events.course_instance_id' )
        ->join('courses', 'courses.id', '=', 'course_instances.course_id' )
        ->where('event_user.user_id', $user_id)
        ->where('permissions.permission_type_id', 3)
        ->whereNull('event_user.deleted_at')
        ->where('roles.site_id', SESSION::get('site_id'));

        if($event_id) {
            $eventPermissions->where('courses.id', $event_id);
        }

        $eventPermissions->get();

        return $eventPermissions;
    }
}
