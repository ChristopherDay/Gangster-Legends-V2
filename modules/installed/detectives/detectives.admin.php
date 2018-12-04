<?php

	class adminModule {

		public function method_options() {

			$settings = new settings();

			if (isset($this->methodData->submit)) {
				$settings->update("detectiveCost", $this->methodData->detectiveCost);
				$this->html .= $this->page->buildElement("success", array(
					"text" => "Detective options updated."
				));
			}

			$output = array(
				"detectiveCost" => $settings->loadSetting("detectiveCost")
			);

			$this->html .= $this->page->buildElement("detectiveOptions", $output);

		}

	}