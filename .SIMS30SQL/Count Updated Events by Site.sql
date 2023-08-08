-- Show activity, as defined by updated events, by site.
-- John Lutz 2020-08-25
select s.abbrv as 'Site', count(*) as 'Updates'
  from events as e, course_instances ci, courses c, sites s 
 where e.course_instance_id = ci.id
   and ci.course_id = c.id
   and c.site_id = s.id
   and date(e.updated_at) >= DATE_SUB(CURDATE(), INTERVAL 2 DAY)
 group by s.abbrv
 order by 2 desc