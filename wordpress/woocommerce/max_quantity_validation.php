<?php

add_filter( 'woocommerce_add_to_cart_validation', 'add_quantity_validation',10,3);
function add_quantity_validation($passed,$product_id,$quantity) {
	global $woocommerce;
	// Get cart
	$cart = WC()->cart->get_cart();
	$productItems = sizeof($cart);
	$totalQuantity;

	$categoriesExcluded = array(15,16);
	define("totalQuantity",4);

	// Check if product id is in relevant category
	function isProductInRelevantCategory($prodId,$categoriesExcluded) {
		$productIsInRelevantCategory = false;

		$prodAddedCategories = array();
		$prodAddedCats = get_the_terms($prodId, 'product_cat');
		// Get array of category ids of added product
		foreach($prodAddedCats as $prodAddedCat) {
			$prodCat = $prodAddedCat->term_id;
			array_push($prodAddedCategories, $prodCat);
		}

		// Check if added product categories contains product id
		foreach($categoriesExcluded as $excludedCat){
			foreach($prodAddedCategories as $prodAddedCategory) {
				if($prodAddedCategory === $excludedCat) {
					$productIsInRelevantCategory = true;
				}
			}
		}

		return $productIsInRelevantCategory;
	}

	function checkQuantityOfCartItems($cart,$categoriesExcluded,$totalQuantity) {
		foreach($cart as $cartItem) {
			if(isProductInRelevantCategory($cartItem['product_id'],$categoriesExcluded)) {
				$totalQuantity += $cartItem['quantity'];
			};
		}
		return $totalQuantity;
	}

	if(isProductInRelevantCategory($product_id,$categoriesExcluded)) {
		// Check quantity
		$totalQuantity = checkQuantityOfCartItems($cart,$categoriesExcluded,$totalQuantity);
	} else {
		// Approve cart add
	};


	// If in relevant id, count number of products in relevant category
	if($totalQuantity > totalQuantity) {
		// Dont allow
		wc_add_notice( __( 'Du kan maks bestille 5 produkter per ordre.', 'woocommerce' ), 'error' );
		$passed = false;
	} else {
		$passed = true;
	}
	return $passed;


}
