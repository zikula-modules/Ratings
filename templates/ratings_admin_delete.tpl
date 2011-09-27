{*  $Id$  *}
{include file="ratings_admin_menu.tpl"}
{gt text="Delete item's rating" assign=templatetitle}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='editdelete.gif' set='icons/large' alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <p class="z-warningmsg">{gt text="Do you really want to delete the rating of this item?"}</p>
    <form class="z-form" action="{modurl modname="Ratings" type="admin" func="delete"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" name="confirmation" value="1" />
            <input type="hidden" name="rid" value="{$rid|safetext}" />
            <fieldset>
                <legend>{gt text="Confirmation prompt"}</legend>
                <div class="z-formbuttons">
                    {button src='button_ok.gif' set='icons/small' __alt="Confirm deletion?" __title="Confirm deletion?"}
                    <a href="{modurl modname=Ratings type=admin func=view}">{img modname='core' src='button_cancel.gif' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
                </div>
            </fieldset>
        </div>
    </form>
</div>
