<?php 

namespace App;

class View {
    private string $templatePath = 'templates/';
    public function render(?array $data): void {
        require dirname(__DIR__) . '/'. $this->templatePath . 'layout.php';
    }

    public function renderToString(string $template, array $data = []): string
    {
        ob_start(); 
        include dirname(__DIR__) . '/templates/' . $template . '.php';
        return ob_get_clean(); 
    }
}