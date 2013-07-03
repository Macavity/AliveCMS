
/**
 * start if document is ready
 */

jQuery(document).ready(function() {

    /**
     * @description Collection of all functions that are to be executed after the page has finished loading.
     */
    function initializeAllComponentsOnReady() {
        debug.info("document.ready initializeAllComponentsOnReady");
        var startTime = (new Date()).getTime();
        
        // Frontpage Slider
        jsSlider.initialize("frontpageSlider", "/ajax/frontpage");
        
        // Activate Menu Buttons
        menuItems();
        
        debug.debug("document.ready initializeAllComponentsOnReady Time:"+(((new Date()).getTime())-startTime)+"ms");
    }
    
    /**
     * Highlight the main menu button as the active category for the current page
     */
    function menuItems(){
        
        if($("body").length > 0){
            var bodyId = $("body").attr("id");
            
            if(bodyId.indexOf("-") != -1){
                var split = bodyId.split("-");
                var controller = split[0];
                var action = split[1];
                
                if(controller == "frontpage"){
                    $("#menu .menu-home a").addClass("active");
                }
                else if(controller == "game" ){ 
                    $("#menu .menu-game a").addClass("active");
                } 
                else if(controller == "server"
                    ||  (controller == "community" && action == "changelog")
                    ||  (controller == "community" && action == "clcore")
                    ||  (controller == "community" && action == "clpage")
                    ){ 
                    $("#menu .menu-community a").addClass("active");
                } else if(controller == "account" 
                ||  (controller == "server" && action == "ta")
                    ){ 
                    $("#menu .menu-media a").addClass("active");
                } 
                else if(
                    (controller == "community" && action == "item")
                ||  (controller == "community" && action == "vote")
                    ){ 
                    $("#menu .menu-services a").addClass("active");
                }
            }
            else{
                $("#menu .menu-home a").addClass("active");
            }
        }
        
    
    }
    
    //Initializert alle Elemente wenn Dokument Ready
    initializeAllComponentsOnReady();
    
});