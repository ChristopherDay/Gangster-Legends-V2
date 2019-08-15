<?php

    class adminModule {

        public function method_options() {

            $settings = new settings();

            if (isset($this->methodData->submit)) {
                $settings->update("bankTax", $this->methodData->bankTax);
                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Options updated."
                ));
            }

            $output = array(
                "bankTax" => $settings->loadSetting("bankTax", false)
            );

            $this->html .= $this->page->buildElement("options", $output);

        }

    }