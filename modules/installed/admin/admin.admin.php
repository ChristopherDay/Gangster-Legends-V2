<?php
    class adminModule {

        public function method_view() {

            $tables = new Hook("adminWidget-table");
            $tables = $tables->run($this->user);

            $widgets = array();

            if ($tables) {
                foreach ($tables as $table) {
                    $widgets[] = array(
                        "sort" => (isset($table["sort"])?$table["sort"]:0),
                        "html" => $this->page->buildElement("widget" . $table["type"], $table)
                    );
                }
            }

            $this->html .= $this->page->buildElement("widgets", array(
                "widgets" => $this->page->sortArray($widgets)
            ));

        }

    }
?>