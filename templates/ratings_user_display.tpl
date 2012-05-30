{*  $Id$  *}
<div id="ratingsratecontent">
    {if $useajax}
    {ajaxheader modname=Ratings filename=ratings.js}
    {/if}
    {if $usefancycontrols eq true and ($style eq 'outoffivestars' or $style eq 'outoftenstars')}
    {pageaddvar name=stylesheet value="modules/Ratings/style/star_rating.css"}
    {/if}

    <h3>{gt text="Rating"}</h3>

    {if $showrating eq false and $permission eq true}
    <p>{gt text="No one has rated this item yet - be the first!"}</p>
    {/if}
    {if $showrating or ($usefancycontrols eq true and ($style eq 'outoffivestars' or $style eq 'outoftenstars'))}

    {if $style eq 'percentage'}
    {$rating|safetext}%
    {elseif $style eq 'outoffive'}
    &nbsp;&nbsp;{$rating|safetext} / 5
    {elseif $style eq 'outoften'}
    &nbsp;&nbsp;{$rating|safetext} / 10
    {elseif $style eq 'outoffivestars'}
    {if $usefancycontrols}
    <ul class="star-rating star-rating-fivestars">
        <li class="current-rating" style="width:{$rawrating|string_format:"%.0f"|default:0}%;">Currently {$rating} / 5 Stars.</li>
        {if $showratingform eq 1 and $permission eq true}
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',20);" title="1 star out of 5" class="one-star">1</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',40);" title="2 stars out of 5" class="two-stars">2</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',60);" title="3 stars out of 5" class="three-stars">3</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',80);" title="4 stars out of 5" class="four-stars">4</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',100);" title="5 stars out of 5" class="five-stars">5</a></li>
        {/if}
    </ul>
    {else}
    {section name=fullstars loop=$rating}
    {img modname='Ratings' src="star.gif"}
    {/section}
    {if $fracrating gte 5}
    {img modname='Ratings' src="halfstar.gif"}
    {/if}
    {section name=emptyStar loop=$emptyStars}
    {img modname='Ratings' src="emptystar.gif"}
    {/section}
    {/if}
    {elseif $style eq 'outoftenstars'}
    {if $usefancycontrols}
    <ul class="star-rating star-rating-tenstars">
        <li class="current-rating" style="width:{$rawrating|string_format:"%.0f"|default:0}%;">Currently {$rating} / 10 Stars.</li>
        {if $showratingform eq 1 and $permission eq true}
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',10);" title="1 star out of 10" class="one-star">1</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',20);" title="2 star out of 10" class="two-stars">2</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',30);" title="3 star out of 10" class="three-stars">3</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',40);" title="4 stars out of 10" class="four-stars">4</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',50);" title="5 star out of 10" class="five-stars">5</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',60);" title="6 stars out of 10" class="six-stars">6</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',70);" title="7 star out of 10" class="seven-stars">7</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',80);" title="8 stars out of 10" class="eight-stars">8</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',90);" title="9 star out of 10" class="nine-stars">9</a></li>
        <li><a href="javascript:ratingsratefromslider('{$modname}','{$objectid}','{$areaid}',100);" title="10 stars out of 10" class="ten-stars">10</a></li>
        {/if}
    </ul>
    {else}
    {section name=fullstars loop=$rating}
    {img modname='Ratings' src="star.gif"}
    {/section}
    {if $fracrating gte 5}
    {img modname='Ratings' src="halfstar.gif"}
    {/if}
    {section name=emptyStar loop=$emptyStars}
    {img modname='Ratings' src="emptystar.gif"}
    {/section}
    {/if}
    {/if}
    {/if}


    {if $showratingform eq 1 and $permission eq true}
    <form id="ratingrateform" action="{modurl modname=Ratings type=user func=rate}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" name="returnurl" value="{$returnurl|safetext}" />
            <input type="hidden" name="modname" value="{$modname|safetext}" />
            <input type="hidden" name="objectid" value="{$objectid|safetext}" />
            <input type="hidden" name="areaid" value="{$areaid|safetext}" />
            <input type="hidden" name="ratingtype" value="{$ratingtype|safetext}" />
            {if $usefancycontrols neq true or ($style eq 'percentage' or $style eq 'outoffive' or $style eq 'outoften')}
            <label for="rating">{gt text="Rate this item"}</label>
            {if $style eq 'percentage'}
            <input name="rating" type="text" size="3" maxlength="3" id="rating" />%
            {elseif $style eq 'outoffive' or $style eq 'outoffivestars'}
            <select name="rating" id="rating">
                <option value="20">1</option>
                <option value="40">2</option>
                <option value="60" selected="selected">3</option>
                <option value="80">4</option>
                <option value="100">5</option>
            </select>
            {elseif $style eq 'outoften' or $style eq 'outoftenstars'}
            <select name="rating" id="rating">
                <option value="10">1</option>
                <option value="20">2</option>
                <option value="30">3</option>
                <option value="40">4</option>
                <option value="50" selected="selected">5</option>
                <option value="60">6</option>
                <option value="70">7</option>
                <option value="80">8</option>
                <option value="90">9</option>
                <option value="100">10</option>
            </select>
            {/if}
            {if $useajax}
            <input id="ajaxrating" style="display: none;" onclick="javascript:ratingsratefromform();" name="submit" type="button" value="{gt text="Submit"}" />
            <noscript>
            {/if}
                <input name="submit" type="submit" value="{gt text="Submit"}" />
            {if $useajax}
            </noscript>
            {/if}
            {/if}

            {if $displayScoreInfo}
            <p style="font-style: italic;">{gt text='%1$s%3$s is the lowest and %2$s%3$s the higher score.' tag1="1" tag2=$maxScore tag3=$typeScore}</p>
            {/if}
        </div>
    </form>
    {if $useajax}
    <div id="ratingmessage">&nbsp;</div>
    <script type="text/javascript">
        var recordingvote  = "{{gt text='Recording rating'}}";
        {{if $usefancycontrols neq true or ($style eq 'percentage' or $style eq 'outoffive' or $style eq 'outoften')}}
        $('ajaxrating').show();
        {{/if}}
    </script>
    {/if}
    {/if}
</div>
