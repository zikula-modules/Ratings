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

namespace Paustian\RatingsModule\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use Paustian\RatingsModule\Controller\Base\AbstractConfigController;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Paustian\RatingsModule\AppSettings;
use Paustian\RatingsModule\Entity\RatingEntity;
use Paustian\RatingsModule\Helper\PermissionHelper;

/**
 * Config controller implementation class.
 *
 * @Route("/config")
 */
class ConfigController extends AbstractConfigController
{
    /**
     * @Route("/config",
     *        methods = {"GET", "POST"}
     * )
     * @Theme("admin")
     */
    public function config(
        Request $request,
        PermissionHelper $permissionHelper,
        AppSettings $appSettings,
        LoggerInterface $logger,
        CurrentUserApiInterface $currentUserApi
    ): Response {
        $oldRating = $this->getVar('ratingScale');
        $result = parent::config($request, $permissionHelper, $appSettings, $logger, $currentUserApi);
        $newRating = $this->getVar('ratingScale');
        if ($oldRating !== $newRating) {
            $em = $this->getDoctrine()->getManager();
            $ratingObjs = $em->getRepository(RatingEntity::class)->findAll();
            $ratio = $newRating / $oldRating;
            foreach ($ratingObjs as $ratingObj) {
                $newRating = $ratingObj->getRating() * $ratio;
                $ratingObj->setRating($newRating);
            }
            $em->persist($ratingObj);
            $em->flush();
        }

        return $result;
    }
}
