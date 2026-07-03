<?php
class ControllerExtensionModuleGalleryrb extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('tool/image');
    $this->load->model('tool/galleryimage');

		$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
	  $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
    $this->document->addStyle('catalog/view/javascript/jquery/magnific/animation-magnific-popup.css');
    $this->document->addStyle('catalog/view/javascript/jquery/magnific/rb-gallery.css');

    $data['title'] = $setting['title'];
    $data['colspan'] = $setting['colspan'];
    $data['animation'] = $setting['animation'];
    $data['text'] = $setting['text'];
    $data['texthover'] = $setting['texthover'];
    $data['textbg'] = $setting['textbg'];
    if(isset($setting['borderimage'])) $data['borderimage'] = $setting['borderimage'];

    
    switch ($setting['colspan']) {
      case 1: $data['colspan'] = 12; break;
      case 2: $data['colspan'] = 6; break;
      case 3: $data['colspan'] = 4; break;
      case 4: $data['colspan'] = 3; break;
      case 6: $data['colspan'] = 2; break;
      case 12: $data['colspan'] = 1; break;
      default: $data['colspan'] = 4;
    }
    
    
    // Sort Order for gallery
    if(isset($setting['gallery_image'][$this->config->get('config_language_id')])){
      $results = $setting['gallery_image'][$this->config->get('config_language_id')];
      usort($results, function($a, $b){
        if($a['sort_order'] === $b['sort_order'])
          return 0;  
        return $a['sort_order'] > $b['sort_order'] ? 1 : -1;
      });
    } else {
      $results = array();
    }
    
    if (isset($this->request->get['path']) && isset($setting['categories'])) {
      $parts = explode('_', (string)$this->request->get['path']);
      $category_id = (int)array_pop($parts);    
      if(!(in_array($category_id, $setting['categories']))) {
        $results = array();
      }
    }
    foreach ($results as $result) {
      if (is_file(DIR_IMAGE . $result['image'])) {
        $file_image = getimagesize(DIR_IMAGE . $result['image']);       
        // Popup image resize
        
        $popup_width = $file_image[0];
        $popup_height = $file_image[1];
                      
        $scale = $setting['popup_width'] / $popup_width;
        $new_popup_height = $popup_height * $scale;
        
        if(isset($setting['resize'])){
          $thumb = $this->model_tool_galleryimage->resize($result['image'], $setting['thumb_width'], $setting['thumb_height']);
        } else {
          $thumb = $this->model_tool_image->resize($result['image'], $setting['thumb_width'], $setting['thumb_height']);
        }
        
        $data['galleries'][] = array(
          'title' => htmlspecialchars_decode($result['gallery_image_description'],ENT_QUOTES),
          'thumb' => $thumb,
          'image' => $this->model_tool_image->resize($result['image'], $setting['popup_width'], $new_popup_height)
        );
      }
    }
    $data['module'] = $module++;
    return $this->load->view('extension/module/galleryrb', $data);
	}
}