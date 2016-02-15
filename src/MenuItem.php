<?php
/**
 * User: arturbartczak
 * Date: 30.09.15
 * Time: 13:23
 */

namespace Code4\Menu;

use Code4\View\Attributes;

class MenuItem {

    private $key;

    private $url;

    private $icon;

    private $title;

    private $collection;

    private $attributes;

    private $collection_attributes;

    private $active = false;

    public function __construct($key, $item) {
        $this->key = $key;

        array_key_exists('url', $item) ? $this->setUrl($item['url']) : null;
        $this->icon = array_key_exists('icon', $item) ? $item['icon'] : '';
        $this->attributes = array_key_exists('attributes', $item) ? new Attributes($item['attributes']) : new Attributes();
        $this->collection_attributes = array_key_exists('collection_attributes', $item) ? new Attributes($item['collection_attributes']) : new Attributes();
        $this->title = array_key_exists('title', $item) ? $item['title'] : ucfirst($key);

        if (array_key_exists('collection', $item) && is_array($item['collection'])) {
            $this->collection = new MenuCollection($item['collection']);
        }
    }

    /**
     * Sprawdza czy MenuItem ma potomków
     * @return bool
     */
    public function hasChildren(){
        return is_object($this->collection) ? $this->collection->count() > 0 ? true : false : false;
    }

    /**
     * Sets active path by dot notated path
     * @param $key
     * @return bool|void
     * @throws \Exception
     */
    public function setActiveByPath($key) {
        if ($key && $this->hasChildren()) {
            $this->setActiveCollection();
            $this->collection->setActiveByPath($key);
        } else {
            $this->setActiveItem();
        }
        return null;
    }

    /**
     * Sets active path by url
     * @param $url
     * @return bool
     */
    public function setActiveByUrl($url) {
        if ($this->url == '/' . ltrim($url, '/')) {
            $this->setActiveItem();
            return true;
        }

        if ($this->hasChildren()) {
            if ($this->collection->setActiveByUrl($url)) {
                $this->setActiveCollection();
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Sets collection active
     * @param bool|true $active
     */
    public function setActiveCollection($active = true) {
        if ($active) {
            $this->collection_attributes->add('class', 'active');
            $this->collection_attributes->add('class', 'asa');
        } else {
            $this->collection_attributes->remove('class', 'active');
        }
    }

    /**
     * Sets item active
     * @param bool|true $active
     */
    public function setActiveItem($active = true) {
        if ($active) {
            $this->attributes->add('class', 'active');
            $this->collection_attributes->add('class', 'active');
            $this->active = true;
        } else {
            $this->attributes->remove('class', 'active');
            $this->collection_attributes->remove('class', 'active');
            $this->active = false;
        }
    }

    /**
     * Checks is item active
     * @return bool
     */
    public function isItemActive() {
        return $this->active;
    }

    /**
     * Checks is collection active
     * @return bool
     */
    public function isCollectionActive() {
        return $this->collection_attributes->findInKey('class', 'active');
    }

    /**
     * @param string $key
     * @return bool
     */
    public function is($key) {
        return $key==$this->key;
    }

    /**
     * Przekazuje poszukiwanie do kolekcji
     * @param $key
     * @return bool
     */
    public function has($key) {
        if (!$key || !$this->hasChildren()) { return false; }
        return $this->collection->has($key);
    }

    /**
     * Zwraca potomki
     * @return array|MenuCollection
     */
    public function getChildren() {
        if ($this->hasChildren()) { return $this->collection; }
        return [];
    }

    /**
     * Zamienia szukaną frazę w URL. Pozwala na dynamiczne podstawianie np. ID usera w link menu.
     * @param $term
     * @param $value
     * @param bool $recursive
     * @return bool
     */
    public function replaceTermInUrl($term, $value, $recursive = false) {
        $this->url = str_replace($term, $value, $this->url);
        if ($this->hasChildren() && $recursive) {
            $this->collection->replaceTermInUrl($term, $value, $recursive);
        }
        return false;
    }

    /**
     * @return Attributes
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * @return Attributes
     */
    public function cAttributes()
    {
        return $this->collection_attributes;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $key
     * @return MenuItem $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return MenuItem $this
     */
    public function setUrl($url)
    {
        $this->url = '/' . ltrim($url, '/');
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Zwraca formatowany string ikony FontAwesome
     * @return string
     */
    public function renderIcon() {
        if ($this->icon) {
            return '<i class="fa fa-' . $this->icon . '"></i>';
        }
        return '';
    }

    /**
     * @param string $icon
     * @return MenuItem $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return MenuItem $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Renderuje element
     * @return \Illuminate\View\View
     */
    public function render() {
        return view('menu::item', array('item' => $this))->render();
    }

    public function __toString() {
        return (string) $this->render();
    }

    public function __call($name, $arg) {
        return call_user_func_array(array($this->collection, $name), $arg);
    }
}