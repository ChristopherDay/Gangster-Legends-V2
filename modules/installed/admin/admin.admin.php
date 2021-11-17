<?php
    class adminModule {

        public function method_view() {

            $widgets = array();

            $charts = new Hook("adminWidget-chart");
            $charts = $charts->run($this->user);

            if ($charts) {
                foreach ($charts as $chart) {
                    $widgets[] = array(
                        "size" => $chart["size"],
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
                        "size" => $table["size"],
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
                        "size" => $htmlElement["size"],
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

            $widgets = array_values($this->page->sortArray($widgets));

            $tmp = array();
            $cols = 0;           
            foreach ($widgets as $key => $widget) {
                $cols += $widget["size"];
                $tmp[] = $widget;

                $nextKey = $key + 1;
                if (isset($widgets[$nextKey])) {
                    if ($cols + $widgets[$nextKey]["size"] > 12) {
                        $tmp[] = array("divider" => 1);
                        $cols = 0;
                    }
                }

            }

            $widgets = $tmp;

            $this->html .= $this->page->buildElement("widgets", array(
                "widgets" => $widgets
            ));

        }

    }
?>