<!DOCTYPE html>
<html>

<head>
    <title><?= $this->title ?></title>
    <link rel="stylesheet" type="text/css" href="public/css/normalize.css">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="public/css/style.css">
    <script src="https://kit.fontawesome.com/cdfcf166fd.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <header id="header">
        <div id="header-logotype">
            <img src="public/images/montania-logotype.png" alt="Montania - Smarta Webb & Affärssystem">
            <p>PROV PHP UTVECKLARE</p>
        </div>
    </header>

    <main id="wrapper">
        <section id="content">
            <h1>Provresultat, ren PHP-kod</h1>

            <ul id="product-short-info-list">
                <li><strong>Antal artiklar:</strong> <?= $this->ProductAPIModel->getProductCount() ?> st</li>
                <li><strong>Lägsta pris:</strong> <?= $this->ProductAPIModel->getStats('price_lowest') ?> kr*</li>
                <?php
                if ($this->ProductAPIModel->getStats('price_lowest') == 0) {
                    echo '<li><strong> Lägsta pris som inte är 0:</strong> ' . $this->ProductAPIModel->getStats('price_lowest_not_zero') . ' kr*</li>';
                }
                ?>
                <li><strong>Högsta pris:</strong> <?= $this->ProductAPIModel->getStats('price_highest') ?> kr*</li>
            </ul>
            <p id="product-short-info-vat"><i>* Pris inkl. moms</i></p>

            <table id="product-table" cellspacing="0">
                <thead>
                    <tr id="product-table-header">
                        <th class="product-table-category">Kategori</th>
                        <th class="product-table-article-id">Artikelnummer</th>
                        <th>Benämning</th>
                        <th>Pris inkl. moms</th>
                        <th>Lagersaldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $category = null;
                    foreach ($this->ProductAPIModel->getProducts() as $product) {
                        if ((isset($product->artikelkategorier_id) && $category !== $product->artikelkategorier_id)
                            || (!isset($product->artikelkategorier_id) && $category !== null)
                        ) {
                            $category = isset($product->artikelkategorier_id) ? $product->artikelkategorier_id : null;
                            echo '<tr class="product-new-category-row">';
                            echo '<td class="product-category-cell" rowspan="' . $this->ProductAPIModel->countProductsInCategory($category) . '">';
                            echo isset($product->artikelkategorier_id) ? ucfirst(strtolower($product->artikelkategorier_id)) : '<span class="product-missing-data">Artikelkategori saknas</span>';
                            echo '</td>';
                        } else {
                            echo '<tr>';
                        }
                        echo '<td class="product-article-no">' . $product->id . '</td>';
                        echo '<td>' . (isset($product->artiklar_benamning) ? $product->artiklar_benamning : '<span class="product-missing-data">Artikelbenämning saknas</span>') . '</td>';
                        echo '<td class="align-right">';
                        if (isset($product->pris)) {
                            echo isset($product->momssats) ? $product->pris * ($product->momssats / 100 + 1) . ' ' : $product->pris . '<span class="product-missing-data"> Momssats saknas</span>';
                        } else {
                            echo '-';
                        }
                        echo $product->valutor_id . '</td>';
                        echo '<td class="align-right"><span class="product-in-stock">';
                        if (isset($product->lagersaldo)) {
                            if ($product->lagersaldo > 0) {
                                echo '<span class="product-in-stock">' . $product->lagersaldo . ' st <i class="fas fa-check-circle"></i></span>';
                            } else {
                                echo '<span class="product-out-of-stock">0 st <i class="fas fa-times-circle"></i></span>';
                            }
                        } else {
                            echo '<span class="product-missing-data">Lagersaldo saknas</span>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer id="footer">
        <address>
            <strong>Emil Johansson</strong><br>
            Boarpsvägen 310, 266 97, Hjärnarp, Sverige<br>
            +46 (0)73-399 44 72<br>
            <a href="mailto:emil.johansson@teletastik.se">emil.johansson@teletastik.se</a>
        </address>
    </footer>
</body>

</html>