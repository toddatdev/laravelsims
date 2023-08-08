<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DatabaseSeeder.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(SitesTableSeeder::class);
        //I removed this and are calling all of the 'access' table seeders below.  -jl 2018-03-23 
        // $this->call(AccessTableSeeder::class);

        $this->call(BuildingsTableSeeder::class);
        $this->call(TimezonesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(PermissionTypeSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(LocationSchedulersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);

        $this->call(SiteOptionsTableSeeder::class);
        $this->call(CourseCategoryGroupsTableSeeder::class);
        $this->call(CourseCategoriesTableSeeder::class);
        $this->call(CourseCategoryTableSeeder::class);
        $this->call(SiteOptionTableSeeder::class);
        $this->call(UserSitesTableSeeder::class);

        $this->call(ResourceTypesTableSeeder::class);
        $this->call(ResourceCategoryTableSeeder::class);
        $this->call(ResourceSubCategoryTableSeeder::class);
        $this->call(ResourcesTableSeeder::class);

        $this->call(InputTypesTableSeeder::class);
        $this->call(CourseOptionsTableSeeder::class);

        $this->call(CourseInstancesTableSeeder::class);
        $this->call(CourseOptionTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(EventResourcesTableSeeder::class);
        $this->call(ResourceIdentifierTypesTableSeeder::class);
//        $this->call(ScheduleRequestsTableSeeder::class);

        $this->call(EmailTypesSeeder::class);
        $this->call(SiteEmailsSeeder::class);
        $this->call(CourseEmailsSeeder::class);
        $this->call(EventEmailsSeeder::class);

        // moved this to the bottom -jl 2018-03-23 11:22
        $this->call(HistoryTypeTableSeeder::class);

        Model::reguard();

        $this->call(EventUserStatusTableSeeder::class);
        $this->call(EventUserHistoryActionsTableSeeder::class);

        $this->call(ContentTypesSeeder::class);
        $this->call(ViewerTypesSeeder::class);

        $this->call(EventStatusTypesTableSeeder::class);
        $this->call(QSEAnswerTypesTableSeeder::class);
    }
}
