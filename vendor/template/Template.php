<?php

namespace Template;

use Template\interfaces\CompilerInterface;

class Template
{
    /** 
     * Т.е если мы передали вид, то он будет искаться тут, по этому пути 
     * @var string Здесь храниться путь к видам
    */
    protected $path_to_views = null;

    /**
     * @var object Переменная в котором храниться объект класса компиляции
     */
    protected $compiler;

    /** 
     * 
     *
     *
     * */ 
    public function __construct( ?CompilerInterface $compiler = null)
    {
        # Транслятор, который будет обрабатывать содержимое
        if( $compiler == null){
            $this->compiler = new compilers\Compiler();
        } else {
            $this->compiler = new $compiler;
        }

        # Установка пути, от куда будут браться виды
        $this->setViewsPath();
    }



    public function view(string $view, array $array = []) {
        $view = $this->path_to_views . ltrim($view, '/');
        $filename = $this->compiler->compiling($view, $array);
        
        extract($array);
        require_once $filename;
    }



    /**
     * Новый путь для видов
     * 
     * @param string $path = null
     */
    public function setViewsPath(?string $path = null) {
        if ($path == null) {
            $new_path = dirname(__DIR__, 2) . '/views/';
            $this->path_to_views = $new_path;
        } else {
            $new_path = dirname(__DIR__, 2) . ('/' . trim($path, '/') . '/');
            $this->path_to_views = $new_path;
        }

        $this->compiler->setPathExtends($new_path);
        return $this->compiler->getPathExtends();
    }



    /**
     * 
    */
    public function getPath(): string {
        return $this->path_to_views;
    }

    public function getParentTemplate() : mixed {
        return $this->compiler->getParent();
    }

    public function setVars(?array $array) : void {
        $this->compiler->setVars($array);
    }

    public function getVars() : ?array {
        return $this->compiler->getVars();
    }
}