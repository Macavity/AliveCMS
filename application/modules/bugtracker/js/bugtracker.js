

var Bugtracker = {
    
    urlGetBugs: '',
    
    initialize: function(){
        
        $('[name="class"]').change(function(){
            $(".form-details").hide();
            /*if(this.value == "[Charakter]"){
                $("#char-detail").show();
            }*/
            if(this.value == 1){
                $("#quest-detail").show();
            }
            if(this.value == 2){
                $("#instance-detail").show();
                $("#npc-detail").show();
                $("#link2-wrapper").show();
                
            }
            if(this.value == 3){
                $("#npc-detail").show();
            }
        });

        $("#zone-name").autocomplete({
            minLength: 2,
            source: instanceData,
            select: function(event, ui) {
                $("#zone-id").val(ui.item.value);
                if($("#form-title").val().length == 0)
                    $("#form-title").val(ui.item.label);
                $("#form-link").val("http://de.wowhead.com/zone="+ui.item.value);
                $("#link-tt").html('<a href="http://de.wowhead.com/zone='+ui.item.value+'" target="_blank">WoWhead Tooltip</a>');
                getBugs($("#form-link").val());
            },
            change: function(event, ui) {
                $("#zone-name").val("");
            } 
        });
        $("#zone-name").blur(function(event, ui) {
            $("#zone-name").val("");
        });

        $("#auto-npc").autocomplete({
            minLength: 3,
            source: "http://portal.wow-alive.de/ajax/search/npc/",
            select: function(event, ui) {
                $("#npc-id").val(ui.item.value);
                if($("#class").val() == 3){
                    $("#form-link").val("http://de.wowhead.com/npc="+ui.item.value);
                    $("#link-tt").html('<a href="http://de.wowhead.com/npc='+ui.item.value+'" target="_blank">WoWhead Tooltip</a>');
                    if($("#form-title").val().length == 0)
                        $("#form-title").val(ui.item.label);
                }
                else{
                    $("#link2-wrapper").show();
                    $("#form-link2").val("http://de.wowhead.com/npc="+ui.item.value);
                    $("#link-tt2").html('<a href="http://de.wowhead.com/npc='+ui.item.value+'" target="_blank">WoWhead Tooltip</a>');
                    $("#form-title").val($("#form-title").val()+": "+ui.item.label);
                }
                getBugs($("#form-link").val());
            },
            change: function(event, ui) {
                $("#auto-npc").val("");
            } 
        });
        
        $("#detail-search").autocomplete({ 
            minLength: 3,
            source: "http://portal.wow-alive.de/ajax/search-quest/",
            select: function(event, ui) {
                $("#form-link").val("http://de.wowhead.com/quest="+ui.item.value);
                $("#form-title").val(ui.item.label);
                $("#link-tt").html('<a href="http://de.wowhead.com/quest='+ui.item.value+'" target="_blank">WoWhead Tooltip</a>');
                getBugs($("#form-link").val());
            },
            change: function(event, ui) {
                $("#detail-search").val("");
            } 
        });
        
        $("#form-submit").click(function(){
            if($("#class").val() == "-"){
                Toast.show("Bitte wähle zuerst aus welche Kategorie der Bug hat.");
                return false;
            }
            if($("#form-title").val() == ""){
                Toast.show("Bitte trage vor dem Abschicken einen Titel ein.");
                return false;
            }
            
        });

        $("#form-link").change(function(){ getBugs($("#form-link").val()); });
    },
    
    /**
     * 
     */
    getBugs: function(term){
        $.getJSON('/ajax/search/bugs/?term='+term, function(data) {
            var items = [];
            
            $.each(data, function(key, val) {
                items.push('<a href="/server/bugtracker/bug/' + key + '" target="_blank"> Bug #'+key+' - ' + val + '</a>');
            });
            if(items.length > 0){
                $('#other-bugs').html('<span class="color-tooltip-red">Achtung!</span> Es gibt dazu schon Bug Reports:<br/>'+items.join('<br/>'));
                $("#label-other-bugs").html("");
            }
            else{
                $('#other-bugs').html('Keine anderen Bug Reports gefunden - <span class="color-tooltip-green">Alles in Ordnung</span>');
                $("#label-other-bugs").html("");
            }
        }); 
    }
};