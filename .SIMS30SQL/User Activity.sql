select 'Events', c.abbrv, e.created_at, e.created_by, e.updated_at, e.last_edited_by
  from events as e, course_instances as ci, courses as c
 where e.course_instance_id = ci.id
   and ci.course_id = c.id
   and (   e.created_by     = 134
        or e.last_edited_by = 134)
UNION
select 'courses', c.abbrv, c.created_at, c.created_by, c.updated_at, c.last_edited_by
  from courses as c
 where (   c.created_by     = 134
        or c.last_edited_by = 134)