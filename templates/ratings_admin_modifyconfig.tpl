{*  $Id$  *}
{gt text="Settings" assign=templatetitle}
{include file="ratings_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='configure.png' set='icons/large' alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <form class="z-form" action="{modurl modname="Ratings" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <fieldset>
                <legend>{$templatetitle}</legend>
                <div class="z-formrow">
                    <label for="ratings_itemsperpage">{gt text="Items per page"}</label>
                    <input id="ratings_itemsperpage" type="text" name="itemsperpage" size="3" value="{$itemsperpage|safetext}" />
                </div>
                <div class="z-formrow">
                    <label for="ratings_defaultstyle">{gt text="Default ratings style"}</label>
                    <select id="ratings_defaultstyle" name="defaultstyle">
                        {html_options options=$defaultstylevalues selected=$defaultstyle}
                    </select>
                </div>
                <div class="z-formrow">
                    <label for="ratings_useajax">{gt text="Use ajax for saving rate"}</label>
                    <input id="ratings_useajax" type="checkbox" name="useajax" value="1"{if $useajax} checked="checked"{/if} />
                    <em class="z-formnote z-sub">{gt text='If disabled the standard redirection will be used.'}</em>
                </div>
                <div class="z-formrow">
                    <label for="ratings_usefancycontrols">{gt text="Display stars dynamically using ajax"}</label>
                    <input id="ratings_usefancycontrols" type="checkbox" name="usefancycontrols" value="1"{if $usefancycontrols} checked="checked"{/if} />
                    <em class="z-formnote z-sub">{gt text='Only works if stars is set as default ratings style.'}</em>
                </div>
                <div class="z-formrow">
                    <label for="ratings_displayScoreInfo">{gt text="Display an informative message about the score"}</label>
                    <input id="ratings_displayScoreInfo" type="checkbox" name="displayScoreInfo" value="1"{if $displayScoreInfo} checked="checked"{/if} />
                    <em class="z-formnote z-sub">{gt text='It tells to the user which is the lowest/higher value.'}</em>
                </div>
                <div class="z-formrow">
                    <label for="ratings_seclevel">{gt text="Security against rate rigging"}</label>
                    <select id="ratings_seclevel" name="seclevel">
                        {html_options options=$securitylevelvalues selected=$seclevel}
                    </select>
                </div>
            </fieldset>
            {*}
            {notifydisplayhooks hookobject=module hookaction=modifyconfig module=Ratings}
            {*}
            <div class="z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt="Update Configuration" __title="Update Configuration"}
                <a href="{modurl modname=Ratings type=admin func=main}">{img modname='core' src='button_cancel.png' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            </div>
        </div>
    </form>
</div>
