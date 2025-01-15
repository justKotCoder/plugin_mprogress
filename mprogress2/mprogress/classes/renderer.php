<?php
namespace local_mprogress\output;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {
    public function render_dashboard(dashboard $dashboard) {
        return $this->render_from_template('local_mprogress/dashboard', $dashboard->export_for_template($this));
    }

    public function render_chart(chart $chart) {
        return $this->render_from_template('local_mprogress/chart', $chart->export_for_template($this));
    }
}