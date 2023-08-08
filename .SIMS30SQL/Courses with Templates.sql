-- List courses that have templates.
-- You can select a specific site and
-- just count the number of templates.
-- John Lutz 2020-08-25
SELECT
  s.abbrv     as "Site"
  ,c.abbrv    as "Course"
  ,ct.name    as "Template"
  ,b.abbrv    as "Building"
  ,l.abbrv    as "Location"
--   ,count(*)   "# of Templates"
FROM
  courses AS c,
  course_templates AS ct,
  sites     as s,
  resources AS r,
  locations AS l,
  buildings AS b
WHERE
  c.id = ct. course_id
  and c.site_id = s.id
  AND ct.initial_meeting_room = r.id
  AND r.location_id = l.id
  AND l.building_id = b.id
-- # Pick your site
--  AND c.site_id = 4
-- # Limit it to just templates that have been update recently.
--  and DATE(ct.updated_at) >= DATE_SUB(NOW(), INTERVAL 5 DAY)
-- # Just get counts
-- group by s.abbrv
ORDER BY
  s.abbrv
 ,c.abbrv
 ,ct.name
