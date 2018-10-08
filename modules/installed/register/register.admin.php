<?php

	class adminModule {

		public function method_settings() {

			$settings = new settings();

			if (isset($this->methodData->submit)) {
				$settings->update("registerSuffix", $this->methodData->registerSuffix);
				$settings->update("registerPostfix", $this->methodData->registerPostfix);
				$this->html .= $this->page->buildElement("success", array(
					"text" => "Theme options updated."
				));
			}


			$output = array(
				"registerSuffix" => $settings->loadSetting("registerSuffix"),
				"registerPostfix" => $settings->loadSetting("registerPostfix")
			);

			$this->html .= $this->page->buildElement("loginOptions", $output);

		}

	}