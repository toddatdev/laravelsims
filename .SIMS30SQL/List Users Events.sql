-- List all the events a user is enrolled in for a site
-- John Lutz 2020-08-25
select u.email "User", e.start_time "Event Start", 
       c.abbrv "Course", r.name "Role", e.id "Event ID"
  from users as u, events as e, event_user as eu,
       course_instances as ci, courses as c,
       roles as r
 where u.id = eu.user_id
   and e.id = eu.event_id
   and e.course_instance_id = ci.id
   and ci.course_id = c.id
   and eu.role_id = r.id
   and eu.deleted_at is NULL
   and c.site_id = 1
   and upper(u.email) like upper('%lutz%')
order by e.start_time, c.abbrv