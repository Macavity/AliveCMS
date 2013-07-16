
var Bugtracker = {


    /**
     * Links for the ajax requests
     */
    links: {
        remove: "bugtracker/admin_projects/delete/",
        create: "bugtracker/admin_projects/create/",
        save: "bugtracker/admin_projects/save/",
        move: "bugtracker/admin_projects/move/",
        main: "bugtracker/admin_projects/"
    },

    showContent: function(content){
        switch(content){
            case "main":
                $("#add_project").fadeOut(150, function(){
                    $("#main_bugtracker").fadeIn(150);
                });
                break;
            case "create_project":
                $("#main_bugtracker").fadeOut(150, function(){
                    $("#add_project").fadeIn(150);
                });
                break;
        }
    },

    /**
     * Toggle between the "add" form and the list
     */
    addProject: function()
    {
        var id = this.identifier;

        /*
         * Toggle Add Project Form and the Projects List
         */
        if($("#add_project").is(":visible"))
        {
            Bugtracker.showContent("main");
        }
        else
        {
            Bugtracker.showContent("create_project");
        }
    },

    /**
     * Submit the form contents to the create link
     * @param Object form
     */
    createProject: function(object)
    {
        debug.debug("Bugtracker.createProject");


        var values = {
            csrf_token_name: Config.CSRF
        };

        $("#add_project").find("input, select").each(function(){
            if($(this).attr("type") != "submit")
            {
                values[$(this).attr("name")] = $(this).val();
            }
        });

        $.post(Config.URL + this.links.create, values, function(data){
            debug.debug("BugtrackerAdmin.createProject Callback");

            if(data.state == "error"){
                UI.alert(data.message);
            }
            else if(data.state == "success"){
                UI.alert(data.message);
                window.location = Config.URL + Bugtracker.links.main;
            }

        }, 'json')
        .fail(function(){
            debug.debug("BugtrackerAdmin.createProject Callback Failure");
            UI.alert("Something went wrong.");
        });
    },

    /**
     * Save the opened project
     * @param Integer id
     */
    saveProject: function(id)
    {
        var values = {
            csrf_token_name: Config.CSRF
        };

        $("#bugtracker_edit").find("input, select").each(function()
        {
            if($(this).attr("type") != "submit")
            {
                values[$(this).attr("name")] = $(this).val();
            }
        });

        $.post(Config.URL + this.links.save + id, values, function(data){
            debug.debug("BugtrackerAdmin.saveProject Callback");

            if(data.state == "error"){
                UI.alert(data.message);
            }
            else if(data.state == "success"){
                UI.alert(data.message);
                window.location = Config.URL + Bugtracker.links.main;
            }

        }, 'json')
        .fail(function(){
            debug.debug("BugtrackerAdmin.saveProject Callback Failure");
            UI.alert("Something went wrong.");
        });
    },

    /**
     * Removes an entry from the list
     * @param  Int id
     * @param  Object element
     */
    remove: function(id, element)
    {
        var identifier = this.identifier,
            removeLink = this.Links.remove;

        UI.confirm("Willst du wirklich dieses Projekt enternen?", "Yes", function()
        {
            $("#" + identifier + "_count").html(parseInt($("#" + identifier + "_count").html()) - 1);

            $(element).parents("li").slideUp(300, function()
            {
                $(this).remove();
            });

            $.get(Config.URL + removeLink + id);
        });
    },

    /**
     * Move up/down
     * @param String direction
     * @param Int id
     * @param Object element
     */
    move: function(direction, id, element)
    {
        var row = $(element).parents("tr");
        var targetRow = (direction == "up") ? row.prev("tr") : row.next("tr");

        if(targetRow.length)
        {
            $.get(Config.URL + this.links.move + id + "/" + direction, function(data)
            {
                debug.debug(data);
            });

            row.hide(300, function()
            {
                if(direction == "down")
                {
                    targetRow.after(row);
                }
                else
                {
                    targetRow.before(row);
                }

                row.slideDown(300);
            });
        }
    }
};