<?php

    class adminModule {

        public function method_options() {

            $settings = new settings();

            if (isset($this->methodData->submit)) {
                $settings->update("maxBulletCost", $this->methodData->maxBulletCost);
                $settings->update("bulletsStockMinPerHour", $this->methodData->bulletsStockMinPerHour);
                $settings->update("bulletsStockMaxPerHour", $this->methodData->bulletsStockMaxPerHour);
                $settings->update("maxBulletBuy", $this->methodData->maxBulletBuy);
                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Options updated."
                ));
            }

            $output = array(
                "maxBulletCost" => $settings->loadSetting("maxBulletCost"),
                "bulletsStockMinPerHour" => $settings->loadSetting("bulletsStockMinPerHour"),
                "bulletsStockMaxPerHour" => $settings->loadSetting("bulletsStockMaxPerHour"),
                "maxBulletBuy" => $settings->loadSetting("maxBulletBuy")
            );

            $this->html .= $this->page->buildElement("bulletOptions", $output);

        }

    }