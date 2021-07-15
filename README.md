# moodle_local_ildmeta

Local Moodle Plugin to add Meta Information to a course

meta data:
- Offering university
- Subject area
- overview image (image upload/ image url; image credits)
- youtube link
- title
- Course offered by
- language
- Descriptions (multiple)
- detailed author / provider infos (including images)

Uses user_info_field table for universities and subjects:
- shortname = universities defines available universities in column param1 divided by linebreaks
- shortname = subjectareas defines available subject areas in column param1 divided by linebreaks

available from course gear menu
