-- Lists the roles for a site based upon the permisssion_type (Site/course/event)
-- -jl 2019-12-04 10:59
select r.name, pt.name
  from roles as r, permission_role as pr, permissions as p, permission_type as `pt`
 where r.id                 = pr.role_id
   and pr.permission_id     = p.id
   and p.permission_type_id = pt.id
   and r.site_id = 6
-- Limit to a particular permission type 
--   and pt.name = 'Site' -- Site | Course | Event
 group by r.name, pt.name
 order by pt.name desc, r.name 