-- List all of the roles that a user has for a site.
-- Make sure you change the site ID and user on both 
-- sides of the union statement
-- -jl 2019-12-05 10:07
select u.email 'User', c.abbrv 'Course', r.name 'Role', p.name 'Permission'
  from users as u, role_user as ru, roles as r,
       permission_role as pr, permissions as p
      ,courses as c
--  inner join courses as c on role_user.course_id = courses.id
 where u.id = ru.user_id
   and ru.role_id = r.id
   and r.id = pr.role_id
   and pr.permission_id = p.id
   and ru.course_id = c.id
   and r.site_id = 4
   and lower(u.email) like lower('%love%')
 
 UNION
 select u.email, '*Site Level', r.name, p.name
  from users as u, role_user as ru, roles as r,
       permission_role as pr, permissions as p
 where u.id = ru.user_id
   and ru.role_id = r.id
   and r.id = pr.role_id
   and ru.course_id is null
   and pr.permission_id = p.id
   and r.site_id = 4
   and lower(u.email) like lower('%love%')
 
 order by 1, 2, 3, 4