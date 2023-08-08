CREATE PROCEDURE `CreateNewSite`(
  IN newSiteID  int,
  IN theirAbbrv varchar(50),
  IN bigName    varchar(255), 
  IN ourAbbrv   varchar(255),
  IN helpEmail  varchar(191),
  IN siteURL    varchar(191),
  IN GoogleCode varchar(1000),
  IN startTime  varchar(1000),
  IN endTime    varchar(1000),
  IN openReg    varchar(1000)
)
BEGIN
-- ###############################
-- Create a new site, set some site options, and create default roles
-- Author : John Lutz
-- Created: 2019-10-25
-- Last updated: 2021-02-19
-- You run this by doing a 'call CreateNewSite(1234, 'theirAbbrv', 'The big Name', etc. etc.)'
-- ###############################

 DECLARE stdUserID, simAdminID, accountRole, roleRole, courseRole, resourceRole, schedulerRole, siteRole, courseDirectorRole,
         courseAdminRole, InstructorRole, operatorRole, studentRole INT ;
  
  INSERT INTO `sites` (`id`, `abbrv`, `name`, `organization_name`, `email`, `url_root`, `created_at`, `updated_at`)
  VALUES (newSiteID, theirAbbrv, bigName, ourAbbrv, helpEmail, siteURL, NOW(), NOW() );
  -- Create the standard user.. 
  insert into roles (`site_id`, `name`, `all`, `sort`) values (newSiteID, 'User', 0, 1);
  set stdUserID = (select id from `roles` where site_id = newSiteID and name = 'User');
  -- ..and set the permission to nothing.
  INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (0, stdUserID);
  -- Create the SimMedical Admin roles.. 
  insert into roles (`site_id`, `name`, `all`, `sort`) values (newSiteID, 'Simmedical Administrator', 1, 2);
  set simAdminID = (select id from `roles` where site_id = newSiteID and name = 'Simmedical Administrator');
  -- Add wiserhelp, JL, and Km to the SimMedical Admin roles.
  insert into role_user (`user_id`, `role_id`) values (1, simAdminID);
  insert into role_user (`user_id`, `role_id`) values (2, simAdminID);
  insert into role_user (`user_id`, `role_id`) values (4, simAdminID);
  -- ################
  -- Set Site Options
  --    Open Account Registration
  insert into site_option (`site_id`, `site_option_id`, `value`, `created_at`, `updated_at`)
    values (newSiteID, 3, openReg, NOW(), NOW()); 
  --    Standard User
  insert into site_option (`site_id`, `site_option_id`, `value`, `created_at`, `updated_at`)
    values (newSiteID, 4, stdUserID, NOW(), NOW()); 
  --    Google Analytics Tracking Code
  insert into site_option (`site_id`, `site_option_id`, `value`, `created_at`, `updated_at`)
    values (newSiteID, 5, GoogleCode, NOW(), NOW()); 
  --    Start Time
  insert into site_option (`site_id`, `site_option_id`, `value`, `created_at`, `updated_at`)
    values (newSiteID, 6, startTime, NOW(), NOW()); 
  --    End Time
  insert into site_option (`site_id`, `site_option_id`, `value`, `created_at`, `updated_at`)
    values (newSiteID, 7, endTime, NOW(), NOW()); 

  -- Create Course Catalog Category Group
  INSERT INTO course_category_groups (`abbrv`, `description`, `site_id`, `created_at`, `updated_at`)
      VALUES ('Catalog Filter', 'Filters Courses in the Course Catalog',  newSiteID, NOW(), NOW());

  -- Create a couple of basic resource catagories
  INSERT INTO `resource_category` (`resource_type_id`, `abbrv`, `description`, `site_id`, `retire_date`, `created_at`, `updated_at`)
      VALUES ('1', 'SimRoom', 'Simulation Room', newSiteID, NULL, NOW(), NOW());
  INSERT INTO `resource_category` (`resource_type_id`, `abbrv`, `description`, `site_id`, `retire_date`, `created_at`, `updated_at`)
      VALUES ('1', 'Debrief', 'Debriefing Room', newSiteID, NULL, NOW(), NOW());

  -- ###############################
  -- Create account Manager Role ...
  INSERT INTO roles (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Account Manager', 0, 1, 'Manage user accounts for the site', NULL, NOW(),NOW());
  set accountRole = (select id from roles where roles.site_id= newSiteID and name = 'Account Manager');
  -- ..and set the permissions : view-backend, manage-users
  INSERT INTO permission_role (`permission_id`, `role_id`)
  VALUES
      (1, accountRole),
      (3, accountRole);

  -- ###############################
  -- Create Role Manager Role ...
  INSERT INTO roles (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Role Manager', 0, 1, 'Create, edit, and delete the roles for the site', NULL, NOW(),NOW());
  set roleRole = (select id from roles where roles.site_id= newSiteID and name = 'Role Manager');
  -- ..and set the permissions :  view-backend, manage-roles
  INSERT INTO permission_role (`permission_id`, `role_id`)
    VALUES
        (1, roleRole),
        (6, roleRole);

  -- ###############################
  -- Create Course Manager Role ...
  INSERT INTO `roles` (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Course Manager', 0, 1, 'Create, Edit, and Retire courses for the site, as well as set options, categories, and templates', NULL, NOW(),NOW());
  set courseRole = (select id from roles where roles.site_id= newSiteID and name = 'Course Manager');
 -- ..and set the permissions : view-backend, manage-courses, manage-templates, course-options, course_categories,
 --   add-people-to-courses, manage-course-emails, manage-course-content, view-learner-curriculum, view-instructor-curriculum
 --   manage-course-fees
   INSERT INTO `permission_role` (`permission_id`, `role_id`)
  VALUES
      (1,  courseRole),
      (4,  courseRole),
      (12, courseRole),
      (13, courseRole),
      (14, courseRole),
      (29, courseRole),
      (30, courseRole),
      (36, courseRole),
      (40, courseRole),
      (42, courseRole),
      (51, courseRole);

  -- ###############################
  -- Create Resource Manager Role ...
  INSERT INTO `roles` (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Resource Manager', 0, 1, 'Manage Buildings, Locations, and Resources for the site', NULL, NOW(),NOW());
  set resourceRole = (select id from roles where roles.site_id= newSiteID and name = 'Resource Manager');
  -- ..and set the permissions : view-backend, manage-buildings, manage-locations, manage-resources
  INSERT INTO `permission_role` (`permission_id`, `role_id`)
  VALUES
      (1,  resourceRole),
      (5,  resourceRole),
      (7,  resourceRole),
      (15, resourceRole);

  -- ###############################
  -- Create Scheduler Role ...
  INSERT INTO `roles` (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Scheduler', 0, 1, 'Manage the calendar for the site. Can add people to any event.', NULL, NOW(),NOW());
  set schedulerRole = (select id from roles where roles.site_id= newSiteID and name = 'Scheduler');
  -- ..and set the permissions: scheduling, event-details, add-people-to-events, add-event-comment,manage-event-emails
  INSERT INTO `permission_role` (`permission_id`, `role_id`)
  VALUES
      (9,  schedulerRole),
      (10, schedulerRole),
      (19, schedulerRole),
      (25, schedulerRole),
      (32, schedulerRole);

  -- ###############################
  -- Create Site Manager Role ...
  INSERT INTO `roles` (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Site Manager', 0, 1, 'Manage the site wide email templates, site options, and site reports', NULL, NOW(),NOW());
  set siteRole = (select id from roles where roles.site_id= newSiteID and name = 'Site Manager');
  -- ..and set the permissions: view-backend, client-manage-site-options, client-manage-site-email,sit-report-creation
  INSERT INTO `permission_role` (`permission_id`, `role_id`)
  VALUES
      (1,  siteRole),
      (27, siteRole),
      (28, siteRole),
      (47, siteRole);

  -- ###############################
  -- Create Course Director Role ...
  INSERT INTO `roles` (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Course Director', 0, 1, 'Manage a course. Has all the course level permission for the assigned course.', NULL, NOW(),NOW());
  set courseDirectorRole = (select id from roles where roles.site_id= newSiteID and name = 'Course Director');
  -- ..and set the permissions: course-schedule-request, course-add-people-to-events, course-add-people-to-courses, 
  --    course-add-event-comment, course-manage-course-emails, course-manage-event-emails
  --    course-manage-course-content, course-view-learner-curriculum, course-view-instructor-curriculum,
  --    course-manage-qse, course-mark-event-attendance
  INSERT INTO `permission_role` (`permission_id`, `role_id`)
  VALUES
      (16, courseDirectorRole),
      (17, courseDirectorRole),
      (24, courseDirectorRole),
      (26, courseDirectorRole),
      (31, courseDirectorRole),
      (33, courseDirectorRole),
      (37, courseDirectorRole),
      (41, courseDirectorRole),
      (43, courseDirectorRole),
      (45, courseDirectorRole),
      (49, courseDirectorRole);

  -- ###############################
  -- Create Course Administrator Role ...
  INSERT INTO `roles` (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Course Administrator', 0, 1, 'Manage a course. Has all the course level permission for the assigned course, except the ability to add someone to the course level roles.', NULL, NOW(),NOW());
  set courseAdminRole = (select id from roles where roles.site_id= newSiteID and name = 'Course Administrator');
  -- ..and set the permissions: course-schedule-request, course-add-people-to-events, course-add-event-comment,
  --   course-manage-course-emails, course-manage-event-emails
  --   course-view-learner-curriculum, course-view-instructor-curriculum,course-manage-qse,
  --   course-mark-event-attendance

  INSERT INTO `permission_role` (`permission_id`, `role_id`)
  VALUES
      (16, courseAdminRole),
      (17, courseAdminRole),
      (26, courseAdminRole),
      (31, courseAdminRole),
      (33, courseAdminRole),
      (41, courseAdminRole),
      (43, courseAdminRole),
      (45, courseAdminRole),
      (49, courseAdminRole);

  -- ###############################
  -- Create Instructor Role ...
  INSERT INTO `roles` (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Instructor', 0, 1, 'Instructor for the event. Can add people and comments to the assigned event and view instructor and student content.', NULL, NOW(),NOW());
  set instructorRole = (select id from roles where roles.site_id= newSiteID and name = 'Instructor');
  -- ..and set the permission: event-add-people-to-events, event-manage-event-emails, event-add-event-comment
  -- event-view-learner-curriculum, event-view-instructor-curriculum, event-manage-qse, event-mark-event-attendance
  INSERT INTO `permission_role` (`permission_id`, `role_id`)  
    VALUES
      (18, instructorRole),
      (34, instructorRole),
      (35, instructorRole),
      (38, instructorRole),
      (39, instructorRole),
      (46, instructorRole),
      (50, instructorRole);

  -- ###############################
  -- Create Operations Role ...
  INSERT INTO `roles` (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Operations', 0, 1, 'Operations personnel for the event. Can add people and comments to the assigned event and view instructor and student content.', NULL, NOW(),NOW());
  set operatorRole = (select id from roles where roles.site_id= newSiteID and name = 'Operations');
  -- ..and set the permission: event-add-people-to-events, event-manage-event-emails, event-add-event-comment
  -- event-view-learner-curriculum, event-view-instructor-curriculum, event-mark-event-attendance
  INSERT INTO `permission_role` (`permission_id`, `role_id`)  
    VALUES
      (18, operatorRole),
      (34, operatorRole),
      (35, operatorRole),
      (38, operatorRole),
      (39, operatorRole),
      (50, operatorRole);

  -- ###############################
  -- Create Student Role ...
  INSERT INTO `roles` (`site_id`, `name`, `all`, `sort`, `help_text`, `learner`, `created_at`, `updated_at`)
     VALUES (newSiteID, 'Student', 0, 1, 'Student in the event. Can view student content.', 1, NOW(),NOW());
  set studentRole = (select id from roles where roles.site_id= newSiteID and name = 'Student');
  -- ..and set the permissions: event-view-learner-curriculum
  INSERT INTO `permission_role` (`permission_id`, `role_id`)
    VALUES
      (38, studentRole);

  -- ##############################################################
  -- CREATE SITE LEVEL EMAILS
  -- ###############################
  -- Account Creation email
  INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`) 
  VALUES (newSiteID, '1', 'Confirm New Account', CONCAT('<p>Confirm account creation for ', theirAbbrv, ' web site.</p>'), CONCAT('<p>Hi&nbsp;{{first_name}} {{last_name}},</p>\r\n<p>Please click the link below to confirm your account creation on the ', theirAbbrv, ' web site.</p>\r\n<p>Thanks.</p>\r\n<p>&nbsp;</p>'), NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '2', NOW(), NOW(), NULL);

  -- ###############################
  -- Password Reset email
INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`) 
  VALUES (newSiteID, '7', 'Password Reset', CONCAT('<p>Password Reset for ', theirAbbrv, '</p>'), CONCAT('<p>Please follow the links below to reset your password on the ', theirAbbrv, ' web site. If you have questions, please contact us at ', helpEmail, '.</p>'), NULL, NULL, '', NULL, '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2', '2', NOW(), NOW(), NULL);

  -- ###############################
  -- ADDED TO COURSE email
INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`) 
  VALUES (newSiteID, '2', 'Added to a course', CONCAT('<p>You\'ve been added to the {{course_name}} course on the ', theirAbbrv, ' web site. &nbsp;</p>'), CONCAT('<p>Hi {{first_name}},</p>\r\n<p>You have been added to the {{course_name}} ({{course_abbrv}}) course as a {{role}}&nbsp;on the <a href=\"', siteURL, '\" target=\"_blank\" >', theirAbbrv, ' web site</a>.</p>\r\n<p>Click the \"My Courses\" button on the web site Dashboard to work with that course.</p><p>If you have questions, please contact us at ', helpEmail, '.</P>'), NULL, NULL, '', '28,30', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2', '2', NOW(), NOW(), NULL);
 
  -- ###############################
  -- REMOVED FROM COURSE email
INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`) 
  VALUES (newSiteID, '3', 'Removed from a course', CONCAT('<p>You\'ve been removed from the the {{course_name}} course on the ', theirAbbrv, ' web site. &nbsp;</p>'), CONCAT('<p>Hi {{first_name}},</p>\r\n<p>You have been removed from the {{course_name}} ({{course_abbrv}}) course as a {{role}} on the <a href=\"', siteURL, '\" target=\"_blank\" >', theirAbbrv, ' web site</a>.</p>\r\n<p>If you have questions, please contact us at ', helpEmail, '.</P>'), NULL, NULL, '', '28,30', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2', '2', NOW(), NOW(), NULL);

  -- ###############################
  -- ADDED TO EVENT email - Instructor
INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(newSiteID, '4', 'Added to event - Instructor', CONCAT('<p>You have been assigned to instruct a ', theirAbbrv, ' {{course_abbrv}} class on {{event_date_short}}.</p>'), CONCAT('<p>You have been assigned to instruct a ', theirAbbrv, ' {{course_name}}&nbsp;class on {{event_day}}, {{event_date_long}}. The class will start in {{init_mtg_room_full}} at {{location_name_full}}. You should arrive at {{faculty_start_time}}. The class will start at {{event_start_time}}.</p>\r\n<p>You can get more information at the {{event_dashboard_link}}.</p><p>{{event_notes}}</p>\r\n<p><strong>Directions</strong>:&nbsp;</span></p>\r\n<p>Building : {{building_name_full}} ({{building_map_url}})<br />&nbsp; &nbsp; {{building_more_info}}</p>\r\n<p>Location in the Building: {{location_name_full}}<br />&nbsp; &nbsp; {{location_more_info}}</p>\r\n<p>&nbsp;</p>\r\n<p>If you have any questions, please contact us at ', helpEmail, '.</p>'), NULL, instructorRole, '', NULL, '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2', '2', NOW(), NOW(), NULL);

  -- ###############################
  -- ADDED TO EVENT email - student
INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(newSiteID, '4', 'Added to event - Student', CONCAT('<p>You have been enrolled in a ', theirAbbrv, ' {{course_abbrv}} class on {{event_date_short}}.</p>'), CONCAT('<p>You have been enrolled in a ', theirAbbrv, ' {{course_name}}&nbsp;class on {{event_day}}, {{event_date_long}}. The class will start in {{init_mtg_room_full}} at {{location_name_full}}. The class will start at {{event_start_time}}.</p>\r\n<p>You can get more information at the {{event_dashboard_link}}.</p><p>{{event_notes}}</p>\r\n<p><strong>Directions</strong>:&nbsp;</span></p>\r\n<p>Building : {{building_name_full}} ({{building_map_url}})<br />&nbsp; &nbsp; {{building_more_info}}</p>\r\n<p>Location in the Building: {{location_name_full}}<br />&nbsp; &nbsp; {{location_more_info}}</p>\r\n<p>&nbsp;</p>\r\n<p>If you have any questions, please contact us at ', helpEmail, '.</p>'), NULL, studentRole, '', NULL, '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2', '2', NOW(), NOW(), NULL);

  -- ###############################
  -- REMOVED FROM EVENT email - Student
INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`) 
  VALUES (newSiteID, '5', 'Removed from event - Student', CONCAT('<p>You\'ve been removed from the ', theirAbbrv, ' {{course_abbrv}} class on {{event_date_short}}.</p>'), CONCAT('<p>Hi {{first_name}},</p>\r\n<p>You\'ve been removed from the class on {{event_date_long}} at {{event_start_time}}.&nbsp;</p>\r\n<p>If you think this is in error, please contact us at ', helpEmail, '.</p>'), NULL, studentRole, '', NULL, '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2', '2', NOW(), NOW(), NULL);

  -- ###############################
  -- 1 DAY BEFORE REMINDER
INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`)
  VALUES (newSiteID, '8', '1 Day before reminder', CONCAT('<p>You have a ', theirAbbrv, ' {{course_abbrv}} class tomorrow.</p>'), CONCAT('<p>You have a ', theirAbbrv, ' {{course_name}} class tomorrow at {{event_start_time}}. The class will meet in {{init_mtg_room_full}} at the {{location_name_full}}.</p>\r\n<p>You can get more information at the {{event_dashboard_link}}.</p><p>{{event_notes}}</p>\r\n<p style=\"color: #626262;\">Here are directions on how to get there :</p>\r\n<p style=\"color: #626262;\">Building :&nbsp;{{building_name_full}}</p>\r\n<p style=\"color: #626262;\">Map:{{building_map_url}}</p>\r\n<p style=\"padding-left: 40px;\">More Info: {{building_more_info}}</p>\r\n<p style=\"color: #626262;\">Location in the Building: {{location_name_full}} ({{location_name_abbrv}})</p>\r\n<p style=\"padding-left: 40px;\">More Info: {{location_more_info}}</p>\r\n<p>{{location_more_info}}</p> <p>If you have questions, please contact us at ', helpEmail, '.'), NULL, CONCAT(InstructorRole, ',', StudentRole), '', NULL, '', NULL, '', '1', '3', '1', NULL, NULL, NULL, '2', '2', NOW(), NOW(), NULL);

  -- ###############################
  -- 3 DAYS BEFORE REMINDER
INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`) 
  VALUES (newSiteID, '8', '3 Days before reminder', CONCAT('<p>You have a ', theirAbbrv, ' {{course_abbrv}}&nbsp;class in 3 days.</p>'), CONCAT('<p>You have a ', theirAbbrv, ' {{course_name}} class on {{event_date_long}} at {{event_start_time}}. The class will meet in {{init_mtg_room_full}} at the {{location_name_full}}.</p>\r\n<p>You can get more information at the {{event_dashboard_link}}.</p><p>{{event_notes}}</p>\r\n<p style=\"color: #626262;\">Here are directions on how to get there :</p>\r\n<p style=\"color: #626262;\">Building :&nbsp;{{building_name_full}}</p>\r\n<p style=\"color: #626262;\">Map:{{building_map_url}}</p>\r\n<p style=\"padding-left: 40px;\">More Info: {{building_more_info}}</p>\r\n<p style=\"color: #626262;\">Location in the Building: {{location_name_full}} ({{location_name_abbrv}})</p>\r\n<p style=\"padding-left: 40px;\">More Info: {{location_more_info}}</p>\r\n<p>{{location_more_info}}</p><p>If you have questions, please contact us at ', helpEmail, '.'), NULL, CONCAT(InstructorRole, ',', StudentRole), '', NULL, '', NULL, '', '3', '3', '1', NULL, NULL, NULL, '2', '2', NOW(), NOW(), NULL);

  -- ###############################
  -- 4 DAYS BEFORE WHEN NO INSTRUCTORS
INSERT INTO `site_emails` (`site_id`, `email_type_id`, `label`, `subject`, `body`, `retire_date`, `to_roles`, `to_other`, `cc_roles`, `cc_other`, `bcc_roles`, `bcc_other`, `time_amount`, `time_type`, `time_offset`, `role_id`, `role_amount`, `role_offset`, `created_by`, `last_edited_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(newSiteID, '8', 'No Instructor in 4 days', '<p>There are no instructors in the {{course_abbrv}} class on {{event_date_short}}.</p>', '<p>There are no instructors in the {{course_name}}&nbsp;class on {{event_day}}, {{event_date_long}} at the {{location_name_full}}.&nbsp;</p>\r\n<p>You can go to the {{event_dashboard_link}} to add people.&nbsp;</p>', NULL, CONCAT(courseDirectorRole, ', ', courseAdminRole), '', NULL, '', NULL, '', '4', '3', '1', instructorRole, '1', '1', '2', '2', NOW(), NOW(), NULL);
END