-- List all courses that a user is involved with.
-- Lists both course and event level roles and 
-- indicates if the role is a learner role for events.
-- John Lutz 2020-08-25
select u.email "User",
       s.organization_name "Site", c.abbrv as "Course",
       r.name as "Role", "Course" as "Role Type",
       r.learner "Is Learner"
  from role_user as ru, roles as r, courses as c, users as u, sites as s
 where ru.role_id = r.id
   and ru.course_id = c.id
   and ru.user_id = u.id
   and c.site_id = s.id
   and u.email like '%lutzjw@%'
union
select u.email "User",
       s.organization_name "Site", c.abbrv, r.name, "Event", r.learner
   from event_user as eu, events as e, course_instances as ci, courses as c, roles as r, users as u, sites as s
  where eu.event_id = e.id
    and e.course_instance_id = ci.id
    and ci.course_id = c.id
    and eu.role_id = r.id
   and eu.user_id = u.id
   and c.site_id = s.id
   and u.email like '%lutzjw@%'
group by 1, 2, 3, 4, 5, 6
order by 1,2