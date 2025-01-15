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

            $progress_text = $progress > 0 ? "{$progress}%" : get_string('startcourse', 'local_mprogress');

            // Получение заданий курса
            $activities = $this->get_course_activities($course->id);
            $total_user_points = array_sum(array_column($activities, 'grade'));
            $total_max_points = array_sum(array_column($activities, 'grademax'));

            $progress_data[] = [
                'course_name' => $course->fullname,
                'course_id' => $course->id,
                'final_grade' => $finalgrade,
                'progress' => $progress,
                'progress_circle_style' => $progress_circle_style,
                'progress_text' => $progress_text,
                'activities' => $activities,
                'has_activities' => !empty($activities),
                'total_user_points' => round($total_user_points, 2),
                'total_max_points' => round($total_max_points, 2),
                'columns' => [
                    'assignment' => get_string('assignment', 'local_mprogress'),
                    'grade' => get_string('grade', 'local_mprogress'),
                    'points' => get_string('points', 'local_mprogress'),
                    'weight' => get_string('weight', 'local_mprogress'),
                    'result' => get_string('result', 'local_mprogress'),
                ]
            ];
        }

        return [
            'courses' => $progress_data,
            'course_progress' => get_string('course_progress', 'local_mprogress')
        ];
    }

    private function get_course_activities($courseid) {
        global $DB;
    
        $modinfo = get_fast_modinfo($courseid);
        $activities = [];
    
        // Сумма всех максимальных баллов за задания
        $total_max_points = 0;
    
        foreach ($modinfo->cms as $cm) {
            if ($cm->uservisible) {
                $grade_info = grade_get_grades($courseid, 'mod', $cm->modname, $cm->instance, $this->userid);
    
                if (isset($grade_info->items[0]) && $grade_info->items[0]->grademax > 0) {
                    $total_max_points += $grade_info->items[0]->grademax;
                }
            }
        }
    
        foreach ($modinfo->cms as $cm) {
            if ($cm->uservisible) {
                // Пропускаем форум, так как он не оценивается
                if ($cm->modname === 'forum') {
                    continue;
                }
    
                // Получаем детали завершения
                $completion = cm_completion_details::get_instance($cm, $this->userid);
                $completion_state = isset($completion->completionstate) ? $completion->completionstate * 100 : 0;
    
                // Получаем информацию об оценке
                $grade_info = grade_get_grades($courseid, 'mod', $cm->modname, $cm->instance, $this->userid);
    
                $max_grade = isset($grade_info->items[0]) ? $grade_info->items[0]->grademax : 0;
                $user_grade = isset($grade_info->items[0]->grades[$this->userid]) 
                    ? $grade_info->items[0]->grades[$this->userid]->grade 
                    : null;
    
                if ($max_grade > 0 && $total_max_points > 0) {
                    // Рассчитываем "Рассчитанный вес"
                    $calculated_weight = round(($max_grade / $total_max_points) * 100, 2);
    
                    // Рассчитываем "Вклад в итог курса"
                    $contribution = round($calculated_weight * ($user_grade / $max_grade), 2);
    
                    // Рассчитываем "Оценка в %"
                    $percentage = $user_grade !== null 
                        ? round(($user_grade / $max_grade) * 100, 2) 
                        : 0;
    
                    // Рассчитываем "Итог"
                    $total_score = $user_grade !== null 
                        ? round($user_grade * ($calculated_weight / 100), 2) 
                        : 0;
                    
                    $total_score2 = $max_grade !== null
                        ? round($max_grade * ($calculated_weight / 100), 2)
                        : 0;

                } else {
                    $calculated_weight = 0;
                    $contribution = 0;
                    $percentage = 0;
                    $total_score = 0;
                    $total_score2 = 0;
                }

                // Определяем текст для колонки "Баллы"
                $grade_display = $user_grade !== null 
                    ? round($user_grade, 1) . " / " . intval($max_grade) 
                    : "- / " . intval($max_grade);
    
                $activities[] = [
                    'name' => $cm->name,
                    'type' => get_string('modulename', $cm->modname), // Тип задания
                    'completion' => $completion_state, // Прогресс завершения активности
                    'grade' => $user_grade,
                    'grademax' => $max_grade,
                    'percentage' => $percentage, // Оценка в %
                    'calculated_weight' => $calculated_weight, // Рассчитанный вес
                    'contribution' => $contribution, // Вклад в итог курса
                    'total_score' => $total_score, // Итог (Текущий балл * вес)
                    'grade_display' => $grade_display, // Текст для колонки "Баллы"
                    'total_score2' => $total_score2 // Итог (Максимальнй балл * вес)
                ];
            }
        }
    
        return $activities;
    }

}
