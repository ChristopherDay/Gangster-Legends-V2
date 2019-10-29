<?php

    class adminModule {

        public function method_settings() {

            $settings = new settings();

            if (isset($this->methodData->submit)) {
                $settings->update("loginSuffix", $this->methodData->loginSuffix);
                $settings->update("loginPostfix", $this->methodData->loginPostfix);
                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Theme options updated."
                ));
            }


            $output = array(
                "loginSuffix" => $settings->loadSetting("loginSuffix"),
                "loginPostfix" => $settings->loadSetting("loginPostfix")
            );

            $this->html .= $this->page->buildElement("loginOptions", $output);

        }

    }