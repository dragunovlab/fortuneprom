<?php 
class ControllerExtensionModuleWhatsAppShare extends Controller  {
	private $moduleName;
	private $modulePath;
	private $moduleNameSmall;
	private $moduleData_module = 'whatsappshare_module';
	private $moduleModel;
	
	public function __construct($registry){
		parent::__construct($registry);
		$this->config->load('isenselabs/whatsappshare');
		$module = 'whatsappshare_';

		$this->moduleName = $this->config->get($module.'moduleName');
		$this->modulePath = $this->config->get($module.'modulePath');

		$this->moduleNameSmall = $this->config->get($module.'moduleName');
		$this->moduleModel = $this->config->get($module.'callModel');
		


	}


    public function index() {
		$this->load->model($this->modulePath);
        $this->document->addStyle('catalog/view/theme/'.$this->config->get('config_theme').'/stylesheet/'.$this->moduleNameSmall.'.css');
        $this->load->model('localisation/language');
 		$current_language = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
    
        $languageVariables= array('heading_title', 'add_to_cart');

        foreach ($languageVariables as $variable) {
            $data[$variable] = $this->language->get($variable);
        }
		$data['modulePath'] = $this->modulePath;
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['text_tax'] = $this->language->get('text_tax');
		
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$data['products'] = array();
		
		$moduleSetting = $this->{$this->moduleModel}->getSetting($this->moduleNameSmall, $this->config->get('config_store_id'));
        $data['moduleData'] = isset($moduleSetting[$this->moduleNameSmall]) ? $moduleSetting[$this->moduleNameSmall] : array();
		
		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/image/whatsappshare/'. $data['moduleData']['ButtonDesign'])) {
			$data['whatsappshare_image'] = 'catalog/view/theme/'.$this->config->get('config_template') . '/image/whatsappshare/'. $data['moduleData']['ButtonDesign'];
		} else {
			$data['whatsappshare_image'] = 'catalog/view/theme/default/image/whatsappshare/'. $data['moduleData']['ButtonDesign'];
		}

		$data['language_id']=$this->config->get('config_language_id');
		if(!isset($data['moduleData']['PanelName'][$this->config->get('config_language')])){
			$data['PanelName'] = $data['heading_title'];
		} else {
			$data['PanelName'] = $data['moduleData']['PanelName'][$this->config->get('config_language')];
		}
		
		if(isset($this->session->data['whatsappshare_products']))
		{
			$reversed = array_reverse($this->session->data['whatsappshare_products']);
			if (isset($this->request->get['product_id'])) {
				if (($key = array_search($this->request->get['product_id'], $reversed)) !== false) unset($reversed[$key]);
			}
		}
		
		$i=0;
		$limit = isset($data['moduleData']['Limit']) ? $data['moduleData']['Limit'] : 4;
		$imageWidth = isset($data['moduleData']['ImageWidth']) ? $data['moduleData']['ImageWidth'] : '100';
		$imageHeight = isset($data['moduleData']['ImageHeight']) ? $data['moduleData']['ImageHeight'] : '100';
		if (isset($reversed)) {
			foreach ($reversed as $result) {
				$product_data = $this->model_catalog_product->getProduct($result);
				
				if ($product_data['image']) {
					$image = $this->model_tool_image->resize($product_data['image'], $imageWidth, $imageWidth);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $imageHeight, $imageHeight);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = 'Цена по запросу';
				} else {
					$price = false;
				}

				if ((float)$product_data['special']) {
					$special = false;
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = false;
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $product_data['rating'];
				} else {
					$rating = false;
				}
						
				$data['products'][] = array(
					'product_id'  => $product_data['product_id'],
					'thumb'       => $image,
					'name'        => $product_data['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($product_data['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $product_data['product_id'])
				);
				
				$i++;
				if ($i==$limit)
					break;
			}
		}
        $is_mobile = '0';

        if(preg_match('/(android|up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $is_mobile=1;
        }

        if(0<(strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') ) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $is_mobile=1;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
        $mobile_agents = array('w3c ','acs-','alav','alca','amoi','andr','audi','avan','benq','bird','blac','blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno','ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-','newt','noki','oper','palm','pana','pant','phil','play','port','prox','qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar','sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-','tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp','wapr','webc','winw','winw','xda','xda-');

        if(in_array($mobile_ua,$mobile_agents)) {
            $is_mobile=1;
        }

        if (isset($_SERVER['ALL_HTTP'])) {
            if (strpos(0<strtolower($_SERVER['ALL_HTTP']),'OperaMini')) {
                $is_mobile=1;
            }
        }

        if (strpos(0<strtolower($_SERVER['HTTP_USER_AGENT']),'windows')) {
            $is_mobile=0;
        }

		$ajaxrequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		$data['is_device_mobile'] = $is_mobile;
		$data['url_1'] = urlencode(html_entity_decode($data['moduleData']['content'][$current_language['code']])) . " ";
		$data['url_2'] = isset($_SERVER["HTTPS_HOST"]) ? $_SERVER["HTTPS_HOST"]:$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; 
		$data['img_src'] = HTTP_SERVER.$data['whatsappshare_image'];
		$data['img_height'] = $data['moduleData']['ImageHeight'];
		$data['img_width'] = $data['moduleData']['ImageWidth'];
		if ($ajaxrequest == false) {
			if(isset($data['product_id'])) {
				$data['product_id'] = $this->request->get['product_id'];
			} else {
				$data['product_id'] = '';
			}
			if (version_compare(VERSION, '2.2.0.0', '<')) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$this->modulePath.'.tpl')) {
					return $this->load->view($this->config->get('config_template').'/template/'.$this->modulePath.'.tpl', $data);
				} else {
					return $this->load->view('default/template/'.$this->modulePath.'.tpl', $data);
				}
			} else {
				return $this->load->view($this->modulePath, $data);
			}
		} else {
			if (version_compare(VERSION, '2.2.0.0', '<')) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$this->modulePath.'/whatsappshare.tpl')) {
					return $this->load->view($this->config->get('config_template').'/template/'.$this->modulePath.'/'.$this->moduleNameSmall.'.tpl', $data);
				} else {
					return $this->load->view('default/template/'.$this->modulePath.'/'.$this->moduleNameSmall.'.tpl', $data);
				}
			} else {
				return $this->load->view($this->modulePath.'/whatsappshare', $data);
			}
		}


				
    }

	public function getindex() {
		$this->response->setOutput($this->index());
	}
	
	public function loglv() {
		// Session variable create
		if(!isset($this->session->data['whatsappshare_products'])) {
			$this->session->data['whatsappshare_products'] = array();
		}
		
		if (isset($this->request->get['product_id'])) {
			if (!in_array($this->request->get['product_id'], $_SESSION['whatsappshare_products'])) {
				$_SESSION['whatsappshare_products'][] = $this->request->get['product_id'];
			} else {
				foreach ($_SESSION['whatsappshare_products'] as $k=>$v) {
					if($v == $this->request->get['product_id']) {
						unset($_SESSION['whatsappshare_products'][$k]);
						$_SESSION['whatsappshare_products'][] = $this->request->get['product_id'];
						$_SESSION['whatsappshare_products'] = array_values($_SESSION['whatsappshare_products']);
					}
				}
			}
		}
		
	}
    
    public function getCatalogURL($store_id){
        if(isset($store_id) && $store_id){
            $storeURL = $this->db->query('SELECT url FROM `'.DB_PREFIX.'store` WHERE store_id=' . $store_id)->row['url'];
        }elseif (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }
	
	
}
?>
