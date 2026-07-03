<?php
class ModelExtensionModuleGifts extends Model {
// Получение подарочных товаров
	public function getGiftProducts($category_id=0, $product_id=0) {
		$sql='SELECT * FROM '.DB_PREFIX.'product_gifts pg LEFT JOIN '.DB_PREFIX.'product_description pd ON (pg.product_id=pd.product_id) LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (pg.product_id=p2c.product_id) LEFT JOIN '.DB_PREFIX.'product p ON(p.product_id=pg.product_id) WHERE p2c.category_id="'.(int)$category_id.'" AND pd.language_id='.(int)$this->config->get('config_language_id').' AND pg.product_id NOT IN('.(int)$product_id.') ORDER BY pg.activation_amount ASC';
		$query=$this->db->query($sql);
		if($query->num_rows) {
			$products=array();
			foreach($query->rows as $product) {
				$products[]=array(
					'product_id'=>$product['product_id'],
					'name'=>$product['name'],
					'description'=>$product['description'],
					'activation_amount'=>$product['activation_amount'],
					'show_link'=>$product['show_link'],
					'href'=>$this->url->link('product/product', 'product_id='.$product['product_id'].($category_id!=0?('&path='.$category_id):''))
				);
			}
			return $products;
		} else {
			return false;
		}
	}
	public function addGiftsInfoToCart($product_id, $gifts) {
		$sql='INSERT INTO '.DB_PREFIX.'cart_gifts (`customer_id`, `session_id`, `product_id`, `gift_product_id`) VALUES';
		$tmp=array();
		foreach($gifts as $gift) {
			$tmp[]=' ('.(int)$this->customer->getId().', "'.$this->db->escape($this->session->getId()).'", '.(int)$product_id.', '.(int)$gift.')';
		}
		$sql.=implode(',', $tmp);
		//echo $sql;
		$this->db->query($sql);
	}
	public function getGiftsInCart($product_id=0) {
		$sql='SELECT *, cg.product_id AS product_id FROM '.DB_PREFIX.'cart_gifts cg LEFT JOIN '.DB_PREFIX.'product_description pd ON(cg.gift_product_id=pd.product_id) WHERE cg.customer_id="'.(int)$this->customer->getId().'" AND cg.session_id="'.$this->db->escape($this->session->getId()).'" AND pd.language_id="'.(int)$this->config->get('config_language_id').'"';
		if($product_id!=0) {
			$sql.=' AND cg.product_id="'.(int)$product_id.'"';
		}
		$query=$this->db->query($sql);
		return $query->rows;
	}
	public function getGiftsInOrder($order_id, $product_id) {
		$sql='SELECT * FROM '.DB_PREFIX.'order_product_gifts opg  WHERE order_id="'.(int)$order_id.'" AND product_id="'.(int)$product_id.'"';
		$query=$this->db->query($sql);
		return $query->rows;
	}
}
?>