<?php

/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 * @see https://www.microbiologytext.com/
 * @see https://ziku.la
 * @version Generated by ModuleStudio 1.5.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Paustian\RatingsModule\HookProvider;

use Paustian\RatingsModule\Api\RatingsApi;
use Paustian\RatingsModule\HookProvider\Base\AbstractRatingUiHooksProvider;
use Zikula\Bundle\HookBundle\Hook\Hook;
use Zikula\Bundle\HookBundle\Hook\DisplayHookResponse;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;

/**
 * Implementation class for ui hooks provider.
 */
class RatingUiHooksProvider extends AbstractRatingUiHooksProvider
{
    /**
     * @var CurrentUserApiInterface
     */
    protected $currentUserApi;

    /**
     * @var VariableApiInterface
     */
    protected $variableApi;

    protected function renderDisplayHookResponse(Hook $hook, $context): DisplayHookResponse
    {
        list ($assignments, $assignedEntities) = $this->selectAssignedEntities($hook);
        $template = '@PaustianRatingsModule/Hook/displayRatingHook.html.twig';

        $module = $hook->getCaller();
        $moduleItem = $hook->getId();
        $repo = $this->entityFactory->getRepository('rating');
        // get the module variables
        $moduleVars = $this->variableApi->getAll('PaustianRatingsModule');
        RatingsApi::adjustUrlPath($moduleVars, $this->requestStack->getCurrentRequest()->getBasePath());

        // user id of 1 means guest. Anything above 1 is a real user.
        $user = $this->currentUserApi->get('uid');
        // determine the overall rating of this article
        $ratings = $repo->getRatingForItem($module, $moduleItem);
        $count = count($ratings);
        $avgData = RatingsApi::calculateAverage($ratings, $moduleVars['ratingScale']);

        $templateParameters = [
            'modVars' => $moduleVars,
            'avgData' => $avgData,
            'module' => $module,
            'moduleItem' => $moduleItem,
            'user' => $user
        ];

        if ('hookDisplayView' === $context) {
            // add context information to template parameters in order to provide means
            // for adding new assignments and removing existing assignments
            $templateParameters['assignments'] = $assignments;
            $templateParameters['subscriberOwner'] = $hook->getCaller();
            $templateParameters['subscriberAreaId'] = $hook->getAreaId();
            $templateParameters['subscriberObjectId'] = $hook->getId();
            $url = method_exists($hook, 'getUrl') ? $hook->getUrl() : null;
            $templateParameters['subscriberUrl'] = (null !== $url && is_object($url)) ? $url->serialize() : serialize([]);
        }

        $output = $this->templating->render($template, $templateParameters);

        return new DisplayHookResponse($this->getAreaName(), $output);
    }

    /**
     * @required
     */
    public function setAdditionalDependencies(
        CurrentUserApiInterface $currentUserApi,
        VariableApiInterface $variableApi
    ): void {
        $this->currentUserApi = $currentUserApi;
        $this->variableApi = $variableApi;
    }
}
