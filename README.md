# PaustianRatingsModule 3.0.0

A  hook provider module that will attach ratings to any hookable subscriber module that implements the uiHookSubscribers. 

This module is intended for being used with Zikula 2.0.12 and later.

For feature requests or bugs submit an issue at GitHub (https://github.com/zikula-modules/Ratings.

For questions, please contact me on zikula.slack.com

Timothy Paustian (tdpaustian@gmail.com)
<https://www.microbiologytext.com/>

##Setting up the module
###Designating rating icons
Install the module as per any Zikula Module. Once installed go to the admin page for the module by clicking on the link 
icon next to the module name. Once there it is important to set up the icons that will be used for showing ratings. Either
all the font awesome (fa) text boxes or all the icon url boxes must be filled out. 

For the font awesome text boxes any font awesome class text available in Zikula can be used as an icon. For example fa-star, 
fa-star-half, and fa-star-o. It is also possible to designate any images you want to create as rating icons. You will need
to create three images. It is best if the three images are the same size and are not larger than about 20 x 20 pixels. In the 
three icon url text boxes designate the path to the icons, starting from the root of your Server. For example, 

`/name-of-zikula-site/web/uploads/rating/staricon.png`

`/name-of-zikula-site/web/uploads/rating/halfstaricon.png`

`/name-of-zikula-site/web/uploads/rating/emptystaricon.png`

###Hooking to other modules
Modules that want to use the RatingsModule must have the uiHookSubscriber interface implemented. You can hook the RatingsModule
to subscribers by clicking the Hooks tab in either the subscriber module or the RatingsModule. If done in the subscriber module
drag the Rating ui hooks provider into the ui_hooks area. If hooking up in the RatingsModule check the check box next to 
the subscriber module you want to hook to the RatingsModule.

That's it. You should now see a Ratings line at the bottom of your module. To add a rating, just click on the icons and
the rating for your user will be added.

