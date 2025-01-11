<?php


if (!function_exists('slugify')) {
    function slugify($str){
        // Substitui caracteres acentuados por versões sem acento
        $str = strtr(utf8_decode($str), utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ"),
                                        "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn");
        // Remove caracteres especiais
        $str = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $str);
        // Remove espaços em branco excessivos e substitui espaços por hífens
        $str = preg_replace('/\s+/', ' ', $str);
        $str = trim($str);
        $str = str_replace(' ', '-', $str);
        // Converte para minúsculas
        $str = strtolower($str);
        return $str;
    }
}



