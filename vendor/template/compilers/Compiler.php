<?php

namespace Template\compilers;

use Template\interfaces\CompilerInterface;


class Compiler implements CompilerInterface
{
    /**
     * @var array здесь будут храниться все правила - т.е тэг 
    */ 
    protected array $rules = [];

    /**
     * Путь к виду
     * @var string $view
     */
    public string $view;

    /** 
     * Переменные которые будут переданы в вид
     * @var string $vars  
     */
    public array $vars = [];

    /**
     * Путь где хранятся шаблоны
     */
    public string $path_to_extends;

    /**
     * Родительский шаблон которые взяли из @extends('___');
     * @var string $view_extends
     */
    public string $view_extends;

    public function __construct()
    {
        $this->rules = require_once dirname(__DIR__) .'/tags/list.php';
    }


    /**
     * Здесь будет происходить вся обработка шаблонов
     *
     * @param string $view - Вид который будем обрабатывать
     * @param array $vars - переменные которые будут переданы в вид
     * @return string - вернем имя уже оттрансилированного файла, а потом просто подключим
     */
    public function compiling(string $view, array $vars = [])
    {
        # Присваиваем переменные
        $this->setVars($vars);

        # добавляем к пути вида - расширение .php
        $this->view = $view.'.php';

        # Проверяем тут, есть ли у $this->view, т.е у вида, наследуемый шаблон
        #  @return boolean
        if ($this->getExtendsNameFromView()) {
            # Если вид наследует шаблон
            # Имя шаблона будет храниться в $this->view_extends
            $end_file = $this->getCompliedFileWithExtends();
        } else {
            $end_file = $this->getCompliedFileWithoutExtends();
        }
        return $end_file;
    }


    protected function getCompliedFileWithExtends()  {
        $extends  = $this->path_to_extends . $this->view_extends . '.php';

    }

    protected function getCompliedFileWithoutExtends() : string{
        $view = file_get_contents($this->view);
        $result = preg_replace_callback_array($this->rules, $view);
        $file = dirname(__DIR__) . '/cache/' . md5($this->view) . '.php';
        file_put_contents($file, $result);
        return $file;
    }

   
    /**
     * Получение имени родительского шаблона из тега - @extends()
     *
     * @return bool
     */
    protected function getExtendsNameFromView(): bool
    {
        $file = file_get_contents($this->view, FALSE, NULL, 0, 100);
        if (preg_match('#@extends\((.*)\)#iu', $file, $matches)) {
            # убераем кавычки, и переводим вот такой синтаксис blog.main -> blog/main
            $extends = trim(\path_replace($matches[1]), "\"'");
            $this->view_extends = $extends;
            return true;
        }
        return false;
    }

    /**
     * Устанавливаем путь от куда будут браться шаблоны
     *
     * @param string $path_to_extends
     * @return void
    */ 
    public function setPathExtends( string $path_to_extends) {
        $this->path_to_extends = $path_to_extends;
    }

    /**
     * Получение имя родительского шаблона из переменной
     *
     * @return void
    */ 
    public function getParent() : ?string
    {
        return $this->view_extends;
    }

    public function getPathExtends(): ?string 
    {
        return $this->path_to_extends;
    }


    /**
     * Установка переменных которые будут переданы в вид
     *
     * @param array|null $array
     * @return void
     */ 
    public function setVars(?array $array): void
    {
        if (!empty($vars))
            $this->vars[] = $array;
        else
            $this->vars = $array;
    }

    /**
     * Получение всех переменных которые будут переданы в вид
     *
     * @return array|null
     */ 
    public function getVars(): ?array
    {
        return $this->vars;
    }
}
