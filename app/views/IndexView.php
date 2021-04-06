<?php
class IndexView
{
    private $title = 'Montania prov 2021';

    /**
     * Renders index page
     */
    public function render(): void
    {
        $file = $this->isCLI() ? 'index-cli.php' : 'index.php';
        require ROOT . '/template/' . $file;
    }

    /**
     * Will check if app is run from Command line or not
     * @return bool True if CLI, else false
     */
    private function isCLI(): bool
    {
        if (
            defined('STDIN')
            || php_sapi_name() === 'cli'
            || array_key_exists('SHELL', $_ENV)
            || (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0)
            || !array_key_exists('REQUEST_METHOD', $_SERVER)
        ) {
            return true;
        }
        return false;
    }
}
