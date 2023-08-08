<?php

namespace App\Http\Controllers\Course;

use App\Models\Course\Course;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\Access\RoleUser\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Course\StoreCourseUserRequest;
use App\Http\Requests\Course\StoreCourseUserCatalogRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Events\Frontend\Course\UserAddedToCourse;
use App\Events\Frontend\Course\DropUserFromCourse;
use App\Models\Access\Role\Role;
use Auth;
use Storage;
use Session;



class CourseUsersController extends Controller
{

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }


    // returns the course roles page from backend courses page
    public function users($course_id)
    {
        $course = Course::find($course_id);

        // set the roles box to course roles.
        $courseRoles = $this->roles->getRoles(2);

        return view('courses.users', compact('course', 'courseRoles'));
    }

    // returns the course roles page from catalog
    public function catalogUsers($course_id, $role_id=null, Request $request)
    {
        $course = Course::find($course_id);

        $pageFrom= $request->get('page');

        //check to see if the course id in the URL was valid before checking permissions (it will fail on invalid id)
        if($course)
        {
            //user must have add-people-to-courses permission at site or course
            if ($course->hasSiteCoursePermissions(['add-people-to-courses'], ['course-add-people-to-courses']))
            {
                // set the roles box to course roles.
                $courseRoles = $this->roles->getRoles(2);

                return view('courses.catalogUsers', compact('course', 'role_id', 'courseRoles', 'pageFrom'));
            }
            else
            {
                return redirect()->route('mycourses')->withErrors(trans('alerts.backend.courseusers.access'));
            }

        }
        else
        {
            return redirect()->route('mycourses')->withErrors(trans('alerts.backend.courseusers.invalid'));
        }

    }


    // Typeahead search box data
    // This pulls username/email from the active users list.
    public function usersData(Request $request)
    {
        $searchString = (!empty($request->get('q'))) ? strtolower($request->get('q')) : null;
        
        if (!isset($searchString)) {
            die('Invalid query.');
        }

        // this replaces spacing with a %wildcard to search first_name last_name
        $searchString = str_replace(' ', '%', $searchString);

        $usersQuery = User::active(true)
            ->where(function($query) use ($searchString){
                return $query
                    ->where('first_name', 'like', '%' . $searchString .'%')
                    ->orWhere('last_name', 'like', '%' . $searchString .'%')
                    ->orWhere('email', 'like', '%' . $searchString .'%');
            })
            ->get();

        foreach ($usersQuery as $usersList) {
            $user['id'] = $usersList->id;
            $user['name'] = $usersList->first_name .' '. $usersList->last_name;
            $user['email'] = $usersList->email;
            $databaseUsers[] = $user;
        }
        
        $status = true;
        
        // no result were found
        if (empty($databaseUsers)) {
            $status = false;
        }

        // build json data for Typeahead
        $users = json_encode(array(
            "status" => $status,
            "error"  => null,
            "data"   => array(
            "user"   => $databaseUsers,
            )
        ));

        return $users;
    }


    // pulls data for Datatable
    public function courseUsersTableData(Request $request)
    {
        $course = Course::find($request->get('course_id'));

        if(!$course->hasSiteCoursePermissions(['add-people-to-courses'], ['course-add-people-to-courses'])) return redirect()->back()->withErrors(trans('alerts.backend.courseusers.access'));

        $users = User::active(true)
            ->selectRaw('roles.name as "role_name", role_user.id as "role_user_id"')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->whereExists(function ($query) use ($request) {
                $query->select(DB::raw(1))
                    ->from('permission_role')
                    ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                    ->whereRaw('permission_role.role_id = roles.id')
                    ->where('permission_type_id', 2)
                    ->where('role_user.course_id', $request->get('course_id'));
            })
            ->where('all', '!=', 1);

        return DataTables::of($users)
            ->addColumn('id', function ($users) {
                return $users->role_user_id;
            })
            ->addColumn('role', function ($users) {
                return $users->role_name;
            })
            ->addColumn('name', function ($users) {
                return $users->first_name .' '. $users->last_name;
            })
            ->addColumn('first_name', function ($users) {
                return $users->first_name;
            })
            ->addColumn('last_name', function ($users) {
                return $users->last_name;
            })
            ->addColumn('email', function ($users) {
                return $users->email;
            })
            ->addColumn('actions', function ($users) {
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                <a href="/course/users/delete/'.$users->role_user_id.'" name="delete" class="btn btn-sm btn-danger">
                <i class="fas fa-lg fa-trash"></i></a></span>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    // add user to db
    // MITCKS TODO: figure out when this is called? It seems redundant of storeCatalog below and no permissions checked?
    public function store(StoreCourseUserRequest $request)
    {
        $roleUser = RoleUser::updateOrCreate(
            ['course_id' => $request['course_id'], 'user_id' => $request['user_id'], 'role_id' => $request['role_id']]
        );

        // Create Event - Send Email: Add User to Course
        event(new UserAddedToCourse(User::find($roleUser->user_id), Course::find($roleUser->course_id), Role::find($roleUser->role_id)));
        return redirect()->back()->withFlashSuccess(trans('alerts.backend.courseusers.created'));
    }

    // delete user from db
    public function delete($id)
    {
        $roleUser = RoleUser::find($id);
        $course = Course::find($roleUser->course_id);
        
        // Send Dropped from Course Email
        event(new DropUserFromCourse(User::find($roleUser->user_id), $course, Role::find($roleUser->role_id)));

        RoleUser::destroy($id);

        //check to be sure object exists before checking permissions
        if($course)
        {
            //if a user has removed themselves and no longer has access to the
            // course role user page via any other role, redirect them to MyCourses rather than just back
            if(!$course->hasSiteCoursePermissions(['add-people-to-courses'], ['course-add-people-to-courses']))
            {
                return redirect()
                    ->route('mycourses')
                    ->withFlashSuccess(trans('alerts.backend.courseusers.deleted'));            }
            else
            {
                return redirect()->route('course_catalog_users', ['course_id'=> $course->id, 'role_id'=> $roleUser->role_id])
                ->withFlashSuccess(trans('alerts.backend.courseusers.deleted'));
            }
        }
    }


    // add user to db
    public function storeCatalog(StoreCourseUserCatalogRequest $request)
    {
        $course = Course::find($request['course_id']);

        //must either have permission to add at course level or the site permission add-people-to-courses
        if(!$course->hasSiteCoursePermissions(['add-people-to-courses'], ['course-add-people-to-courses']))
        {
            return redirect()->back()->withErrors(trans('alerts.backend.courseusers.access'));
        }

        $roleUser = RoleUser::updateOrCreate(
            ['course_id' => $request['course_id'], 'user_id' => $request['user_id'], 'role_id' => $request['role_id']]
        );
        
        event(new UserAddedToCourse(User::find($roleUser->user_id), Course::find($roleUser->course_id), Role::find($roleUser->role_id)));

        return redirect()->route('course_catalog_users', ['course_id'=> $course->id, 'role_id'=> $request['role_id']])
                ->withFlashSuccess(trans('alerts.backend.courseusers.created'));
    }

}
