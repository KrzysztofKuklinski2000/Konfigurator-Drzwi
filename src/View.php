<?php 

namespace App;

class View {
    private string $templatePath = 'templates/';
    public function render(?array $data): void {
        require dirname(__DIR__) . '/'. $this->templatePath . 'layout.php';
    }
}