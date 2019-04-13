<?php

namespace App;

class Cart
{
    
	private $items;
	private $total_quantity;
	private $total_price;

	public function __construct( $old_cart )
	{

		if( $old_cart ) {

			$this->items = $old_cart->items;
			$this->total_quantity = $old_cart->total_quantity;
			$this->total_price = $old_cart->total_price;

		}

	}

	public function getItems()
	{

		return $this->items;

	}

	public function getTotalQuantity()
	{

		return $this->total_quantity;

	}

	public function getTotalPrice()
	{

		return $this->total_price;

	}

	public function addProductToCart( $product, $quantity )
	{

		$products = [

			'qty'		=>	0,
			'price'		=>	$product->price,
			'product'	=>	$product,

		];

		if( $this->items ) {

			if( array_key_exists( $product->slug, $this->items ) ) {

				$products = $this->items[ $product->slug ];

			}

		}

		$products[ 'qty' ] += $quantity;
		$products[ 'price' ] = $product->price * $products[ 'qty' ];
		$this->items[ $product->slug ] = $products;

		$this->total_quantity += $quantity;
		$this->total_price += $product->price;
	
	}

	public function removeProductFromCart( $product )
	{

		if( $this->items ) {

			if( array_key_exists( $product->slug, $this->items ) ) {

				$remove_product = $this->items[ $product->slug ];
				$this->total_quantity -= $remove_product[ 'qty' ];
				$this->total_price -= $remove_product[ 'price' ];
				
				array_forget( $this->items, $product->slug );

			}

		}
	
	}

	public function updateProductInCart( $product, $quantity )
	{

		if( $this->items ) {

			if( array_key_exists( $product->slug, $this->items ) ) {

				$products = $this->items[ $product->slug ];

			}

		}

		$this->total_quantity -= $products[ 'qty' ];
        $this->total_price -= $products[ 'price' ];

        $products[ 'qty' ] = $quantity;
		$products[ 'price' ] = $product->price * $quantity;
		$this->items[ $product->slug ] = $products;

		$this->total_quantity += $quantity;
		$this->total_price += $products[ 'price' ];
	
	}

}
