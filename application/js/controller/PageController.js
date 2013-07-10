
define(['./BaseController'], function (BaseController) {

    function pageController(id){
        debug.debug("new PageController");
        BaseController.call(this, id);
        return (this);
    }

    pageController.prototype = new BaseController("Page");

    // Define the class methods.
    pageController.init = function(){
        debug.debug("PageController.initialize");

        debug.debug("PageController.initialize END");
    };

    // ToDo: Check: Why is the Base-Function not inherited?
    pageController.getTemplate = function(templateName){
        return Handlebars.templates[templateName];
    };

    return (pageController);
});
