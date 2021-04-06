<?php
class IndexController
{
    private $View;

    public function __construct()
    {
        $this->View = new IndexView();
        $this->View->ProductAPIModel = new ProductAPIModel('https://dev14.ageraehandel.se/sv/api/product');
        $this->View->render();
    }
}
