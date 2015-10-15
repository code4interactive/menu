<?php

namespace Code4\Menu;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractMenu implements MenuInterface {

    private $menu;
    private $name;
    protected $checkRoles;
    protected $checkPermissions;
    protected $showInMenuPermission;
    protected $filesystem;
    protected $request;

    public function __construct($menuName, Filesystem $filesystem, Request $request)
    {
        $this->name = $menuName;
        $this->filesystem = $filesystem;
        $this->request = $request;
        //$this->init($this->loadConfig());
    }

    /**
     * Builds menu from passed data
     * @param array $menuItems
     */
    public function build($menuItems) {
        $this->menu = new MenuCollection($menuItems);
        //Set active menus
        $this->detectActive();
    }

    //Renderuje menu z wykorzystaniem widoków
    public function render($viewName = 'menu::collection') {
        echo view($viewName, ['items'=>$this->menu]);
    }

    /**
     * Detects
     */
    public function detectActive() {
        $this->menu->setActiveByUrl($this->request->decodedPath());
    }


    /**
     * Funkcja pomocznicza do ładowania menu
     * @param $yamlFilePath
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function loadMenuFromYamlFile($yamlFilePath) {
        $file = $this->filesystem->get($yamlFilePath);
        return Yaml::parse($file);
    }

    /**
     * Zwraca nazwę kontenera menu
     * @return string
     */
    public function getMenuName() {
        return $this->menu;
    }


    public function __call($name, $arg) {
        return call_user_func_array(array($this->menu, $name), $arg);
    }

}