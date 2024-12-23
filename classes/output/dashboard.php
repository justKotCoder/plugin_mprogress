<?php
namespace local_mprogress\output;

use renderable;
use templatable;
use renderer_base;
use core_completion\progress;
use core_completion\cm_completion_details;

class dashboard implements renderable, templatable {
    private $userid;

    public function __construct($userid) {
        $this->userid = $userid;
    }

    public function export_for_template(renderer_base $output) {
        global $DB;

        // Получение курсов пользователя
        $courses = enrol_get_users_courses($this->userid);
        $progress_data = [];

        foreach ($courses as $course) {
            // Получение итоговой оценки
            $grade = $DB->get_record_sql(
                "SELECT finalgrade, grademax
                 FROM {grade_items} gi
                 JOIN {grade_grades} gg ON gi.id = gg.itemid
                 WHERE gi.courseid = ? AND gg.userid = ? AND gi.itemtype = 'course'",
                [$course->id, $this->userid]
            );

            $finalgrade = ($grade && $grade->grademax > 0)
                ? round(($grade->finalgrade / $grade->grademax) * 100, 2)
                : 0;

            // Получение прогресса курса
            $progress = progress::get_course_progress_percentage($course, $this->userid);
            $progress = $progress !== null ? round($progress, 2) : 0;

            // Ограничение прогресса в пределах 0-100%
            $progress = max(0, min(100, $progress));

            // Генерация динамического стиля для кругового прогресса
            $progress_circle_style = "background: conic-gradient(
                #ff7f3f 0% {$progress}%, 
                #f0f0f0 {$progress}% 100%
            );";

            $progress_text = $progress > 0 ? "{$progress}%" : "Начните курс";

            // Получение заданий курса
            $activities = $this->get_course_activities($course->id);

            $progress_data[] = [
                'course_name' => $course->fullname,
                'course_id' => $course->id,
                'final_grade' => $finalgrade,
                'progress' => $progress,
                'progress_circle_style' => $progress_circle_style,
                'progress_text' => $progress_text,
                'activities' => $activities,
                'has_activities' => !empty($activities),
            ];
        }

        return [
            'courses' => $progress_data
        ];
    }

    private function get_course_activities($courseid) {
        global $DB;

        $modinfo = get_fast_modinfo($courseid);
        $activities = [];

        foreach ($modinfo->cms as $cm) {
            if ($cm->uservisible) {
                // Получение деталей завершения
                $completion = cm_completion_details::get_instance($cm, $this->userid);
                $completion_state = isset($completion->completionstate) ? $completion->completionstate * 100 : 0;

                // Ограничение состояния завершения в пределах 0-100%
                $completion_state = max(0, min(100, $completion_state));

                // Получение информации об оценке
                $grade_info = grade_get_grades($courseid, 'mod', $cm->modname, $cm->instance, $this->userid);
                $grade = isset($grade_info->items[0]->grades[$this->userid])
                    ? $grade_info->items[0]->grades[$this->userid]->grade
                    : null;
                $grademax = isset($grade_info->items[0]) ? $grade_info->items[0]->grademax : null;

                $activities[] = [
                    'name' => $cm->name,
                    'completion' => $completion_state,
                    'grade' => $grade,
                    'grademax' => $grademax,
                ];
            }
        }

        return $activities;
    }
}
