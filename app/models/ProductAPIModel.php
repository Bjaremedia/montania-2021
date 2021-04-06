<?php
class ProductAPIModel
{
    private $target_url;
    private $products = [];
    private $product_count = 0;
    private $stats = [];

    /**
     * Will load product data from target url on object creation
     * @param string $url Target url containing JSON type product data
     */
    public function __construct(string $url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $this->target_url = $url;
        }

        $this->loadProductsFromJson();
        usort($this->products, ['self', 'sortProducts']);
        $this->product_count = count($this->products);
        $this->getPriceDifferences();
    }

    /**
     * Getter for target URL
     * @return string Target URL string
     */
    public function getTargetUrl(): string
    {
        return $this->target_url;
    }

    /**
     * Return loaded products
     * @return array Products loaded from URL
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Count amount of products
     * @return int Products count
     */
    public function getProductCount(): int
    {
        return $this->product_count;
    }

    /**
     * Get specified stats
     * @return mixed Null if key doesn't exist, else return value for stat
     */
    public function getStats(string $key = null): mixed
    {
        if ($key === null) {
            return $this->stats;
        }
        return isset($this->stats[$key]) ? $this->stats[$key] : null;
    }

    /**
     * Count products for each category
     * @param string|null $category Category name/id (count products not in a category if input === null)
     * @return int Count for products in category
     */
    public function countProductsInCategory($category): int
    {
        $count = 0;
        foreach($this->products as $product) {
            if(!isset($product->artikelkategorier_id)) {
                if($category === null) {
                    $count++;
                }
                continue;
            }
            if($product->artikelkategorier_id == $category) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Calculates the price difference for products array
     */
    public function getPriceDifferences(): void
    {
        $product_price = $this->products[0]->pris * ($this->products[0]->momssats / 100 + 1);
        $this->stats['price_lowest'] = $product_price;
        $this->stats['price_lowest_not_zero'] = $product_price;
        $this->stats['price_highest'] = $product_price;
        for ($i = 1; $i < $this->product_count; $i++) {
            $product_price = $this->products[$i]->pris * ($this->products[$i]->momssats / 100 + 1);
            $this->stats['price_lowest'] = $product_price < $this->stats['price_lowest'] ? $product_price : $this->stats['price_lowest'];
            $this->stats['price_lowest_not_zero'] = $product_price < $this->stats['price_lowest_not_zero'] && $product_price > 0 ? $product_price : $this->stats['price_lowest_not_zero'];
            $this->stats['price_highest'] = $product_price > $this->stats['price_highest'] ? $product_price : $this->stats['price_highest'];
        }
    }

    /**
     * Load products from target url property, expects JSON at source
     * Save loaded data to products property
     */
    private function loadProductsFromJson(): void
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->target_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        $source = json_decode(
            curl_exec($curl)
        );
        curl_close($curl);
        if (is_object($source) && isset($source->products)) {
            $this->products = $source->products;
        }
    }

    /**
     * Usort function for products data
     * @param object $a First object for comparsion
     * @param object $b Second object for comparsion
     * @return int Sort order
     */
    private static function sortProducts(object $a, object $b): int
    {
        if (!isset($a->artikelkategorier_id) && isset($b->artikelkategorier_id)) {
            return +1;
        }
        if (isset($a->artikelkategorier_id) && !isset($b->artikelkategorier_id)) {
            return -1;
        }
        if (!isset($a->artikelkategorier_id) && !isset($b->artikelkategorier_id)
        || ($a->artikelkategorier_id == $b->artikelkategorier_id)) {
            return self::sortByProductName($a, $b);
        }
        return strcmp($a->artikelkategorier_id, $b->artikelkategorier_id);
    }

    /**
     * Sort products array by name (made for use with Usort)
     * @param object $a First object for comparsion
     * @param object $b Second object for comparsion
     * @return int Sort order
     */
    private static function sortByProductName(object $a, object $b): int
    {
        if (!isset($a->artiklar_benamning) && isset($b->artiklar_benamning)) {
            return +1;
        }
        if (isset($a->artiklar_benamning) && !isset($b->artiklar_benamning)) {
            return -1;
        }
        if (!isset($a->artiklar_benamning) && !isset($b->artiklar_benamning)) {
            return 0;
        }
        return strcmp($a->artiklar_benamning, $b->artiklar_benamning);
    }
}
