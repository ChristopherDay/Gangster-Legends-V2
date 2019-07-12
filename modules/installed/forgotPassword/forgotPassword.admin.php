<?php

    class adminModule {

        public function method_settings() {

            $settings = new settings();

            if (isset($this->methodData->submit)) {
                @$settings->update("validateUserEmail", $this->methodData->validateUserEmail);
                $settings->update("registerSuffix", $this->methodData->registerSuffix);
                $settings->update("registerPostfix", $this->methodData->registerPostfix);
                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Theme options updated."
                ));
            }


            $output = array(
                "validateUserEmail" => $settings->loadSetting("validateUserEmail"),
                "registerSuffix" => $settings->loadSetting("registerSuffix"),
                "registerPostfix" => $settings->loadSetting("registerPostfix")
            );

            $this->html .= $this->page->buildElement("registerOptions", $output);

        }

    }