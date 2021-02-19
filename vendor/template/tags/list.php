<?php

return [
    '#@php(.*?)@endphp#siu' => function($matches) {
        return '<?php '. $matches[1] .' ?>';
    },
    '#@if\((.*?)\)(.*?)@endif#isu' => function($matches) {
        return '<?php if('. $matches[1] . '){ ?>' . $matches[2] . '<?php } ?>';  
    },
    '#@echo\((.*?)\)#isu' => function($matches) {
        return '<?=' . $matches[1] . '?>';
    },
    '#@include\((.*?)\)#isu' => function($matches) {
        return '<?php require_once("' . dirname(__DIR__, 3) . '/views/' . trim($matches[1], '\'"') . '");?>';
    } 
];