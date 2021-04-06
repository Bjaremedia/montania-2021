<?php
echo PHP_EOL;
echo 'Antal artiklar: ' . $this->ProductAPIModel->getProductCount() . ' st' . PHP_EOL;
echo 'Lägsta pris: ' . $this->ProductAPIModel->getStats('price_lowest') . ' kr (inkl. moms)' . PHP_EOL;
if ($this->ProductAPIModel->getStats('price_lowest') == 0) {
    echo 'Lägsta pris som inte är 0: ' . $this->ProductAPIModel->getStats('price_lowest_not_zero') . ' kr (inkl. moms)' . PHP_EOL;
}
echo 'Högsta pris: ' . $this->ProductAPIModel->getStats('price_highest') . ' kr (inkl. moms)' . PHP_EOL;
echo PHP_EOL;
echo 'PRODUKTLISTA' . PHP_EOL;
echo 'Hämtad från: ' . $this->ProductAPIModel->getTargetUrl() . PHP_EOL;
echo PHP_EOL;

$category = null;
foreach ($this->ProductAPIModel->getProducts() as $product) {
    if ((isset($product->artikelkategorier_id) && $category !== $product->artikelkategorier_id)
        || (!isset($product->artikelkategorier_id) && $category !== null)
    ) {
        $category = isset($product->artikelkategorier_id) ? $product->artikelkategorier_id : null;
        echo ' --- ' . (isset($product->artikelkategorier_id) ? ucfirst(strtolower($product->artikelkategorier_id)) : 'Artikelkategori saknas') . ' --- ' . PHP_EOL;
    }
    echo $product->id . '   ';
    echo (isset($product->artiklar_benamning) ? $product->artiklar_benamning : 'Artikelbenämning saknas!') . '   ';
    if (isset($product->pris)) {
        echo isset($product->momssats) ? $product->pris * ($product->momssats / 100 + 1) . ' inkl. moms ' : $product->pris . ' exkl. moms ';
    } else {
        echo '-';
    }
    echo $product->valutor_id . '   ';
    if (isset($product->lagersaldo)) {
        if ($product->lagersaldo > 0) {
            echo $product->lagersaldo . ' st   ';
        } else {
            echo '0 st   ';
        }
    } else {
        echo 'Lagersaldo saknas!   ';
    }
    echo PHP_EOL;
}
