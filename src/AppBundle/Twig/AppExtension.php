<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $environmentMode;

    public function __construct($environmentMode)
    {
        $this->environmentMode = $environmentMode;
    }

    public function getGlobals() {
        return [
            'environmentMode' => $this->environmentMode
        ];
    }

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('quotation', [$this, 'quotationFilter']),
        ];
    }

    public function quotationFilter($text, $symbol) {
        return "$symbol $text $symbol";
    }

    public function getName() {
        return 'app_extension';
    }
}