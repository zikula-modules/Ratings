{*  $Id$  *}
{include file="ratings_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='windowlist.png' set='icons/large' __alt='View all ratings' }</div>
    <h2>{gt text="View all ratings"}</h2>
    <table class="z-admintable">
        <thead>
            <tr>
                <th>{gt text="Module"}</th>
                <th>{gt text="Item ID"}</th>
                <th>{gt text="Number of votes"}</th>
                <th>{gt text="Rating"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$ratings item=rating}
            <tr class="{cycle values="z-odd,z-even"}">
                <td>{$rating.module|safehtml}</td>
                <td>{$rating.itemid|safehtml}</td>
                <td>{$rating.numratings|safehtml}</td>
                <td>{$rating.rating|safehtml}</td>
                <td>
                    {assign var="options" value=$rating.options}
                    {section name=options loop=$options}
                    <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title}</a>
                    {/section}
                </td>
            </tr>
            {foreachelse}
            <tr class="z-admintableempty"><td colspan="6">{gt text="No items found."}</td></tr>
            {/foreach}
        </tbody>
    </table>
    {pager rowcount=$pager.numitems limit=$pager.itemsperpage posvar=startnum shift=1 img_prev=images/icons/extrasmall/previous.png img_next=images/icons/extrasmall/next.png}
</div>
