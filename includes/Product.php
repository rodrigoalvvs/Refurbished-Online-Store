<?php 

class Product {
    public $productId;
    public $basePrice;
    public $discount;


    public function __construct($productId, $basePrice, $discount) {
        $this->productId = $productId;
        $this->basePrice = $basePrice;
        $this->discount = $discount;
    }
    public function getProductId() : int {
        return $this->productId;
    }
    public function getProductPrice() : float{
        return $this->basePrice * (1 - ($this->discount / 100));
    }
    public function getAbsoluteDiscount() : float{
        return $this->basePrice * ($this->discount / 100);
    }
}

?>