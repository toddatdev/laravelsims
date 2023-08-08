<?php

use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('courses')->delete();
        
        \DB::table('courses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'site_id' => 6,
                'abbrv' => 'CTT',
                'name' => 'Crisis Team Training',
                'retire_date' => NULL,
                'catalog_description' => '<p>Stuff</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-6/CourseCatalogImages/1.jpg',
                'author_name' => 'Me',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 0,
                'created_at' => '2018-03-21 18:50:43',
                'updated_at' => '2018-03-21 18:53:00',
            ),
            1 => 
            array (
                'id' => 2,
                'site_id' => 1,
                'abbrv' => 'CTT',
                'name' => 'Crisis Team Training',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">
<p>The aviation industry practices teamwork in crisis situations. As a result crisis situations are managed efficiently and expertly; harm is averted. Now medical professionals can learn and practice the principles of crisis team management. This course is not focused on leaders, but teams who need to complete a set of tasks in rapid order in an effort to save patients lives.</p>
<p>Groups of about 20 individuals from a variety of disciplines will complete pre-course materials (a slide show and a pretest), participate in a simulator program with facilitated debriefing, and complete post course assessments.</p>
<p>Trainees will understand the rationale for crisis team training, where it fits into a quality patient safety program, fundamentals of teamwork (with emphasis on cooperation and communication), and the importance of practice. They will confront a series of different crisis event so they can learn the underlying principles of team function.</p>
<p><span style="color: #ff0000;">Note: CTT at St. Margaret\'s Hospital (SMH) is intended for SMH employees only.&nbsp; If you are interested in attending, please request registration and we will put you on a wait list, if spots open up.</span></p>
<p><br /><em>Cancellation Policy:&nbsp;</em><em>It is our general policy that unless otherwise stipulated, &nbsp;if a participant cannot attend a class that is previously paid for, they must inform the course director by email at least 2 weeks prior to the class date for a full refund. Course director emails can be found on the directory page for each course on the WISER web site. &nbsp;If the cancellation occurs within 2 weeks of the class, the participant is subject to the revocation of a portion or the entire course fee to WISER. &nbsp;In addition, they may be subject to a cancellation fee based upon a percentage of the course cost.</em></p>
</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/2.png',
                'author_name' => 'Lilian Emlet, MD, Ben Berg, Michael A. DeVita, MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2018-03-27 20:05:16',
                'updated_at' => '2019-01-18 20:03:30',
            ),
            2 => 
            array (
                'id' => 3,
                'site_id' => 1,
                'abbrv' => 'Deactive',
                'name' => 'Deactivated course',
                'retire_date' => '2018-03-21',
                'catalog_description' => '<p>This is a deactivated course.</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/3.jpg',
                'author_name' => NULL,
                'virtual' => 0,
                'created_by' => 2,
                'last_edited_by' => 2,
                'created_at' => '2018-03-21 16:28:38',
                'updated_at' => '2018-03-21 16:28:48',
            ),
            3 => 
            array (
                'id' => 4,
                'site_id' => 6,
                'abbrv' => 'Diamond Head',
                'name' => 'Exploring Diamond Head on Oahu',
                'retire_date' => NULL,
                'catalog_description' => '<p>Fun!&nbsp;</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-6/CourseCatalogImages/4.jpg',
                'author_name' => 'John Lutz',
                'virtual' => 0,
                'created_by' => 2,
                'last_edited_by' => 2,
                'created_at' => '2018-03-21 18:08:15',
                'updated_at' => '2018-03-21 18:08:15',
            ),
            4 => 
            array (
                'id' => 5,
                'site_id' => 6,
                'abbrv' => 'HAN',
                'name' => 'Honolulu at Night.',
                'retire_date' => NULL,
                'catalog_description' => '<p>Night life of Honolulu!&nbsp;</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-6/CourseCatalogImages/5.jpg',
                'author_name' => 'John Lutz',
                'virtual' => 0,
                'created_by' => 2,
                'last_edited_by' => 2,
                'created_at' => '2018-03-21 18:08:56',
                'updated_at' => '2018-03-21 18:08:56',
            ),
            5 => 
            array (
                'id' => 9,
                'site_id' => 1,
                'abbrv' => 'Meeting',
                'name' => 'Meeting',
                'retire_date' => NULL,
                'catalog_description' => '<p>NA</p>',
                'catalog_image' => NULL,
                'author_name' => 'NA',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-17 15:09:02',
                'updated_at' => '2019-01-18 20:04:49',
            ),
            6 => 
            array (
                'id' => 10,
                'site_id' => 1,
                'abbrv' => 'ANES PERIOP US',
                'name' => 'Anesthesiology Perioperative Ultrasound Workshop',
                'retire_date' => NULL,
                'catalog_description' => '<div>This course is designed to provide point-of-care ultrasound mini-lectures and skill stations covering: lung/pleura, cardiac, abdominal/FAST, vascular and DVT to anesthesiology residents.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/10.jpg',
                'author_name' => 'Michael Boisen MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:28:52',
                'updated_at' => '2019-01-18 19:28:52',
            ),
            7 => 
            array (
                'id' => 11,
                'site_id' => 1,
                'abbrv' => '2nd year CPC: IV',
                'name' => '2nd Year Medical Student Clinical Procedures: IV',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">Second year medical students will learn how to start IV\'s on simulated arms.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/11.jpg',
                'author_name' => 'Robert Krohner DO, Andrew Hulme MD, Grace Lim MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:31:12',
                'updated_at' => '2019-01-18 19:31:12',
            ),
            8 => 
            array (
                'id' => 12,
                'site_id' => 1,
                'abbrv' => '3RD YR CCM',
                'name' => '3rd Year Medical Student Critical Care Medicine',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">Third year medical students in their critical care medicine rotation learn how to identify and treat a wide range of unstable patients using lab results, EKGs, patient interviews and simulation based training exercises.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/12.jpg',
                'author_name' => 'Paul Rogers, MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:32:08',
                'updated_at' => '2019-01-18 19:32:09',
            ),
            9 => 
            array (
                'id' => 13,
                'site_id' => 1,
                'abbrv' => 'ADV ARWY SKLS: ACNP STU',
                'name' => 'Advanced Airway Skills for Acute Care Nurse Practitioner Students',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course is designed to provide Acute Care Nurse Practitioner Students training on advanced airway skills, including direct laryngoscopy, the use of extraglottic devices, &nbsp;videolaryngoscopy, the use of bronchoscopy for airway management, evaluation, and clearance and emergency cricothyrotomy.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Jane Guttendorf DNP, RN, CRNP, ACNP-BC',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:33:26',
                'updated_at' => '2019-01-18 19:33:26',
            ),
            10 => 
            array (
                'id' => 14,
                'site_id' => 1,
                'abbrv' => 'ACLS',
                'name' => 'Advanced Cardiac Life Support',
                'retire_date' => NULL,
                'catalog_description' => '<p>Simulate team management of the patient experiencing medical crisis utilizing current AHA ACLS algorithms. This course is designed for Critical Care Professionals and Emergency Department nurses and physicians.</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/14.jpg',
                'author_name' => 'Wendy Kastelic',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:36:12',
                'updated_at' => '2019-01-24 16:29:12',
            ),
            11 => 
            array (
                'id' => 15,
                'site_id' => 1,
                'abbrv' => 'AIR MED CREW',
                'name' => 'Air Medical Crew Training',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course is designed to train air flight crews in difficult airway management and aspects of medical decision making in-flight. In part one of this course, participants gain competencies in difficult airway management techniques and devices. In the second part of this course, medical emergencies are reviewed and participants practice identifying and treating these emergencies using the evidence based guidelines and simulation.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/15.jpg',
                'author_name' => 'Paul Phrampus, MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:38:37',
                'updated_at' => '2019-01-18 19:38:37',
            ),
            12 => 
            array (
                'id' => 16,
                'site_id' => 1,
                'abbrv' => 'ANES ELECT A/W GET',
                'name' => 'Anesthesiology Elective: Airway Management During Intravenous Induction of General Endotracheal Anesthesia',
                'retire_date' => NULL,
            'catalog_description' => '<div class="courseDesc">The goal for this session is to introduce participants to their hands on role during an induction of general endotracheal (GET) anesthesia. Topics covered and reviewed will include basic airway management skills (facemask ventilation, laryngeal mask airway insertion, and direct laryngoscopy and endotracheal intubation). Participants will also examine and work with the anesthesia machine, particularly the flow meters, circle system, and pop-off valve. Students will then practice, with the simulator, managing a patient\'s airway during an intravenous induction of GET.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/16.jpg',
                'author_name' => 'William McIvor, MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:39:59',
                'updated_at' => '2019-01-18 19:40:00',
            ),
            13 => 
            array (
                'id' => 17,
                'site_id' => 1,
                'abbrv' => 'BIP HP',
                'name' => 'Back Injury Prevention for Healthcare Professionals',
                'retire_date' => NULL,
                'catalog_description' => '<p>This course is intended to provide an internet-supported, comprehensive, ergonomics training program in prevention of musculoskeletal injury for direct patient care personnel. We will utilize survey and assessment tools, a Patient Transfer Protocol, WISER integrated information management systems, ergonomic expert oversight, and simulated patient transfers using weighted moving manikins to accomplish the training.&nbsp;<br /><br /></p>
<div>UPMC MyHealth We\'ve Got Your Back Program</div>
<p>UPMC Health Plan has launched an initiative designed to help individuals improve their understanding of low back injuries, identify possible risk factors, and develop healthy behaviors to prevent injury down the road. We want to provide employees with the knowledge and tools they need to protect their backs, within a safe working environment, so that they can enjoy pain-free, rewarding work and leisure activities. UPMC Health Plan, UPMC\'s Center for Rehabilitative Services (CRS), the Winter Institute for Simulation and Educational Research (WISER) and EAP Solutions (part of UPMC\'s Work Partners division) have created one of the area\'s most comprehensive approaches to back fitness and low back injury prevention. "We\'ve Got Your Back" includes:</p>
<ul>
<li>A health fair where attendees can learn about many health and fitness-related products and services related to back health and low back pain prevention</li>
<li>Training in safe lifting techniques and injury prevention (WISER Back Injury Prevention for Health Care Professionals Course)</li>
<li>A free personalized back fitness assessment and personalized home program developed just for you by a licensed physical therapist</li>
<li>The opportunity to receive a free take-home kit that includes a stability ball, exercise bands, and informational materials for exercising at home</li>
<li>Motivational one-on-one sessions with trained health coaches</li>
<li>Care management services for individuals who are at high risk of experiencing chronic back problems</li>
<li>Training in strategies for balancing life, work, and wellness</li>
</ul>
<div>Information Use</div>
<div>Your feedback will provide important information as we continue to develop this initiative at other UPMC sites and in the marketplace.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/17.jpg',
                'author_name' => 'John O\'Donnell',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:42:36',
                'updated_at' => '2019-01-18 19:42:36',
            ),
            14 => 
            array (
                'id' => 18,
                'site_id' => 1,
                'abbrv' => 'CEM Paramedic Lab',
                'name' => 'Center for Emergency Medicine Paramedic Lab',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">The course is only for paramedic students who are enrolled in the Center for Emergency Medicine paramedic program. It is currently not open to the public.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Tom Platt, Guy Guimond, Bob Seitz, Mike Hahn and Luis Rodriguez',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:43:40',
                'updated_at' => '2019-01-18 19:43:40',
            ),
            15 => 
            array (
                'id' => 19,
                'site_id' => 1,
                'abbrv' => 'COP',
                'name' => 'Community Outreach Programming',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">Through a robust collaboration with various community programs, this course aims to provide students with high-quality educational opportunities to explore healthcare, science and career options. &nbsp;This course utilizes hands-on, inquiry based activities to generate interest and enthusiasm about STEM topics.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/19.jpg',
                'author_name' => 'Debby Farkas PhD and Becky Gonda PhD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:45:19',
                'updated_at' => '2019-01-18 19:45:20',
            ),
            16 => 
            array (
                'id' => 20,
                'site_id' => 1,
                'abbrv' => 'DEYSC',
                'name' => 'Designing or Enhancing Your Simulation Center',
                'retire_date' => NULL,
                'catalog_description' => '<p>Welcome to "Designing or Enhancing Your Simulation Center". This one day course is designed to assist those individuals or centers who are interested in designing new or updating existing simulation centers.</p>
<p>This is an 8 topic course that will guide the participants, step by step, through the process of identifying their training needs and designing a world class simulation center to meet those needs.</p>
<p>Below are the topics for this course.</p>
<ul>
<li>Topic 1. Introduction to WISER and Course Overview</li>
<li>Topic 2. Identifying Your Center\'s Training Missions</li>
<li>Topic 3. Blueprints to Build Out, Designing Your Center</li>
<li>Topic 4. Identifying your Center\'s Audio and Video Needs</li>
<li>Topic 5. Administrative Considerations</li>
<li>Topic 6. Job Descriptions</li>
<li>Topic 7.&nbsp;Creating Environments</li>
<li>Topic 8. Additional Tips for Success</li>
</ul>
<p><strong>Course Fee</strong>: $695</p>
<p><br /><em>Cancellation Policy:</em><em>&nbsp;It is our general policy that unless otherwise stipulated, &nbsp;if a participant cannot attend a class that is previously paid for, they must inform the course director by email at least 2 weeks prior to the class date for a full refund. Course director emails can be found on the directory page for each course on the WISER web site. &nbsp;If the cancellation occurs within 2 weeks of the class, the participant is subject to the revocation of a portion or the entire course fee to WISER. &nbsp;In addition, they may be subject to a cancellation fee based upon a percentage of the course cost.</em></p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/20.jpg',
                'author_name' => 'Tom Dongilli',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:46:32',
                'updated_at' => '2019-01-18 19:46:32',
            ),
            17 => 
            array (
                'id' => 21,
                'site_id' => 1,
                'abbrv' => 'FIRST 5 MIN 1: CTA - MWH',
                'name' => 'First 5 Minutes: Module 1. What To Do Until the Code Team Arrives - Magee',
                'retire_date' => NULL,
                'catalog_description' => '<p>This simulation based educational training program is designed for training professional nurses, respiratory therapists and other healthcare providers in the early recognition and treatment of emergently ill patients prior to code team arrival.<br /><br />This course can&nbsp;also be utilized for assessment of&nbsp;departments or programs preparedness for management of a crisis in the critical first 5 minutes of discovery.</p>',
                'catalog_image' => NULL,
                'author_name' => 'Amy Clontz, Thomas Dongilli, Wendy Kastelic, Linda Reid, Melanie Shatzer, Fred Tasota',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:49:14',
                'updated_at' => '2019-01-18 19:49:14',
            ),
            18 => 
            array (
                'id' => 22,
                'site_id' => 1,
                'abbrv' => 'MICU CRISIS SKILLS',
                'name' => 'MICU Crisis, Communication, and Safety Skills',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">The course is designed to provide trainees and staff crisis management, communication and safety skills in the MICU. Participants will experience a series of simulated codes, debriefing, and hands-on skills training to meet the learning objectives.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Stephanie Maximous, MD; Jared Chiarchiaro, MD; Phillip Lamberty MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:50:09',
                'updated_at' => '2019-01-18 19:50:09',
            ),
            19 => 
            array (
                'id' => 23,
                'site_id' => 1,
                'abbrv' => 'Nurs: NA/aPCT Orientation',
                'name' => 'Nursing Assistant/Advanced PCT Orientation',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">New hire orientation for experienced NA\'s and APCT\'s. &nbsp;Course includes fundamentals of patient care and safety principles. &nbsp;&nbsp;For UPMC Passavant only.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Michele Adams, BSN, RN',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:51:04',
                'updated_at' => '2019-01-18 19:51:04',
            ),
            20 => 
            array (
                'id' => 24,
                'site_id' => 1,
                'abbrv' => 'NICU CODE - MAGEE',
            'name' => 'Neonatal Intensive Care Unit Mock Code (MAGEE)',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">Simulation of an infant requiring resuscitation upon delivery or resuscitation of a newborn.&nbsp;</div>',
                'catalog_image' => NULL,
                'author_name' => 'Roberta Bell',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:51:50',
                'updated_at' => '2019-01-18 19:51:50',
            ),
            21 => 
            array (
                'id' => 25,
                'site_id' => 1,
                'abbrv' => 'CARD SURG 101',
                'name' => 'Cardiac Surgery 101',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">The aim of Cardiac Surgery 101 is to provide University of Pittsburgh medical students a jump-start on their potential careers as future cardiac surgeons by providing an intensive five-week immersion into the field. The course foundation will be laid through interactive and case discussions for the first hour of each session. The second hour will allow students to apply their knowledge in surgical simulation labs.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Ibrahim Sultan, MD, FACC',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:52:37',
                'updated_at' => '2019-01-18 19:52:37',
            ),
            22 => 
            array (
                'id' => 26,
                'site_id' => 1,
                'abbrv' => 'VIMEDIX - RSCH',
                'name' => 'Vimedix Ultrasound Research Study',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This study is designed to investigate the improvement of ultrasound image acquisition skills due to an educational intervention.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Abdulrahman Sindi',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:53:23',
                'updated_at' => '2019-01-18 19:53:23',
            ),
            23 => 
            array (
                'id' => 27,
                'site_id' => 1,
                'abbrv' => 'PHLEB INSTR',
                'name' => 'Phlebotomy Instruction',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course will provide didactic instruction and hands-on demonstration for the process and procedure of phlebotomy at UPMC Passavant.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Kathy Fadgen, Rebecca Kolb, Christie Galcik, and Rebecca Rust, BSN RN-BC CEN',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:54:22',
                'updated_at' => '2019-01-18 19:54:22',
            ),
            24 => 
            array (
                'id' => 28,
                'site_id' => 1,
                'abbrv' => 'MS3 TRAUMA',
                'name' => 'MS3 Trauma Resuscitation Course',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course is a simulation based learning experience for 3rd year medical students during their surgical clerkship. They are exposed to Trauma Resuscitation scenarios where they learn how to take care of a polytrauma patient and work together as a team.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Samuel Tisherman MD, Graciela Bauza MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:55:31',
                'updated_at' => '2019-01-18 19:55:31',
            ),
            25 => 
            array (
                'id' => 29,
                'site_id' => 1,
                'abbrv' => 'Pharm 5813',
                'name' => 'Acute Care Pharmacotherapy Simulation: Pharm 5813',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course is designed to allow students to apply clinical knowledge, skills and attitudes gained in previous courses to care of patients with advanced cardiovascular diseases and those that are critically ill. The student will expand on the concepts gained throughout the Pharmacotherapy of Cardiovascular Disease course. The course utilizes simulation based learning to enhance clinical decision-making processes. A major component of the course is self study requiring an adult learning approach to education. The adult learning concept will require the student to be responsible for and highly interactive in achieving the objectives of this course. The student will be expected to be prepared prior to each learning experience. This learning environment will foster clinical decision making and reinforce concepts learned throughout the curriculum at the school of pharmacy. It will also provide an excellent source for objective assessment of student knowledge and performance.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Amy Seybert',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:56:19',
                'updated_at' => '2019-01-18 19:56:19',
            ),
            26 => 
            array (
                'id' => 30,
                'site_id' => 1,
                'abbrv' => 'NUR 1121 ADV',
                'name' => 'NUR 1121 - Nursing Advanced Clinical Problem Solving',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course focuses on the nursing management of the adult experiencing acute or complex illnesses with an alteration in multiple body systems. Principles of crisis intervention are integrated to design interactions for adult clients who have life threatening, physiological and possibly psychological problems. The student\'s ability to apply nursing process using critical thinking is expanded through classroom and clinical activities. Collaboration with interdisciplinary health professionals in health promotion and restoration is fostered.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Gretchen Zewe',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 19:57:17',
                'updated_at' => '2019-01-18 19:57:17',
            ),
            27 => 
            array (
                'id' => 31,
                'site_id' => 1,
                'abbrv' => 'TOB TRT TRNG',
                'name' => 'Tobacco Treatment Training',
                'retire_date' => NULL,
                'catalog_description' => '<p>This course is designed to provide clinical staff training on the dosing and administration of pharmacotherapy and the use of motivational interviewing to treat tobacco use and nicotine dependence. Providing evidence-based tobacco treatment can decrease the risk for complications, infections, and longer healing times. Patients are also at risk for injury when leaving inpatient units to smoke to satisfy cravings for nicotine. Counseling and medications are the most effective for treating hospitalized smokers and providing these interventions can significantly increase patient&rsquo;s abstinence rates.<br /><br />The course contains two modules, Motivational Interviewing and Tobacco Pharmacotherapy, to increase the understanding of how motivational interviewing techniques can engage patients in discussing tobacco use and treatment and the dosing and administering tobacco pharmacotherapy to treat nicotine withdrawal and promote tobacco cessation.&nbsp;</p>
<div>&nbsp;</div>
<p><em>Continuing Education Disclaimer:<br /><br />The University of Pittsburgh School of Medicine is accredited by the Accreditation Council for Continuing Medical Education (ACCME) to provide continuing medical education for physicians.<br />&nbsp;<br />The University of Pittsburgh School of Medicine designates this enduring material is approved for AMA PRA Category 1 Credits&trade;. Clinicians should only claim credit commensurate with the extent of their participation in the activity.<br /><br />Authors disclosure of relevant financial relationships with any entity producing, marketing, re-selling, or distributing health care goods or services, used on, or consumed by, patients is listed above. No other planners, members of the planning committee, content reviewers and/or anyone else in a position to control the content of this education activity have relevant financial relationships to disclose.</em></p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/31.png',
                'author_name' => 'Esa Davis, MD, MPH, Antoine Douaihy, MD, Anna Schulze, MSW',
                'virtual' => 1,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:00:54',
                'updated_at' => '2019-01-18 20:00:55',
            ),
            28 => 
            array (
                'id' => 32,
                'site_id' => 6,
                'abbrv' => 'Meeting',
                'name' => 'Meeting',
                'retire_date' => NULL,
                'catalog_description' => '<p>NA</p>',
                'catalog_image' => NULL,
                'author_name' => 'NA',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:14:09',
                'updated_at' => '2019-01-18 20:14:09',
            ),
            29 => 
            array (
                'id' => 33,
                'site_id' => 6,
                'abbrv' => 'JABSOM MS3 Trauma II',
                'name' => 'JABSOM MS3 Trauma II',
                'retire_date' => NULL,
                'catalog_description' => '<p>Course Purpose</p>
<p>Session II of the MS3 JABSOM Surgery Trauma Curriculum&nbsp;</p>
<ul>
<li>To provide an assessment to basic principles of trauma management</li>
<li>To augment the American College of Surgeons core medical student TEAM Curriculum with high fidelity manikin based curriculum.</li>
</ul>
<p>Course Objectives</p>
<ul>
<li>Prioritize assessment and management strategies for trauma patients using the ABCDE approach</li>
<li>Demonstrate knowledge of basic principles through manikin trauma scenario management.</li>
</ul>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-6/CourseCatalogImages/33.jpg',
                'author_name' => 'Susan Steinemann, MD, FACS',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:15:31',
                'updated_at' => '2019-01-18 20:15:31',
            ),
            30 => 
            array (
                'id' => 34,
                'site_id' => 6,
                'abbrv' => 'SimTiki AcademyColloquium',
                'name' => 'SimTiki Academy International Scholars Colloquium',
                'retire_date' => NULL,
                'catalog_description' => '<p>SimTiki Academy International Scholars Colloquium occurs weekly every Monday from 12:30p-2:30p during the SimTiki Academy International Scholars Cycle.&nbsp;<br />Target Audience: SimTiki Academy International Scholars<br />Goal: This colloquium seeks to advance discussion, sharing and exchange of ideas in simulation and medical education.&nbsp;<br />Objectives:<br />1. Presentation of a thematically organized session by Faculty or Academic Fellow.<br />2. Provide an environment for lively discussion in an informal atmosphere.<br />3. Encourage individual study of areas of simulation and healthcare education.</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-6/CourseCatalogImages/34.jpg',
                'author_name' => 'Jannet Lee-Jayaram MD, Ben Berg MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:16:36',
                'updated_at' => '2019-01-18 20:16:37',
            ),
            31 => 
            array (
                'id' => 35,
                'site_id' => 6,
                'abbrv' => 'UHPG PED',
                'name' => 'UHPG PED Residency',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course with introduce first and second year pediatric residents to simulation exercises and will prepare them to manage critically ill patients.</div>',
                'catalog_image' => NULL,
                'author_name' => NULL,
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:17:21',
                'updated_at' => '2019-01-18 20:17:21',
            ),
            32 => 
            array (
                'id' => 36,
                'site_id' => 6,
                'abbrv' => 'FCCS',
                'name' => 'Fundamental Critical Care Support',
                'retire_date' => NULL,
            'catalog_description' => '<p>The Fundamental Critical Care Support (FCCS) Course is a two-day comprehensive course addressing fundamental management principles for the first 24 hours of critical care.&nbsp;<br />This is the version 6 of the FCCS course.&nbsp;</p>
<p><strong>Day 1 begins at 0700</strong></p>
<p><strong>Target Learners</strong>: Physician, Resident Physician, Physician Assistant, Nurse, Respiratory Therapist, Paramedic, EMT&nbsp;<br /><br />The Hawaii Consortium for Continuing Medical Education (HCCME)&nbsp;<br />designates this activity for a maximum of&nbsp;<strong><em>14 AMA PRA Category 1 CreditsTM.</em></strong><br /><br /><strong>Course Purpose&nbsp;<br /></strong>&bull; To better prepare the non-intensivist for the first 24 hours of management of the critically ill patient until transfer or appropriate critical care consultation can be arranged.&nbsp;<br />&bull; To assist the non-intensivist in dealing with sudden deterioration of the critically ill patient.&nbsp;<br />&bull; To prepare house staff for ICU coverage.&nbsp;<br />&bull; To prepare nurses and other critical care practitioners to deal with acute deterioration in the critically ill patient.&nbsp;<br /><br /><strong>Course Objectives&nbsp;<br /></strong>&bull; Prioritize assessment needs for the critically ill patient.&nbsp;<br />&bull; Select appropriate diagnostic tests.&nbsp;<br />&bull; Identify and respond to significant changes in the unstable patient.&nbsp;<br />&bull; Recognize and initiate management of acute life-threatening conditions.&nbsp;<br />&bull; Determine the need for expert consultation and/or patient transfer and prepare the practitioner for optimally accomplishing transfer.&nbsp;<br /><br /><strong>Course Description&nbsp;<br /></strong>&bull; Didactic lectures&nbsp;<br />&bull; Case based skill stations&nbsp;<br />&bull; Pre &amp; Post tests&nbsp;<br />&bull; Certificate of successful course completion&nbsp;<br />&bull; Requirements for successful course completion are:&nbsp;<br />&nbsp; &nbsp; &nbsp;- &nbsp;attend all didactic sessions&nbsp;<br />&nbsp; &nbsp; &nbsp;- &nbsp;successfully complete all skill stations&nbsp;<br />&nbsp; &nbsp; &nbsp;- &nbsp;obtain 70% or higher on the post test&nbsp;<br />&bull; SCCM maintains database of FCCS providers/instructors&nbsp;<br /><br /></p>
<p><strong>Course Fee<br /></strong>TAMC Sponsored Participants No Charge<br />UH Residents &amp; UH Faculty &nbsp; $195<br />Other Physicians &nbsp; $750<br />Non-UH Residents $550<br />Allied Health &nbsp;&nbsp; &nbsp; &nbsp;$550</p>
<p><strong>Textbook:&nbsp;</strong>&nbsp;"Fundamental Critical Care Support" Sixth Edition&nbsp;may be purchased as an electronic or print book from the&nbsp;<a href="http://www.sccm.org/fundamentals/fccs/fccs-sixth-edition/Pages/default.aspx">Society for Critical Care Medicine Store</a></p>
<p>For more information regarding FCCS at SimTiki Simulation Center please contact us at&nbsp;<a href="mailto:help@simtiki.org">help@simtiki.org</a>&nbsp;or call&nbsp; 808-692-1085.</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-6/CourseCatalogImages/36.jpg',
                'author_name' => 'Society for Critical Care, Benjamin W Berg MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:18:44',
                'updated_at' => '2019-01-18 20:18:45',
            ),
            33 => 
            array (
                'id' => 37,
                'site_id' => 6,
                'abbrv' => 'HPEC',
                'name' => 'Health Professions Education Conference',
                'retire_date' => NULL,
                'catalog_description' => '<p>This conference focuses on faculty development and the sharing of educational scholarship, thus supporting improvements and enhancements to our educational methods and outcomes that allow us to teach and train high-quality health professionals, and to stimulate academic exchange between departments and schools.&nbsp;</p>
<p>To sign up for future HPEC announcements or if you have any questions, please use the link below:&nbsp;<a href="mailto:INFOHPECJOIN-L@lists.hawaii.edu?subject=HPEC%20">INFOHPECJOIN-L@lists.hawaii.edu</a></p>',
                'catalog_image' => NULL,
                'author_name' => 'Ben Berg MD, Jannet Lee-Jayaram',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:19:34',
                'updated_at' => '2019-01-18 20:19:34',
            ),
            34 => 
            array (
                'id' => 38,
                'site_id' => 6,
                'abbrv' => 'JABSOM MS4 Boot Camp',
                'name' => 'JABSOM MS4 Boot Camp',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">A course for JABSOM MS4 students&nbsp; for subspecialty milestones.&nbsp; This course runs during&nbsp;senior seminar week.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Kyra Len MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:20:28',
                'updated_at' => '2019-01-18 20:20:28',
            ),
            35 => 
            array (
                'id' => 39,
                'site_id' => 6,
                'abbrv' => 'FLS Practicum',
                'name' => 'FLS Practicum',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">Practicum for surgeons, surgical residents in preparation for FLS accreditation exam.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-6/CourseCatalogImages/39.jpg',
                'author_name' => 'Benjamine Berg, M.D.',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:21:25',
                'updated_at' => '2019-01-18 20:21:25',
            ),
            36 => 
            array (
                'id' => 40,
                'site_id' => 6,
                'abbrv' => 'UHPG IM Sim Challenge',
                'name' => 'UHPG IM UHIMRP Medical Crisis Simulation Challenge',
                'retire_date' => NULL,
                'catalog_description' => '<p>The UHIMRP Medical Crisis Simulation Competition, aka Sim Wars Hawaii,&nbsp; is a one half day course at the UH SimTiki Simulation Center at the UH JABSOM Kakaako Campus for internal medicine residents. The purpose of this competition is to review and teach the basic concepts of team leadership in a medical crisis situation. Instead of standard simulation course, this course will be conducted in the &ldquo;Sim Wars&rdquo; competition format. &ldquo;Sim Wars is an interactive simulation competition that allows teams of clinical providers to compete against each other on simulated patient encounters in front of large audiences. The competition allows teams to demonstrate their communication, leadership, and clinical decision making skills. &ldquo;</p>
<p>A simulated clinical environment will be created for this competition and is produced in a supportive but competitive atmosphere. Residents will be organized into teams. Teams will be paired up and each be given the chance to run a simulated medical crisis situation. Teams will be judged on their performance and advanced based on the judges&rsquo; opinion. The format will be a single elimination tournament.</p>
<p>Winner takes home the prize and bragging rights for the year.</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-6/CourseCatalogImages/40.jpg',
                'author_name' => 'Emilio Ganitano, MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-18 20:22:58',
                'updated_at' => '2019-01-18 20:22:58',
            ),
            37 => 
            array (
                'id' => 41,
                'site_id' => 1,
                'abbrv' => 'ACLS - SHADY',
                'name' => 'Advanced Cardiac Life Support - Shadyside',
                'retire_date' => NULL,
                'catalog_description' => '<p><strong><span style="color: #ff0000;">This course is only for associates from Shadyside. Registration is through the UPMC Employee U-Learn Course Registration.&nbsp;</span></strong></p>
<p>This course provides advanced cardiac life support information required for ACLS certification.&nbsp;</p>
<p>PREREQUISITE: CURRENT CPR CERTIFICATION.</p>',
                'catalog_image' => NULL,
                'author_name' => 'Not Applicable',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 13:31:00',
                'updated_at' => '2019-01-24 13:31:00',
            ),
            38 => 
            array (
                'id' => 42,
                'site_id' => 1,
                'abbrv' => 'ANES ELECT OB',
                'name' => 'Anesthesiology Elective: Obstetrics',
                'retire_date' => NULL,
                'catalog_description' => '<p>This course covers topics in obstetrical anesthesia simulation for&nbsp;4th year medical students.&nbsp;<br />The goal of this course is to learn to provide anesthesia care for patients in labor who require a caesarian section. A review of the physiologic changes associated with pregnancy and their anesthetic implications is provided, as well as a review of the common anesthetic techniques utilized for labor analgesia and caesarian section. The course participants then actively manage a simulation of an emergency c-section for fetal distress.</p>',
                'catalog_image' => NULL,
                'author_name' => 'William McIvor, MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 13:58:25',
                'updated_at' => '2019-01-24 13:58:25',
            ),
            39 => 
            array (
                'id' => 43,
                'site_id' => 1,
                'abbrv' => 'HC ACLS Part 2',
                'name' => 'HeartCode Advanced Cardiac Life Support - Part 2 Skills',
                'retire_date' => NULL,
            'catalog_description' => '<p><strong>Advanced Cardiac Life Support (ACLS)</strong> Certification and Recertification at WISER using the Laredal Heart Code System.</p>
<p>The Heart Code ACLS course is designed for those individuals that are interested in self paced, PC Driven ACLS certification and recertification. Providers are presented with realistic patient cases and are required to interact with the program to assess the patient, formulate a treatment plan based on the ACLS algorithms and administer treatment.</p>
<p><strong>Considerations:</strong></p>
<ul>
<li>This is a <strong>self-paced, self-driven</strong> certification and recertification program<br /><br /></li>
<li>This is an <strong>instructorless</strong> program There are two parts to the program: <br />
<ul>
<li><strong>Part I:</strong> The didactic portion requires the participants to complete the computer based scenarios and written exam, we recommend completing this portion at home. This takes an estimated 4-5 hours to complete, but could take longer. <em>Please print the certificate at the end of Part I and bring it to WISER for Part II.<br /><br /></em></li>
<li><em>&nbsp;</em><strong>Part II:</strong> The skills portion is done independently utilizing a computer and a sensored manikin at WISER. An estimated 1 hour should be allotted to complete this portion.<br /><br /></li>
<li>Contact WISER at 412-648-6073 to schedule an appointment to complete the skills component. Appointments will be scheduled Monday through Friday 8:00am to 3:00pm. The printed certificate MUST be brought to WISER to complete Part II.</li>
</ul>
</li>
</ul>
<p><strong>Cost:<br /></strong></p>
<ul>
<li><strong>$200.00</strong> For all UPMC/PITT Employees</li>
<li><strong>$250.00</strong> For all External Employees</li>
<li><strong>Effective&nbsp;6/1/2018, ACLS pricing will be $300</strong><br /><br /></li>
</ul>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/43.jpg',
                'author_name' => 'American Heart Association',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 14:03:48',
                'updated_at' => '2019-01-24 14:03:48',
            ),
            40 => 
            array (
                'id' => 44,
                'site_id' => 1,
                'abbrv' => 'SCS',
                'name' => 'Satellite Center Support',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">Scheduled time for the simulation specialists to do routine maintenance and instructor training at the satellite centers.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/44.jpg',
                'author_name' => 'Not Applicable',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 14:05:04',
                'updated_at' => '2019-01-24 14:05:04',
            ),
            41 => 
            array (
                'id' => 45,
                'site_id' => 1,
                'abbrv' => 'BAC - MKS',
                'name' => 'Basic Arrhythmia Class - McKeesport',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This multi-day course covers various information regarding recognition and treatment of arrhythmias.&nbsp; Successful completion of this course requires participation and attendance at all sessions and a passing score on a final exam.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Amber Bugajski',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 14:06:02',
                'updated_at' => '2019-01-24 14:06:02',
            ),
            42 => 
            array (
                'id' => 46,
                'site_id' => 1,
                'abbrv' => 'ICRSE',
                'name' => 'Inpatient Crisis Response System Evaluation',
                'retire_date' => NULL,
                'catalog_description' => '<p>Inpatient Crisis Response System Evaluation is utilized to perform assessments of floor staff, response teams and systems to medical emergencies. &nbsp;Simulation sessions will be performed in various areas, including hospital units, procedures areas and other designated locations within a facility. &nbsp;Performance data will be recorded and reports will then be generated. &nbsp;This course can be customized to your institution\'s needs.</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/46.jpg',
                'author_name' => 'Tom Dongilli, Debby Farkas',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 14:32:10',
                'updated_at' => '2019-01-24 14:32:11',
            ),
            43 => 
            array (
                'id' => 47,
                'site_id' => 1,
                'abbrv' => 'OSCE FAMMED',
                'name' => 'Organized Structured Clinical Exam: Family Medicine Clerkship',
                'retire_date' => NULL,
                'catalog_description' => '<p>The Family Medicine OSCE is a test for the third year students who are on their "Family Medicine" clerkship rotations.</p>',
                'catalog_image' => NULL,
                'author_name' => 'Patti Zahnhaussen',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 14:42:26',
                'updated_at' => '2019-01-24 14:42:26',
            ),
            44 => 
            array (
                'id' => 48,
                'site_id' => 1,
                'abbrv' => 'POCUS COMP RSCH',
                'name' => 'POCUS Competency Research',
                'retire_date' => NULL,
                'catalog_description' => '<p>This study proposes a proficiency-based approach where trainees may be evaluated for progress from novice to competent and onto mastery via a standard checklist assessment of image acquisition and interpretation of the POCUS exam.</p>',
                'catalog_image' => NULL,
                'author_name' => 'Christopher K. Schott, MD MS RDMS FACEP',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 14:45:59',
                'updated_at' => '2019-01-24 14:45:59',
            ),
            45 => 
            array (
                'id' => 49,
                'site_id' => 1,
                'abbrv' => 'NARAT',
                'name' => 'Nurse Anesthesia Regional Anesthesia Training',
                'retire_date' => NULL,
                'catalog_description' => '<p>This course will consist of approximately 4 hours of pre-course reading and preparation. Through review of course materials participants will develop/refresh their didactic knowledge base. The four hour hands-on component will begin with a threshold examination, followed by video review and a series of hands-on workshops. Skills in sterile technique, spinal and epidural placement will be developed through experiential learning sessions. The mastery learning principle will be employed in order to ensure that all participants gain needed skills for application to clinical practice.&nbsp;<br /><br /><strong>Please email the course director, <a href="mailto:wickerma@upmc.edu">Marc Wicker</a>, with registration requests.<br /></strong><br /><em>Cancellation Policy: It is our general policy that unless otherwise stipulated, &nbsp;if a participant cannot attend a class that is previously paid for, they must inform the course director by email at least 2 weeks prior to the class date for a full refund. Course director emails can be found on the directory page for each course on the WISER web site. &nbsp;If the cancellation occurs within 2 weeks of the class, the participant is subject to the revocation of a portion or the entire course fee to WISER. &nbsp;In addition, they may be subject to a cancellation fee based upon a percentage of the course cost.</em></p>
<p>DISCLAIMER STATEMENT<br />The information presented at this CME program represents the views and opinions of the individual presenters, and does not constitute the opinion or endorsement of, or promotion by, the UPMC Center for Continuing Education in the Health Sciences, UPMC / University of Pittsburgh Medical Center or Affiliates and University of Pittsburgh School of Medicine. Reasonable efforts have been taken intending for educational subject matter to be presented in a balanced, unbiased fashion and in compliance with regulatory requirements. However, each program attendee must always use his/her own personal and professional judgment when considering further application of this information, particularly as it may relate to patient diagnostic or treatment decisions including, without limitation, FDA-approved uses and any off-label uses.</p>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/49.jpg',
                'author_name' => 'Marc Wicker, John O\'Donnell, Laura Wiggins',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 14:50:27',
                'updated_at' => '2019-01-24 14:50:27',
            ),
            46 => 
            array (
                'id' => 50,
                'site_id' => 1,
                'abbrv' => 'OCTT',
                'name' => 'Obstetric Crisis Team Training',
                'retire_date' => NULL,
            'catalog_description' => '<div class="courseDesc">The Obstetric Crisis Team Training Course (OCTT) is designed to promote patient safety by training obstetric teams to navigate patient crises effectively. The course trains obstetricians, anesthesiology team members, nurses, newborn response team members, critical care physicians, and others to work together to deliver outstanding patient care in emergent situations. The course utilizes full scale human simulation to achieve learning objectives in the areas of communication, cognitive knowledge, judgment, and decision making.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/50.jpg',
                'author_name' => 'Gabriella Gosman MD, Patricia L. Dalby MD, Karen Stein MSED BSN RN, Nancy Wise, David Streitman',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 15:20:37',
                'updated_at' => '2019-01-24 15:20:38',
            ),
            47 => 
            array (
                'id' => 51,
                'site_id' => 1,
                'abbrv' => 'BERMS',
                'name' => 'Bystander Emergency Response for Medical Students',
                'retire_date' => NULL,
                'catalog_description' => '<p>This course will provide a framework for bystander treatment and first aid, taught at the knowledge level of first and second year medical students. Each session will approach a different &ldquo;real-world&rdquo; clinical scenario with an exploration of the approach to the patient, available resources, stabilization of the condition, and the pathophysiology of the condition. <br /><br />Course Objectives:<br />&nbsp;-Provide a general structured approach to the injured/ill patient for medical students with limited clinical experience<br />&nbsp;-Explore scenario-specific techniques for patient stabilization and management<br />&nbsp;-Discuss initial patient management in resource-poor environments</p>',
                'catalog_image' => NULL,
                'author_name' => 'Adam Z. Tobias, MD, MPH',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 15:21:26',
                'updated_at' => '2019-01-24 15:21:26',
            ),
            48 => 
            array (
                'id' => 52,
                'site_id' => 1,
                'abbrv' => '4TH YR EM EMED 5450',
                'name' => '4th Year Medical Student Emergency Medicine Clerkship',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">As part of the Fourth Year Medical Student Elective students are given the opportunity to treat life threatening problems in a simulated environment so that when they are faced with similar situations in their internship and residency, they know what to expect and how to provide safe care. They learn the skills necessary to be an effective team leader in crisis situations. Scenarios are accompanied by immediate feedback and are repeated until they are mastered.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Susan Dunmire',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 15:22:10',
                'updated_at' => '2019-01-24 15:22:10',
            ),
            49 => 
            array (
                'id' => 53,
                'site_id' => 1,
                'abbrv' => 'ICU CRT',
                'name' => 'ICU Crisis Response Training Program',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">Internal mock code to train ICU staff on local response to a medical crisis.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Thomas Dongilli AT, CHSOS',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 15:22:56',
                'updated_at' => '2019-01-24 15:22:56',
            ),
            50 => 
            array (
                'id' => 54,
                'site_id' => 1,
                'abbrv' => 'PED TRN MOCK',
                'name' => 'Mock Code Program for Pediatric Trainees',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This intermittent simulation course will introduce pediatric healthcare providers,, including physicians, nurses, pharmacists, and respiratory therapists to the recognition and management of pediatric emergencies. Participants will work through pediatric emergency scenarios in an in-situ environment with the multidisciplinary medical team. Emphasis on teamwork, leadership skills, and medical management will be emphasized.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/54.jpg',
                'author_name' => 'Melinda F. Hamilton, MD, MSC',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 15:23:52',
                'updated_at' => '2019-01-24 15:23:52',
            ),
            51 => 
            array (
                'id' => 55,
                'site_id' => 1,
                'abbrv' => '4TH YR CCM',
                'name' => '4th Year Medical Student Critical Care Medicine',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">As part of the Fourth Year Medical Student Elective, students spend an hour per day in the simulation center. Students are given the opportunity to treat life threatening problems in a simulated environment so that when they are faced with similar situations in their internship and residency, they know what to expect and how to provide safe care. They learn the skills necessary to be an effective team leader in crisis situations. Scenarios are accompanied by immediate feedback and are repeated until they are mastered.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Christopher Schott, MD MS RDMS FACEP',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 15:24:40',
                'updated_at' => '2019-01-24 15:24:40',
            ),
            52 => 
            array (
                'id' => 56,
                'site_id' => 1,
                'abbrv' => 'JAI',
                'name' => 'Joint Aspiration and Injection Course: Shoulder and Knee',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course teaches the basic skills of joint aspiration and injection of the knee and shoulder joints. Didactic training reviews the indications for and the contraindications to joint aspiration and injection, and the evidence favoring therapeutic steroid injections. Using synthetic knee and shoulder models the learner will master the psychomotor skills necessary to perform a posterior and a lateral approach to the subacromial bursa, locate the biceps tendon and the acromioclavicular joint, and aspirate "joint fluid" using either the medial or lateral approach to the knee joint. The trainee will be evaluated on both the cognitive and psychomotor components of the course.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Ruth Preisner, MD and Scott Herle, MD, MS',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 15:25:24',
                'updated_at' => '2019-01-24 15:25:24',
            ),
            53 => 
            array (
                'id' => 57,
                'site_id' => 1,
                'abbrv' => 'ANES CLERK DAY 3-EMR SURG',
                'name' => 'Anesthesiology Clerkship Day 3: Anesthesia for Emergency Exploratory Laparotomy',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">Participants will provide perioperative care for a patient presenting for emergency exploratory laparotomy. This session will be performed in groups by the participants.</div>',
                'catalog_image' => NULL,
                'author_name' => 'William McIvor, MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 15:26:09',
                'updated_at' => '2019-01-24 15:26:09',
            ),
            54 => 
            array (
                'id' => 58,
                'site_id' => 1,
                'abbrv' => 'NRP',
                'name' => 'Neonatal Resuscitation Program',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This simulation-based training integrates cognitive, technical, and behavioral skills needed to work through challenging situations, specifically in relating to the resuscitation of the neonate with a focus on teamwork and communication.</div>',
                'catalog_image' => 'https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/58.jpg',
                'author_name' => 'Breanne M. Giron',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 15:27:45',
                'updated_at' => '2019-01-24 15:27:46',
            ),
            55 => 
            array (
                'id' => 59,
                'site_id' => 1,
                'abbrv' => 'Airway Mgmt - MKS',
                'name' => 'Airway Management - McKeesport',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course is designed to train residents airway management techniques.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Not Applicable',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 16:27:07',
                'updated_at' => '2019-01-24 16:27:07',
            ),
            56 => 
            array (
                'id' => 60,
                'site_id' => 1,
                'abbrv' => 'ANES ELECT ADV A/W MNG',
                'name' => 'Anesthesiology Elective: Advanced Airway Management',
                'retire_date' => NULL,
            'catalog_description' => '<div class="courseDesc">The advanced airway management simulation begins with a workshop in which students learn about and practice the use of several advanced airway management tools: the Eschmann stylet (gum bougie), the intubating laryngeal mask airway, the esophageal-tracheal combitube and transtracheal jet ventilation. Each student will then put his/her skills into practice during a simulation in which he/she is required to manage a difficult airway, followed by a debriefing session. Students then complete an online feedback survey regarding the quality of this session.</div>',
                'catalog_image' => NULL,
                'author_name' => 'William McIvor, MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 16:30:11',
                'updated_at' => '2019-01-24 16:30:11',
            ),
            57 => 
            array (
                'id' => 61,
                'site_id' => 1,
                'abbrv' => 'ICU: QI',
                'name' => 'Protocol for the ICU: A QI Project',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">The purpose of this QI project will be to evaluate the system currently in place for activating a condition specific team using in-situ simulation. The secondary focus will be using in-situ simulation in the critical care setting to improve knowledge and confidence related to assessment, mechanisms, and treatment of the specific condition.&nbsp;&nbsp;</div>',
                'catalog_image' => NULL,
                'author_name' => 'Sheila Mickels',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 16:30:49',
                'updated_at' => '2019-01-24 16:30:49',
            ),
            58 => 
            array (
                'id' => 62,
                'site_id' => 1,
                'abbrv' => 'SEPSIS RESP',
                'name' => 'Sepsis Response',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">The course is designed to train the sepsis response team at UPMC St. Margaret\'s how to evaluate and respond to the patient with sepsis.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Courtney Nyoh MSN, RN',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 16:31:34',
                'updated_at' => '2019-01-24 16:31:34',
            ),
            59 => 
            array (
                'id' => 63,
                'site_id' => 1,
                'abbrv' => 'INTRO REG ANES',
                'name' => 'Introduction to Regional Anesthesia: Learning Ultrasound-guided Peripheral Nerve Blockade',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">This course will introduce medical students to ultrasound-guided peripheral nerve blocks and other aspects of regional anesthesia, with particular focus on upper extremity and brachial plexus blocks. &nbsp;Course sessions will include didactics, gross anatomy review, as well as ultrasound depictions of the anatomy. &nbsp;Students will also practice needle guidance techniques.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Kaarin Michaelsen, MD, PhD, Steven L. Orebaugh, MD',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 16:32:30',
                'updated_at' => '2019-01-24 16:32:30',
            ),
            60 => 
            array (
                'id' => 64,
                'site_id' => 1,
                'abbrv' => 'RAD CON RXN',
                'name' => 'Radiology Contrast Reaction Course',
                'retire_date' => NULL,
                'catalog_description' => '<div class="courseDesc">Radiology attendings, residents, nurses and technicians will learn to how manage life threatening situations as well as additional complications that arise in a radiology context.&nbsp; Through a blended learning model, participants will engage with an online didactic module prior to an onsite series of simulation-based scenarios.</div>',
                'catalog_image' => NULL,
                'author_name' => 'Marion Hughes, Michael Magnetta, Alisa Sumkin, Phil Orons, Margarita Zuley',
                'virtual' => 0,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-01-24 17:42:46',
                'updated_at' => '2019-01-24 17:42:46',
            ),
        ));
        
        
    }
}