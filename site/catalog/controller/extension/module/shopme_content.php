<?php
class ControllerExtensionModuleShopmeContent extends Controller {
	public function index($setting) {
		
		$module_title = $setting['module_title'];
        $data['module_title'] = $module_title[$this->config->get('config_language_id')];
		
		$data['template'] = $setting['template'];
		$data['columns'] = $setting['columns'];
		$data['bgblock'] = $setting['bgblock'];
		$data['bgblockh'] = $setting['bgblockh'];
		$data['colortext'] = $setting['colortext'];
		$data['colortexth'] = $setting['colortexth'];
		
		if (isset($setting['sections'])) {        
            $data['sections'] = array();

            $section_row = 0;
            
            foreach($setting['sections'] as $section) {

                if (isset($section['title'][$this->config->get('config_language_id')])){
                    $title = html_entity_decode($section['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                } else {
                    $title = false;
                }
				
				if (isset($section['icon'][$this->config->get('config_language_id')])){
                    $icon = html_entity_decode($section['icon'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                } else {
                    $icon = false;
                }
				
				if (isset($section['h3'][$this->config->get('config_language_id')])){
                    $h3 = html_entity_decode($section['h3'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                } else {
                    $h3 = false;
                }
				
				if (isset($section['block'][$this->config->get('config_language_id')])){
                    $block = html_entity_decode($section['block'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                } else {
                    $block = false;
                }

                $section_row++;

                $data['sections'][] = array(
                    'index'   => $section_row,
					'title'   => $title,
					'icon'   => $icon,
					'h3'   => $h3,
					'description'   => $block

                );
            }
		
			return $this->load->view('extension/module/shopme_content', $data);
		}
	}
}