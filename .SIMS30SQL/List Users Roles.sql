-- Find all the roles that a user has across all sites
-- John Lutz 2020-08-25 
select s.organization_name, u.email, r.name 
  from users as u, role_user as ru, roles as r, sites as s
 where u.id = ru.user_id
   and ru.role_id = r.id
   and r.site_id = s.id
   and lower(u.email) like lower('%epps%')
-- Pick a site ID to limit it to just one site
--    and s.id = 6
 order by s.organization_name, u.email, r.name