<?php
class ControllerExtensionXmasSnowFlakeXmasSnowFlake extends Controller {

	public function index(&$route, &$data, &$output) {
		if (!$this->config->get("module_xmas_snow_flake_status")) {
			return;
		}

		$color = $this->config->get('module_xmas_snow_flake_color');
		if(empty($color)){
			$color = '71C7D8';
		}

		$intensity = $this->config->get('module_xmas_snow_flake_intensity');
		if (!$intensity) {
			$intensity = 40;
		}

		$icon = $this->config->get('module_xmas_snow_flake_icon');
		if (!$icon) {
			$icon = '<i class="fa-solid fa-save"></i>';
			$icon = '&#10052;';
		}

		$icon = html_entity_decode($icon, ENT_QUOTES, 'UTF-8');

		$sizeRange = [12, 30];
		$sizemin = $this->config->get('module_xmas_snow_flake_sizemin');
		if (!$sizemin) {
			$sizemin = $sizeRange[0];
		}

		$sizemax = $this->config->get('module_xmas_snow_flake_sizemax');
		if (!$sizemax) {
			$sizemax = $sizeRange[1];
		}

		$opacityRange = [0.4, 1];
		$opacitymin = $this->config->get('module_xmas_snow_flake_opacitymin');
		if (!$opacitymin) {
			$opacitymin = $opacityRange[0];
		}

		$opacitymax = $this->config->get('module_xmas_snow_flake_opacitymax');
		if (!$opacitymax) {
			$opacitymax = $opacityRange[1];
		}


		$driftRange = [-2, 2];
		$driftmin = $this->config->get('module_xmas_snow_flake_driftmin');
		if (!$driftmin) {
			$driftmin = $driftRange[0];
		}

		$driftmax = $this->config->get('module_xmas_snow_flake_driftmax');
		if (!$driftmax) {
			$driftmax = $driftRange[1];
		}

		$speedRange = [55, 120];
		$speedmin = $this->config->get('module_xmas_snow_flake_speedmin');
		if (!$speedmin) {
			$speedmin = $speedRange[0];
		}

		$speedmax = $this->config->get('module_xmas_snow_flake_speedmax');
		if (!$speedmax) {
			$speedmax = $speedRange[1];
		}


		$html = "
		<script type=\"text/javascript\" src=\"catalog/view/javascript/xmas_snow_flake/jquery.snow.js\"></script>
		<script type=\"text/javascript\">
		$(function() {
			$('body').snow({
				intensity: {$intensity},
				sizeRange: [{$sizemin}, {$sizemax}],
				opacityRange: [{$opacitymin}, {$opacitymax}],
				driftRange: [{$driftmin}, {$driftmax}],
				speedRange: [{$speedmin}, {$speedmax}],
				icon : '{$icon}'
			});
		});
		</script>
		<style type=\"text/css\">
		.snowflake {
			-webkit-animation: spin 4s linear infinite;
			-moz-animation: spin 4s linear infinite;
			animation: spin 4s linear infinite;
			color: #{$color} !important;
			position: relative;
			z-index: 99;
		}
		@-moz-keyframes
			spin { 100% {
				-moz-transform: rotate(360deg);
			}
		}
		@-webkit-keyframes
			spin { 100% {
				-webkit-transform: rotate(360deg);
			}
		}
		@keyframes
			spin { 100% {
				-webkit-transform: rotate(360deg);
				transform:rotate(360deg);
			}
		}
		</style>
		";

		$find = "</body>";
		$output = str_replace($find, $html . $find, $output);
	}
}
