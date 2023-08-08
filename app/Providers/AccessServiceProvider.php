<?php

namespace App\Providers;

use App\Services\Access\Access;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Models\Access\Permission\Permission;
use Auth;

/**
 * Class AccessServiceProvider.
 */
class AccessServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Package boot method.
     */
    public function boot()
    {
        $this->registerBladeExtensions();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAccess();
        $this->registerFacade();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerAccess()
    {
        $this->app->bind('access', function ($app) {
            return new Access($app);
        });
    }

    /**
     * Register the vault facade without the user having to add it to the app.php file.
     *
     * @return void
     */
    public function registerFacade()
    {
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Access', \App\Services\Access\Facades\Access::class);
        });
    }

    /**
     * Register the blade extender to use new blade sections.
     */
    protected function registerBladeExtensions()
    {
        /*
         * Role based blade extensions
         * Accepts either string of Role Name or Role ID
         */
        Blade::directive('role', function ($role) {
            return "<?php if (access()->hasRole({$role})): ?>";
        });

        /*
         * Accepts array of names or id's
         */
        Blade::directive('roles', function ($roles) {
            return "<?php if (access()->hasRoles({$roles})): ?>";
        });

        Blade::directive('needsroles', function ($roles) {
            return '<?php if (access()->hasRoles('.$roles.', true)): ?>';
        });

        /*
         * Permission based blade extensions
         * Accepts wither string of Permission Name or Permission ID
         */
        Blade::directive('permission', function ($permission) {
            return "<?php if (access()->allow({$permission})): ?>";
        });

        /*
         * Accepts array of names or id's
         */
        Blade::directive('permissions', function ($permissions) {
            return "<?php if (access()->allowMultiple({$permissions})): ?>";
        });

        Blade::directive('needspermissions', function ($permissions) {
            return '<?php if (access()->allowMultiple('.$permissions.', true)): ?>';
        });

    
        Blade::if('coursePermission', function($course_id, $permission_name, $user_id = null) {

            if ($user_id == null) $user = Auth::user();

            if ($user) $user_id = $user->id;

            $coursePermission = Permission::select('permissions.id')
                ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id' )
                ->join('roles', 'permission_role.role_id', '=', 'roles.id')
                ->join('role_user', 'role_user.role_id', '=', 'roles.id')
                ->where('permissions.name', $permission_name)
                ->where('role_user.user_id', $user_id)
                ->where('role_user.course_id', $course_id)
                ->get();

                if($coursePermission->count() > 0) {
                    $count = true;
                } else {
                    $count = false;
                } 
                
            return $count;

        });

        Blade::if('eventPermission', function($event_id, $permission_name, $user_id = null) {

            if ($user_id == null) $user = Auth::user();

            if ($user) $user_id = $user->id;

            $eventPermission = Permission::select('permissions.id')
                ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id' )
                ->join('roles', 'permission_role.role_id', '=', 'roles.id')
                ->join('event_user', 'event_user.role_id', '=', 'roles.id')
                ->where('permissions.name', $permission_name)
                ->where('event_user.user_id', $user_id)
                ->where('event_user.event_id', $event_id)
                ->get();

                if($eventPermission->count() > 0) {
                    $count = true;
                } else {
                    $count = false;
                } 
                
            return $count;

        });

        /*
         * Generic if closer to not interfere with built in blade
         */
        Blade::directive('endauth', function () {
            return '<?php endif; ?>';
        });
    }
}
