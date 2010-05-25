/**
 * Ratings
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link      http://code.zikula.org/ratings/
 * @version   $Id$
 * @license   GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */


/**
 * Record a users rating for an item
 *
 * @params none;
 * @return none;
 * @author Mark West
 */
function ratingsratefromslider(modname, objectid, rating)
{
    Element.update('ratingmessage-' + modname + '-' + objectid, recordingvote);
    var pars = "module=Ratings&func=rate&modname=" + modname + "&objectid=" + objectid + "&rating=" + rating;
    var myAjax = new Ajax.Request(
        document.location.pnbaseURL + 'ajax.php',
        {
            method: 'post', 
            parameters: pars, 
            onComplete: ratingsrate_response
        }); 
}

/**
 * Record a users rating for an item
 *
 * @params none;
 * @return none;
 * @author Mark West
 */
function ratingsratefromform(modname, objectid)
{
    Element.update('ratingmessage-' + modname + '-' + objectid, recordingvote);
    var pars = "module=Ratings&func=rate&"
               + Form.serialize('ratingrateform-' + modname + '-' + objectid);
    var myAjax = new Ajax.Request(
        document.location.pnbaseURL + 'ajax.php',
        {
            method: 'post', 
            parameters: pars, 
            onComplete: ratingsrate_response
        }); 
}

/**
 * Ajax response function for the rating: show the result
 *
 * @params none;
 * @return none;
 * @author Mark West
 */
function ratingsrate_response(req)
{
    if(req.status != 200 ) { 
        pnshowajaxerror(req.responseText);
        return;
    }

    var json = pndejsonize(req.responseText);
    if (json.objectid == '' || json.modname == '-1') {
        pnshowajaxerror("Oops something went wrong! " + json.alerttext + "response: " + json.response);
    } else {
        Element.update('ratingsratecontent-' + json.modname + '-' + json.objectid, json.result);
    }
}
