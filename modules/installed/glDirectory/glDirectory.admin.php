<?php

    class adminModule {

        public function method_options() {

            $settings = new settings();
            $validationPath = __DIR__ . "../../../../glDirectory.txt";

            if (isset($this->methodData->submit)) {
                $settings->update("voteMin", $this->methodData->voteMin);
                $settings->update("voteMax", $this->methodData->voteMax);
                $settings->update("voteUrl", $this->methodData->voteUrl);
                $settings->update("voteKey1", $this->methodData->voteKey1);
                $settings->update("voteKey2", $this->methodData->voteKey2);
               
                if (isset($this->methodData->validation) && strlen($this->methodData->validation) == 64) {
                    file_put_contents($validationPath, $this->methodData->validation);
                }

                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Options updated."
                ));
            }

            $validation = "";

            if (file_exists($validationPath)) {
                $validation = file_get_contents($validationPath);
            }

            $output = array(
                "voteMin" => $settings->loadSetting("voteMin"),
                "voteMax" => $settings->loadSetting("voteMax"),
                "voteUrl" => $settings->loadSetting("voteUrl"),
                "voteKey1" => $settings->loadSetting("voteKey1"),
                "voteKey2" => $settings->loadSetting("voteKey2"), 
                "validation" => $validation
            );

            $this->html .= $this->page->buildElement("options", $output);

        }

    }
