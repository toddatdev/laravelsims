-- This is similar to the old SIMS Activity Report.
-- It basically dumps all of the events within a time period
-- John Lutz 2020-08-25
select e.id                 as "Event ID",
       c.abbrv              as "Course",
       e.abbrv              as "Label",
       (e.setup_time/60)    as "Setup Time",
       e.start_time         as "Start Time",
       e.end_time           as "End Time",
       (e.teardown_time/60) as "Tear Down Time",
       e.class_size         as "Class Size",
       l.abbrv              as "Location",
       r.abbrv              as "IMR",
       e.public_comments    as "Public Comments",
       e.internal_comments  as "Internal Comments"
  from events           as e,
       course_instances as ci,
       courses          as c,
       resources        as r,
       locations        as l 
 where c.id                   = ci.course_id
   and ci.id                  = e.course_instance_id
   and e.initial_meeting_room = r.id
   and r.location_id          = l.id
   and c.site_id              = 4
   and date(e.start_time) between '2019-01-25' and '2022-12-31'
 order by e.start_time, c.abbrv