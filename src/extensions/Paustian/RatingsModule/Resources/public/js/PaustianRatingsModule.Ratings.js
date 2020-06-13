'use strict';

(function ($) {
    $(document).ready(function () {
        var rateSet = new RatingSetter();
        rateSet.init();
    });

    class RatingSetter {
        constructor() {
            this.ajaxSettings = {
                dataType: 'json',
                error: this.ajaxError,
                timeout: 10000
            };
        }

        init() {
            this.$averageNum = $('#averageNumber');
            this.$ratingButtons = $('[id^=rating_]');
            this.$ratingButtons.on('click', this.setRating.bind(this));
            this.$maxRating = $('#maxSize');
        }

        setRating(evt) {
            var itemName = evt.target.id;
            var rating = itemName.substring(7, itemName.length);
            var module = $('#module').text();
            var moduleItem = $('#moduleItem').text();
            var user = $('#user').text();
            //The user with id of 1 is the guest user. This means you are not logged in.
            if (user === '1') {
                window.alert(Translator.trans('You must be logged in to set a rating.'));
                return;
            }
            this.sendAjax(
                'paustianratingsmodule_ajax_setrating',
                {
                    module: module,
                    moduleItem: moduleItem,
                    rating: rating,
                    user: user
                },
                {
                    success: this.updateRating.bind(this),
                    method: 'POST'
                }
            );
        }

        updateRating(result, textStatus, jqXHR) {
            //The user just added a rating, we need to recalculate the stars
            //check to see if we should uses font awesome icons
            if (result.modVars.iconFa.length > 0) {
                for (var i = 0; i < result.modVars.ratingScale; i++) {
                    $(this.$ratingButtons[i]).removeClass();
                    if (i < result.avgData.avgInt) {
                        $(this.$ratingButtons[i]).addClass('fa ' + result.modVars.iconFa);
                        continue;
                    }
                    if (i === result.avgData.avgInt) {
                        if (result.avgData.doHalfStar) {
                            $(this.$ratingButtons[i]).addClass('fa ' + result.modVars.halfIconFa);
                        } else {
                            $(this.$ratingButtons[i]).addClass('fa ' + result.modVars.emptyIconFa);
                        }
                        continue;
                    }
                    $(this.$ratingButtons[i]).addClass('fa ' + result.modVars.emptyIconFa);
                }
            }
            //if the font awesome variables are empty, we use urls
            for (var i = 0; i < result.modVars.ratingScale; i++){
                $(this.$ratingButtons[i]).removeAttr('src');
                if(i < result.avgData.avgInt){
                    $(this.$ratingButtons[i]).attr('src', result.modVars.iconUrl);
                    continue;
                }
                if(i === result.avgData.avgInt){
                    if(result.avgData.doHalfStar){
                        $(this.$ratingButtons[i]).attr('src', result.modVars.halfIconUrl);
                    } else {
                        $(this.$ratingButtons[i]).attr('src', result.modVars.emptyIconUrl);
                    }
                    continue;
                }
                $(this.$ratingButtons[i]).attr('src', result.modVars.emptyIconUrl);
            }
            $(this.$averageNum).text('(' + Math.round(result.avgData.average *10) / 10 + ' ' + Translator.trans('of') + ' ' + result.modVars.ratingScale + ')');

        }

        sendAjax(url, data, options) {
            //push the data object into the options
            options.data = data;
            $.extend(options, this.ajaxSettings);
            var theRoute = Routing.generate(url);
            $.ajax(theRoute, options);
        }

        ajaxError(jqXHR, textStatus, errorThrown) {
            window.alert(textStatus + "\n" + errorThrown);
        }
    }
})(jQuery);
