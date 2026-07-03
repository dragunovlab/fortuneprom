<?php
/**
 * @author COREIT coreit.com.ua <core1@coreit.com.ua>
 */

if(class_exists('CoreIt_Mobile_Detect') == false) {  require_once(DIR_SYSTEM . 'library/CoreIt_Mobile_Detect.php'); }

class ControllerExtensionModuleWidgetMultiConnect extends Controller {

	private $detected = null;

	const PRE_NAME_MODULE = 'multi_connect_';

	const VALUE_NAME  	   = 'valueName';
	const STATUS_NAME 	   = 'statusName';
	const CSS_CLASS        = 'cssClass';
	const RUN_COMMAND	   = 'runCommand';
	const TELEGRAM_NAME    = 'telegram_username';
	const WHATSAPP_NAME    = 'whatsapp_phone_number';
	const SKYPE_NAME       = 'skype_login';
	const VIBER_NAME       = 'viber_phone_number';
	const MESSENGER_NAME   = 'messenger_id';
	const VK_NAME		   = 'vk_id';
	const LINE_NAME		   = 'line_id';
	const PHONE_NAME	   = 'phone_number';
	const EMAIL_NAME	   = 'email';
    const INSTAGRAM_NAME   = 'instagram';
	const DEFAULT_COLOR    = '#cd4e37';
	const DEFAULT_POSITION = 'right';
	const CALL_BACK 	   = 'call_back';
	const TARGET_BLANK	   = 'target_blank';
    const IS_CHANGE        = 'is_change';

    public function __construct($registry) {
        $this->detected = new CoreIt_Mobile_Detect;
        parent::__construct($registry);
    }


	public function index(&$route, &$data, &$output)
	{
		$this->document->addStyle('catalog/view/theme/default/stylesheet/widget-multi-connect-v09.css');
		$this->load->language('extension/module/widget_multi_connect');
		/**
		 * array config messengers self::RUNCOMMAND the format stirg used is the same as in the function sprintf()
		 */
		$messengers_data = [
			[
				self::VALUE_NAME   => self::TELEGRAM_NAME,
				self::STATUS_NAME  => 'telegram_status',
				self::CSS_CLASS    => 'widget-connect__button-telegram',
				self::RUN_COMMAND  => 'https://t.me/%s',
			], [
				self::VALUE_NAME   => self::WHATSAPP_NAME,
				self::STATUS_NAME  => 'whatsupp_status',
				self::CSS_CLASS    => 'widget-connect__button-whatsapp',
				self::RUN_COMMAND  => 'https://wa.me/%s'
			], [
				self::VALUE_NAME   => self::SKYPE_NAME,
				self::STATUS_NAME  => 'skype_status',
				self::CSS_CLASS    => 'widget-connect__button-skype',
				self::RUN_COMMAND  => 'skype:%s?chat'
			],
			[
				self::VALUE_NAME   => self::VIBER_NAME,
				self::STATUS_NAME  => 'viber_status',
				self::CSS_CLASS    => 'widget-connect__button-viber',
				self::RUN_COMMAND  => 'viber://chat?number=%s',
				self::CALL_BACK	   => 'prepareViberValue'

			], [
				self::VALUE_NAME   => self::MESSENGER_NAME,
				self::STATUS_NAME  => 'messenger_status',
				self::CSS_CLASS    => 'widget-connect__button-messenger',
				self::RUN_COMMAND  => 'https://m.me/%s'
			], [
				self::VALUE_NAME   => self::VK_NAME,
				self::STATUS_NAME  => 'vk_status',
				self::CSS_CLASS    => 'widget-connect__button-vk',
				self::RUN_COMMAND  => 'https://vk.me/%s'
			], [
				self::VALUE_NAME   => self::LINE_NAME,
				self::STATUS_NAME  => 'line_status',
				self::CSS_CLASS    => 'widget-connect__button-line',
				self::RUN_COMMAND  => 'line://ti/p/%s'
			], [
                self::VALUE_NAME   => self::INSTAGRAM_NAME,
                self::STATUS_NAME  => 'instagram_status',
                self::CSS_CLASS    => 'widget-connect__button-instagram',
                self::RUN_COMMAND  => 'https://www.instagram.com/%s',
                self::CALL_BACK	   => 'prepareInstagramValue'
            ],[
				self::VALUE_NAME   => self::PHONE_NAME,
				self::STATUS_NAME  => 'phone_status',
				self::CSS_CLASS    => 'widget-connect__button-telephone',
				self::RUN_COMMAND  => 'tel:%s'
			], [
				self::VALUE_NAME   => self::EMAIL_NAME,
				self::STATUS_NAME  => 'email_status',
				self::CSS_CLASS    => 'widget-connect__button-email',
				self::RUN_COMMAND  => 'mailto:%s'
			]
		];

		$data['messengers'] = array_reduce($messengers_data,
			function ($acc, $messenger) {
				if ($this->getMessengerStatus($messenger[self::STATUS_NAME]) === 'on') {
					$acc[$messenger[self::VALUE_NAME]] = [
						'value'    => $this->getMessengerValue($messenger),
						'title'    => $this->getMessengerTitle($messenger),
						'cssClass' => $messenger[self::CSS_CLASS]
					];
				}
				return $acc;
			}, []);
		$data['color_main_button'] = $this->getCollorMainButton();
		$data['position']          = $this->getPosition();
		$data['target_blank']      = $this->getBlank($this->detected);


		$widget_html = $this->load->view('extension/module/widget_multi_connect', $data);
		$output = $widget_html . $output;
	}

    /**
     * @param array $messenger_data
     * @return string
     */
    private function getMessengerValue($messenger_data) {
        $result = $this->config->get(self::PRE_NAME_MODULE . $messenger_data[self::VALUE_NAME]);
        if (isset($messenger_data[self::CALL_BACK])) {
            $callback = $messenger_data[self::CALL_BACK];
            if (method_exists($this, $callback)) {
                $result = $this->$callback($result);
            }
        }
        if (is_array($result) && isset($messenger_data[self::CALL_BACK])) {
            if ($result[self::IS_CHANGE]) {
                return $result['data'];
            }
            return sprintf($messenger_data[self::RUN_COMMAND], $result['data']);
        }
        if ($result && !empty($result)) {
            return sprintf($messenger_data[self::RUN_COMMAND], $result);
        }
        return '';
    }

	/**
	 * @param string $status_name
	 * @return string
	 */
	private function getMessengerStatus($status_name) {
		return $this->config->get(self::PRE_NAME_MODULE . $status_name);
	}

	/**
	 * @param array $messenger_data
	 * @return string
	 */
	private function getMessengerTitle($messenger_data) {
		return $this->language->get('text_' . $messenger_data[self::VALUE_NAME]);
	}

	/**
	 * @return string css type color
	 */
	private function getCollorMainButton() {
		$result = $this->config->get('multi_connect_color_main_button');
		if ($result && !empty($result)) {
			return $result;
		}
		return self::DEFAULT_COLOR;
	}

	/**
	 * @return string css class
	 */
	private function getPosition() {
		$result = '';
		$result .= $this->config->get('multi_connect_position');
		if (!$result && empty($result)) {
			$result .= self::DEFAULT_POSITION;
		}
		 $placment = $result === self::DEFAULT_POSITION ? 'left' : self::DEFAULT_POSITION;
		return ['placment' => $placment, 'css_class' => $result];
	}

    /**
     * @param $value
     * @return array
     */
    private function prepareViberValue($value) {
        $result = [self::IS_CHANGE => false, 'data' => $value];
        /**
         * @var $param CoreIt_Mobile_Detect
         */
        if (!$this->detected->isMobile() && !$this->detected->isTablet()) {
            $result['data'] = sprintf('+%s', $value);
            return $result;
        } else if ($this->detected->isiOS()) {
            $result['data'] = sprintf('+%s', $value);
            return $result;
        }
        return $result;
    }

    /**
     * @param $value
     * @return array
     */
    private function prepareInstagramValue($value) {
        $result = [self::IS_CHANGE => false, 'data' => $value];
        if ($this->detected->isMobile() && $this->detected->isAndroidOS()) {
            $result['data'] =
                sprintf(
                    'intent://instagram.com/_u/%s/#Intent;package=com.instagram.android;scheme=https;end',
                    $value
                );
            $result[self::IS_CHANGE] = true;
            return $result;
        }
        if ($this->detected->isMobile() && $this->detected->isiOS()) {
            $result['data'] =
                sprintf(
                    'instagram://user?username=%s',
                    $value
                );
            $result[self::IS_CHANGE] = true;
            return $result;
        }
        return $result;
    }

    /**
     * @param $param
     * @return string
     */
	private function getBlank($param) {
		if (!$param->isMobile() && !$param->isTablet()) {
			return 'target="_blank"';
		}
		return '';
	}
}
