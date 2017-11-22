<?php

// Max 5 in quantity per order for sunglasses ie category 25
add_filter( 'woocommerce_add_to_cart_validation', 'add_quantity_validation',10,3);
function add_quantity_validation($passed,$product_id,$quantity) {
	global $woocommerce;
	// Get cart
	$cart = WC()->cart->get_cart();
	$productItems = sizeof($cart);
	$totalQuantity;

	define("categoryExcluded",25);
	define("totalQuantity",4);


	foreach($cart as $cartItem) {
		$catIds = array();
		$prodCats = array();
		$prodAddedCats = get_the_terms($product_id, 'product_cat');
		foreach($prodAddedCats as $prodAddedCat) {
			$prodCat = $prodAddedCat->term_id;
			array_push($prodCats, $prodCat);
		}
		$terms = get_the_terms ( $cartItem['product_id'], 'product_cat' );
		foreach ( $terms as $term ) {
			 $cat_id = $term->term_id;
			 array_push($catIds, $cat_id);
		}
		if(in_array(categoryExcluded, $catIds) && in_array(categoryExcluded, $prodCats)) {
			$totalQuantity += $cartItem['quantity'];
		}

	}
	if($totalQuantity > 4) {
		// Dont allow
		wc_add_notice( __( 'Du kan maks bestille 5 produkter per ordre.', 'woocommerce' ), 'error' );
		$passed = false;
	} else {
		$passed = true;
	}
	return $passed;
}
