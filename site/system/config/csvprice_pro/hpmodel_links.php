<?php

/*
 *  init $getProductId Method:
 *  getProductIdBySku		- by Product SKU
 *  getProductIdByModel	- by Product Model
 *  getProductIdByName	- by Product Name
 *  getProductIdById		- by Product ID
 * 
 * 
 *  DataFomat: 
 * 	{parent_id}|{image}|{sort}|{type_id}
 * 	{parent_id}|{image}|{sort}
 * 	{parent_id}|{sort}
 * 	{parent_id}|{image}
 * 	{parent_id}
 *  Do not change a delimiter char "|" in DataFomat
 */

$_['csvprcie_pro_hpmodel_links'] = array(
	'getProductId' => 'getProductIdBySku',
	'FieldDelimiter' => '|',
	'StringDelimiter' => "\n",
	'ImageCatalog' => 'catalog/',
	'DataFormat' => '{parent_id}|{image}'
);
