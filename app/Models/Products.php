<?php
declare(strict_types = 1);

namespace App\Models;

use App\Models\Product;
use App\Exceptions\ModelExceptions\NonIntKeyException;
use App\Exceptions\ModelExceptions\NotProductTypeException;

/**
 * Products container - container data structure.
 */
class Products implements \ArrayAccess, \Iterator{
    /**
     * The products stored in this object.
     */
    private array $products = [];

    /**
     * The length of the product stored in this object.
     */
    private int $length = 0;

    /**
     * The pointer to keep track of element position
     * when iterating through the products.
     */
    private int $pointer = 0;


    /* The ArrayAccess method implementations *\

    /**
     * Get the product using array style.
     * 
     * @param int $offset The index of the product
     * @return Product|null Return the product if it exists
     *                      or return null
     */
    public function offsetGet(mixed $offset): mixed{
        if(isset($this->products[$offset]))
            return $this->products[$offset];
        return null;
    }

    /**
     * Set the product at a given index in the array style.
     * 
     * @param int $offset The indext to set the product in
     * @param Product $value The product to set
     */
    public function offsetSet(mixed $offset, mixed $value): void{
        if(!\is_int($offset)) 
            throw new NonIntKeyException();

        if(!\gettype($value) == "Product") 
            throw new NotProductTypeException();

        if(\is_null($offset))
            $this->products[] = $value;
        else
            $this->products[$offset] = $value;
    }

    /**
     * Check if a product exists at a given index.
     * 
     * @param int $offset The index to check at
     * @return bool True if the product exists or false otherwise
     */
    public function offsetExists(mixed $offset): bool{
        return isset($this->products[$offset]);
    }

    /**
     * Deletes a product in the given index and re-index the products.
     * 
     * @param int $offset The index of the product
     */
    public function offsetUnset(mixed $offset): void{
        if(!\is_int($offset)) 
            throw new NonIntKeyException();

        unset($this->products[$offset]);
        $this->products = array_values($this->products);
    }


    /* The Iterator method implementations */

    /**
     * Get the current product of the iterator.
     * 
     * @return Product The current product 
     */
    public function current(): mixed{
        return $this->products[$this->pointer];
    }

    /**
     * Move to the next available product in the iterator.
     */
    public function next(): void{
        $this->pointer++;
    }

    /**
     * Get the current key of the iterator.
     * 
     * @return int The current key
     */
    public function key(): mixed{
        return $this->pointer;
    }

    /**
     * Reset the pointer to the first product element.
     */
    public function rewind(): void{
        $this->pointer = 0;
    }

    /**
     * Check if the pointer is still pointing to 
     * an exesting index of the iterator.
     * 
     * @return bool True if the pointer is pointing to an
     *              existing product or false otherwise
     */
    public function valid(): bool{
        return count($this->products) > $this->pointer;
    }


    /* Class methods */

    /**
     * Get the number of product in this products onbject.
     * 
     * @return int Number of products
     */
    public function getLength(): int{
        return $this->length;
    }

    /**
     * Add a product to the array of products.
     * 
     * @param Product $product The product to add
     */
    public function add(Product $product){
        $this->products[] = $product;
        $this->length++;
    }

    /**
     * Remove a given product from the products.
     * 
     * @param Product $product The product to remove
     */
    public function remove(Product $product){
        $index = \array_search($product, $this->products);
        if($index === false) return;
        $this->removeAt($index);
    }

    /**
     * Remove an element at a given index 
     * and returns the element removed.
     * 
     * @param int $index The index of the element
     * @return Product The element removed
     * @throws \OutOfBoundsException
     */
    public function removeAt(int $index): Product{
        if($index < 0 || $this->length - 1 < $index) 
            throw new \OutOfBoundsException();
        
        $prod = $this->products[$index];
        unset($this->products[$index]);
        $this->products = array_values($this->products);
        return $prod;
    }

    /**
     * Compare to two products objects and return the matches in a products object.
     * 
     * @param Products $products1 The first object to compare
     * @param Products $products2 The second object to compare
     */
    static public function getDuplicates(
        Products $products1, 
        Products $products2
        ): Products{
        $arr = \array_intersect(
            $products1->toArray(), 
            $products2->toArray()
        );
        return static::arrayToProducts($arr);
    }

    /**
     * Convert an array type to products object.
     * 
     * The method discards all elements that are not of
     * type Product.
     * 
     * @param array $products The array of products to convert
     * @return Products The resulting Products object after adding 
     *                  all elements of type Product to it
     */
    static public function arrayToProducts(array $products){
        $prods = new Products();
        foreach($products as $product){
            if(\get_class($product) == __NAMESPACE__ . "\Product"){
                $prods->add($product);
            }
        }
        return $prods;
    }

    /**
     * Get the products in an array type.
     */
    public function toArray(): array{
        return $this->products;
    }
}