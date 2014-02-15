/**
 * Zikula Application Framework
 *
 * @copyright (c) 2014, Zikula Development Team
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
*/

(function($) {
    /**
     * Determines whether or not an ajax request is currently in progress.
     *
     * @type {boolean}
     */
    var waitForAjax = false;

    /**
     * Record a users rating for an item.
     *
     * @return void
     */
    window.ratingsratefromslider = function(modname, objectid, areaid, rating) {
        var pars;
        pars = 'module=Ratings&type=ajax&func=rate&modname=' + modname + '&objectid=' + objectid + '&areaid=' + areaid + '&rating=' + rating;
        handleRate(pars);
    };

    /**
     * Record a users rating for an item.
     *
     * @return void
     */
    window.ratingsratefromform = function() {
        var pars;
        pars = 'module=Ratings1&type=ajax&func=rate&' + $('#ratingrateform').serialize();
        handleRate(pars);
    };

    /**
     * Handle a new rating by creating the ajax request.
     *
     * @param pars The parameters to pass via POST.
     */
    function handleRate(pars) {
        if (waitForAjax) {
            // Rating is already in progress.
            return;
        }
        waitForAjax = true;
        $('#ratingmessage').hide().removeClass('hidden').fadeIn();

        $.ajax({
            type: "POST",
            url: Zikula.Config.baseURL + 'ajax.php',
            data: pars
        }).always(handleResponse);
    }

    /**
     * Ajax response function for the rating: show the result.
     *
     * @return void
     */
    function handleResponse(response) {
        $('#ratingmessage').fadeOut(400, function() {
            waitForAjax = false;
            if (response.data == null) {
                alert(ratingsAjaxErrorMessage);
                return;
            }

            $('#ratingsratecontent').html(response.data.result);
        });
    }
})(jQuery);