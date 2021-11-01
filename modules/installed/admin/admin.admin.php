<?php
    class adminModule {

        public function method_view() {

            $widgets = array();

            $charts = new Hook("adminWidget-chart");
            $charts = $charts->run($this->user);

            if ($charts) {
                foreach ($charts as $chart) {
                    $widgets[] = array(
                        "sort" => (isset($chart["sort"])?$chart["sort"]:0),
                        "html" => $this->page->buildElement("widgetChart", $chart)
                    );
                }
            }

            $tables = new Hook("adminWidget-table");
            $tables = $tables->run($this->user);

            if ($tables) {
                foreach ($tables as $table) {
                    $widgets[] = array(
                        "sort" => (isset($table["sort"])?$table["sort"]:0),
                        "html" => $this->page->buildElement("widgetTable", $table)
                    );
                }
            }

            $htmlWidgets = new Hook("adminWidget-html");
            $htmlWidgets = $htmlWidgets->run($this->user);

            if ($htmlWidgets) {
                foreach ($htmlWidgets as $htmlElement) {
                    $widgets[] = array(
                        "sort" => (isset($htmlElement["sort"])?$htmlElement["sort"]:0),
                        "html" => $this->page->buildElement("widgetHTML", $htmlElement)
                    );
                }
            }

            $alerts = new Hook("adminWidget-alerts");
            $alerts = $alerts->run($this->user);

            if ($alerts) {
                foreach ($alerts as $alert) {
                    if (isset($alert["text"])) $this->page->alert($alert["text"], $alert["type"]);
                }
            }

            $this->html .= $this->page->buildElement("widgets", array(
                "widgets" => $this->page->sortArray($widgets)
            ));

        }

    }
?>