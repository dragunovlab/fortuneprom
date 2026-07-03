<?php
class ModelExtensionModuleGifts extends Model{
	public function install() {
		$this->db->query('CREATE TABLE `'.DB_PREFIX.'product_gifts` (`product_id` INT NOT NULL, `activation_amount` FLOAT NOT NULL, `show_link` BOOLEAN NOT NULL DEFAULT FALSE, PRIMARY KEY (`product_id`)) ENGINE=InnoDB;');

		$this->db->query('CREATE TABLE `'.DB_PREFIX.'cart_gifts` (`gift_id` INT NOT NULL AUTO_INCREMENT , `customer_id` INT NOT NULL , `session_id` VARCHAR(32) NOT NULL , `product_id` INT NOT NULL , `gift_product_id` INT NOT NULL , PRIMARY KEY (`gift_id`)) ENGINE = InnoDB');
		
		$this->db->query('CREATE TABLE `'.DB_PREFIX.'order_product_gifts` (`gift_id` INT NOT NULL AUTO_INCREMENT, `order_id` INT NOT NULL, `product_id` INT NOT NULL, `gift_product_id` INT NOT NULL, `gift_product_info` VARCHAR(255) NOT NULL, PRIMARY KEY (`gift_id`)) ENGINE = InnoDB;');
	}

	public function uninstall() {
		$this->db->query('DROP TABLE `'.DB_PREFIX.'product_gifts`');
		$this->db->query('DROP TABLE `'.DB_PREFIX.'cart_gifts`');
		$this->db->query('DROP TABLE `'.DB_PREFIX.'order_product_gifts');
	}

	public function getGifts() {
		$query=$this->db->query('SELECT * FROM '.DB_PREFIX.'product_gifts pg LEFT JOIN '.DB_PREFIX.'product_description pd ON (pg.product_id=pd.product_id) LEFT JOIN '.DB_PREFIX.'product p ON(pg.product_id=p.product_id) WHERE pd.language_id="'.(int)$this->config->get('config_language_id').'"');

		return $query->rows;
	}

	public function edit($data) {
		$this->db->query('DELETE FROM '.DB_PREFIX.'product_gifts');
		foreach($data as $product_id=>$product) {
			$this->db->query('INSERT INTO '.DB_PREFIX.'product_gifts VALUES('.(int)$product_id.', '.(isset($product['activation_amount'])?(int)$product['activation_amount']:0).', '.(isset($product['show_link'])?(int)$product['show_link']:0).')');
			if(isset($product['status']) && $product['status']==1) {
				$this->db->query('UPDATE '.DB_PREFIX.'product SET status=0 WHERE product_id='.(int)$product_id);
			} else {
				$this->db->query('UPDATE '.DB_PREFIX.'product SET status=1 WHERE product_id='.(int)$product_id);
			}
		}
	}
	public function getGiftsInOrder($order_id, $product_id) {
		$sql='SELECT * FROM '.DB_PREFIX.'order_product_gifts opg  WHERE order_id="'.(int)$order_id.'" AND product_id="'.(int)$product_id.'"';
		$query=$this->db->query($sql);
		return $query->rows;
	}

}
?>