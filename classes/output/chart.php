<?php
namespace local_mprogress\output;
use renderable;
use templatable;
use renderer_base;
class chart implements renderable, templatable {
    private $userid;
    public function __construct($userid) {
        $this->userid = $userid;
    }
    public function export_for_template(renderer_base $output) {
        global $DB;
        $courses = enrol_get_users_courses($this->userid);
        $chart_data = [];
        foreach ($courses as $course) {
            $grade = $DB->get_record_sql(
                "SELECT finalgrade
                 FROM {grade_items} gi
                 JOIN {grade_grades} gg ON gi.id = gg.itemid
                 WHERE gi.courseid = ? AND gg.userid = ? AND gi.itemtype = 'course'",
                [$course->id, $this->userid]
            );
            $finalgrade = $grade ? round(($grade->finalgrade / $grade->grademax) * 100, 2) : 0;
            $chart_data[] = [
                'label' => $course->fullname,
                'value' => $finalgrade,
            ];
        }
        return [
            'chart_data' => $chart_data,
        ];
    }
}