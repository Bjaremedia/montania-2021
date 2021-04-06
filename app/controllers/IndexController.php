<?php
class IndexController
{
    private $View;

    /**
     * Create new view, set model to view and render page
     */
    public function __construct()
    {
        $this->View = new IndexView();
        $this->View->ProductAPIModel = new ProductAPIModel('https://dev14.ageraehandel.se/sv/api/product');
        $this->View->render();
    }
}
