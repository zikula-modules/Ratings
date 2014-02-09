/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.com
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Ratings
*/

/**
 * Record a users rating for an item.
 *
 * @return void
 * @author Mark West
 */
function ratingsratefromslider(modname, objectid, areaid, rating) {
    var pars, request;

    $('ratingmessage').update(recordingvote);
    pars = 'module=Ratings&type=ajax&func=rate&modname=' + modname + '&objectid=' + objectid + '&areaid=' + areaid + '&rating=' + rating;
    request = new Ajax.Request(
        Zikula.Config.baseURL + 'ajax.php',
        {
            method: 'post', 
            parameters: pars, 
            onComplete: ratingsrate_response
        }
    ); 
}


/**
 * Record a users rating for an item.
 *
 * @return void
 * @author Mark West
 */
function ratingsratefromform() {
    var pars, request;

    $('ratingmessage').update(recordingvote);
    pars = 'module=Ratings&type=ajax&func=rate&' + Form.serialize('ratingrateform');
    request = new Ajax.Request(
        Zikula.Config.baseURL + 'ajax.php',
        {
            method: 'post', 
            parameters: pars, 
            onComplete: ratingsrate_response
        }
    ); 
}

/**
 * Ajax response function for the rating: show the result.
 *
 * @return void
 * @author Mark West
 */
function ratingsrate_response(req) {
    var json;

    $('ratingmessage').update('');
    if (req.status != 200) { 
        alert(req.responseText);
        return;
    }

    json = req.getData();
    $('ratingsratecontent').update(json.result);
}
