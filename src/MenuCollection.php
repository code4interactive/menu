<?php
namespace Code4\Menu;

use Illuminate\Contracts\Support\Arrayable;

class MenuCollection implements Arrayable, \IteratorAggregate {

    protected $menu;

    public function __construct($menuItems) {
        $this->menu = [];
        $this->loadItems($menuItems);
    }

    /**
     * @param array $items
     */
    public function loadItems($items) {
        foreach ($items as $key => $item) {
            if (array_key_exists('order', $item) && !array_key_exists($item['order'], $this->menu)) {
                $this->menu[$item['order']] = new MenuItem($key, $item);
            } else {
                $this->menu[] = new MenuItem($key, $item);
            }
        }
        ksort($this->menu);
        $this->menu = array_values($this->menu);
    }

    /**
     * Sprawdza czy element istnieje. Wyszukiwanie z wykorzystaniem dot notation
     * @param string $key
     * @return bool
     */
    public function has($key) {
        $segments = explode('.' ,$key);
        $segment = array_shift($segments);
        foreach ($this->menu as $el) {
            if ($el->is($segment)) {
                if (count($segments)) {
                    return $el->has(implode('.', $segments));
                } else {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function get($key) {
        if (!$this->has($key)) {
            throw new \Exception('Element "' . $key . '" not found');
        };
        $segments = explode('.' ,$key);
        $segment = array_shift($segments);

        foreach ($this->menu as $el) {
            if ($el->is($segment)) {
                if (count($segments)) {
                    return $el->get(implode('.', $segments));
                } else {
                    return $el;
                }
            }
        }
    }

    /**
     * Ustawia kolejne elementy menu jako aktywne
     * @param $key
     * @throws \Exception
     */
    public function setActiveByPath($key) {
        if (!$this->has($key)) {
            throw new \Exception('Element "' . $key . '" not found');
        };
        $segments = explode('.' ,$key);
        $segment = array_shift($segments);

        //var_dump($segments);

        foreach ($this->menu as $el) {
            if ($el->is($segment)) {
                $el->setActiveByPath(implode('.', $segments));
                break;
            }
        }
    }

    /**
     * Ustawia kolejne elementy menu sprawdzając url
     * @param $url
     * @return bool
     */
    public function setActiveByUrl($url) {
        foreach ($this->menu as $el) {
            if ($el->setActiveByUrl($url)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Ustala kolejność elementu
     * @param $key
     * @param $order
     * @return MenuCollection $this
     * @throws \Exception
     */
    public function setOrder($key, $order) {
        if (!$this->has($key)) {
            throw new \Exception('Element "' . $key . '" not found');
        };

        $segments = explode('.' ,$key);
        $segment = array_shift($segments);
        if (count($segments)) {
            $this->get($segment)->setOrder(implode('.',$segments), $order);
        } else {

            $index = $this->indexOf($segment);
            $replacement[] = $this->menu[$index];
            array_splice($this->menu, $index, 1);

            if (array_key_exists($order, $this->menu)) {
                $replacement[] = $this->menu[$order-1];
            }

            array_splice($this->menu, $order-1, 1, $replacement);
        }
        return $this;
    }

    /**
     * Znajduje index elementu
     * @param $key
     * @return integer|null
     */
    public function indexOf($key) {
        $lp = 0;
        foreach ($this->menu as $element) {
            if ($element->is($key)) return $lp;
            $lp++;
        }
        return null;
    }

    /**
     * Zlicza elementy kolekcji
     * @return int
     */
    public function count() {
        return count($this->menu);
    }

    /**
     * @return array
     */
    public function toArray() {
        return $this->menu;
    }

    /**
     * Zwraca iterator kolecji
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->menu);
    }

}