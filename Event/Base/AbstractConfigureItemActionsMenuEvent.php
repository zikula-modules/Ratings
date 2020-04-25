<?php
/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 * @link https://www.microbiologytext.com/
 * @link https://ziku.la
 * @version Generated by ModuleStudio 1.3.2 (https://modulestudio.de).
 */

namespace Paustian\RatingsModule\Event\Base;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event base class for extending item actions menu.
 */
class AbstractConfigureItemActionsMenuEvent extends Event
{
    /**
     * @var FactoryInterface.
     */
    protected $factory;

    /**
     * @var ItemInterface
     */
    protected $menu;

    /**
     * @var array
     */
    protected $options;

    /**
     * ConfigureItemActionsMenuEvent constructor.
     *
     * @param FactoryInterface $factory
     * @param ItemInterface    $menu
     * @param array            $options
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu, array $options = [])
    {
        $this->factory = $factory;
        $this->menu = $menu;
        $this->options = $options;
    }

    /**
     * Returns the factory.
     *
     * @return FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Returns the menu.
     *
     * @return ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Returns the options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
