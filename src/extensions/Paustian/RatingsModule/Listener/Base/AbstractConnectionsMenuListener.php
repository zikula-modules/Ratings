<?php

/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 * @see https://www.microbiologytext.com/
 * @see https://ziku.la
 * @version Generated by ModuleStudio 1.4.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Paustian\RatingsModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\ExtensionsModule\Event\ConnectionsMenuEvent;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;

/**
 * Event handler base class for adding connections to extension menus.
 */
abstract class AbstractConnectionsMenuListener implements EventSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;
    
    /**
     * @var PermissionApiInterface
     */
    protected $permissionApi;
    
    public function __construct(
        TranslatorInterface $translator,
        PermissionApiInterface $permissionApi
    ) {
        $this->translator = $translator;
        $this->permissionApi = $permissionApi;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            ConnectionsMenuEvent::class => ['addMenuItem', 5]
        ];
    }
    
    /**
     * Listener for the `ConnectionsMenuEvent`.
     *
     * Occurs when building admin menu items.
     * Listener can be used provide menu items to other extensions.
     * Adds sublinks to a 'Connections' menu that is appended to all extensions if populated.
     *
     * You can add data like this:
     *
     *     `if (!$this->permissionApi->hasPermission($event->getExtensionName() . '::', '::', ACCESS_ADMIN)) {
     *          return;
     *      }
     *
     *      if ('ZikulaUsersModule' === $event->getExtensionName()) {
     *          // only add to menu for the Users module
     *          $event->addChild($this->translator->trans('Paustian ratings'), [
     *              'route' => 'paustianratingsmodule_user_index',
     *              'routeParameters' => ['moduleName' => $event->getExtensionName()]
     *          ]);
     *      }`
     */
    public function addMenuItem(ConnectionsMenuEvent $event): void
    {
    }
}
