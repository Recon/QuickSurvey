<?php

namespace Recon\AppBundle\Twig;

class AutoLinkTwigExtension extends \Twig_Extension{

    public function getFilters(){
        return [
            'auto_url' => new \Twig_Filter_Method($this, 'auto_link_text', ['is_safe' => ['html']]),
        ];
    }

    public function getName(){
        return "auto_link_twig_extension";
    }

    static public function auto_link_text($string){
        if(!preg_match("~^(?:f|ht)tps?://~i", $string)){
            $string = "http://" . $string;
        }

        return $string;
    }

}
