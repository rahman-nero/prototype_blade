<?php

/*
 * Вот такой путь - blog.admin.index 
 * Превращает - blog/admin/index   
 */
function path_replace(string $path) {
    $path = str_replace('.', '/', $path);
    return $path;
}