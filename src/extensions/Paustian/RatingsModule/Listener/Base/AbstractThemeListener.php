<?php

/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 *
 * @see https://www.microbiologytext.com/
 * @see https://ziku.la
 *
 * @version Generated by ModuleStudio 1.5.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Paustian\RatingsModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Zikula\ThemeModule\Bridge\Event\TwigPostRenderEvent;
use Zikula\ThemeModule\Bridge\Event\TwigPreRenderEvent;
use Zikula\ThemeModule\Engine\AssetFilter;

/**
 * Event handler base class for theme-related events.
 */
abstract class AbstractThemeListener implements EventSubscriberInterface
{
    /**
     * @var AssetFilter
     */
    protected $assetFilter;
    
    public function __construct(
        AssetFilter $assetFilter
    ) {
        $this->assetFilter = $assetFilter;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            TwigPreRenderEvent::class => ['preRender', 5],
            TwigPostRenderEvent::class => ['postRender', 5],
            KernelEvents::RESPONSE => ['injectDefaultAssetsIntoRawPage', 1020], // after DefaultPageAssetSetterListener
        ];
    }
    
    /**
     * Listener for the `TwigPreRenderEvent`.
     *
     * Occurs immediately before twig theme engine renders a template.
     */
    public function preRender(TwigPreRenderEvent $event): void
    {
    }
    
    /**
     * Listener for the `TwigPostRenderEvent`.
     *
     * Occurs immediately after twig theme engine renders a template.
     */
    public function postRender(TwigPostRenderEvent $event): void
    {
    }
    
    /**
     * Adds assets to a raw page which is not processed by the Theme engine.
     */
    public function injectDefaultAssetsIntoRawPage(ResponseEvent $event): void
    {
        $request = $event->getRequest();
    
        $raw = null !== $request ? $request->query->getBoolean('raw') : false;
        if (true !== $raw) {
            return;
        }
    
        $routeName = $request->get('_route', '');
        if (false === mb_strpos($routeName, 'paustianratingsmodule')) {
            return;
        }
    
        $response = $event->getResponse();
        $output = $response->getContent();
        $output = $this->assetFilter->filter($output);
        $response->setContent($output);
        $event->setResponse($response);
    }
}
