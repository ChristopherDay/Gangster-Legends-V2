<?php

    class adminModule {

        public function method_options() {

            $settings = new settings();

            if (isset($this->methodData->submit)) {
                $settings->update("detectiveCost", $this->methodData->detectiveCost);
                $settings->update("detectiveDuration", $this->methodData->detectiveDuration);
                $settings->update("detectiveExpire", $this->methodData->detectiveExpire);
               
                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Detective options updated."
                ));
            }

            $output = array(
                "detectiveCost" => $settings->loadSetting("detectiveCost"),
                "detectiveDuration" => $settings->loadSetting("detectiveDuration"),
                "detectiveExpire" => $settings->loadSetting("detectiveExpire")
               
            );

            $this->html .= $this->page->buildElement("detectiveOptions", $output);

        }

    }
