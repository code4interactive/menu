<?php
namespace Code4\Menu\Test;

use \Code4\Menu\MenuCollection;

class MenuCollectionTest extends TestCase
{
    /**
     * Test that true does in fact equal true
     */
    public function testHas()
    {
        $menuCollection = new MenuCollection($this->exampleMenu);

        $this->assertEquals(true,   $menuCollection->has('settings'), 'First level existing');
        $this->assertEquals(false,  $menuCollection->has('notExisting'), 'First level not existing');
        $this->assertEquals(true,   $menuCollection->has('settings.ogolne'), 'Second level existing');
        $this->assertEquals(false,  $menuCollection->has('settings.notExisting'), 'Second level not existing');
    }

    /**
     * Testujemy czy kolejność przekazania elementów menu w konfiguracji bez wskazywania kolejności w "order"
     * jest zachowana
     */
    public function testDefaultOrder() {
        $menuCollection = new MenuCollection($this->exampleNoOrder);
        $lp = 1;
        foreach($menuCollection->toArray() as $el) {
            $this->assertEquals('Pos '.$lp, $el->getTitle(), 'Menu item "'.$el->getKey().' order: '.$lp.'');
            $lp++;
        }
    }

    public function testOrder()
    {
        $menuCollection = new MenuCollection($this->exampleOrder);
        //Test list order
        $lp = 1;
        foreach($menuCollection->toArray() as $el) {
            $this->assertEquals('pos'.$lp, $el->getKey(), 'Menu item "'.$el->getKey().' order: '.$lp.'');
            $lp++;
        }

        //Test setting the order
        $menuCollection->setOrder('pos1', 2);
        $this->assertEquals('pos1', $menuCollection->toArray()[1]->getKey());
    }

    public function testActiveByPath()
    {
        $menuCollection = new MenuCollection($this->exampleMenu);
        $menuCollection->setActiveByPath('settings.ogolne');

        $this->assertEquals(true, $menuCollection->get('settings.ogolne')->isItemActive());
    }

    public function testActiveByUrl()
    {
        $menuCollection = new MenuCollection($this->exampleMenu);
        $menuCollection->setActiveByUrl('settings/general');

        $this->assertEquals(true, $menuCollection->get('settings.ogolne')->isItemActive());
    }

    public function testReplaceTermInUrl() {
        $menuCollection = new MenuCollection($this->exampleMenu);
        $menuCollection->replaceTermInUrl('{user_id}', '12');

        $this->assertEquals('/administration/user/{user_id}', $menuCollection->get('settings.user')->getUrl());

        $menuCollection->replaceTermInUrl('{user_id}', '12', true);
        $this->assertEquals('/administration/user/12', $menuCollection->get('settings.user')->getUrl());
    }
}
