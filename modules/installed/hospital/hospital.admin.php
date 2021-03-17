<?php

    class adminModule {

        public function method_options() {

            $settings = new settings();

            if (isset($this->methodData->submit)) {
                $settings->update("hospitalTimeUntillFull", $this->methodData->hospitalTimeUntillFull);
                $settings->update("hospitalmoneyUntillFull", $this->methodData->hospitalmoneyUntillFull);
                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Options updated."
                ));
            }

            $output = array(
                "hospitalTimeUntillFull" => $settings->loadSetting("hospitalTimeUntillFull"),
                "hospitalmoneyUntillFull" => $settings->loadSetting("hospitalmoneyUntillFull")
            );

            $this->html .= $this->page->buildElement("options", $output);

        }

    }