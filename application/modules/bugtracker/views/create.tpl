<style type="text/css">
	textarea{
	    width:500px;
	    overflow:auto;
	}
	pre{
	    border: 1px solid white;
	    padding: 10px 20px;
	    margin-left: 50px;
	}
	b, strong{ color:white;}
	h2{ padding: 10px 0px; color:#F0E29A;}
</style>

<script type="text/javascript">
$(document).ready(function() {

    Bugtracker.urlGetBugs = {site_url('bugtracker/ajaxGetBugs/')};
    Bugtracker.initialize();
    
});
</script>

{form_open('bugtracker/create')}
            
<div class="table">
	<table border="0" cellpadding="5" cellspacing="0" width="800">
	    <thead>
	        <tr>
	            <th colspan="3"><span class="sort-tab">Neuen Bug eintragen</span></th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr>
	            <td valign="top" width="120"><strong>Kategorie:</strong></td>
	            <td valign="top">
	                {form_dropdown('class', $bugTypes)}
	                <div id="char-detail" class="form-details" style="display:none">
	                    <br>
	                    <label>Ist ein bestimmter Charakter betroffen dann gib hier den Charakternamen ein:</label><br/>
	                    <input type="text" id="detail-char" name="char-detail" size="50" value=""/>
	                </div>
	                <div id="instance-detail" class="form-details" style="display:none">
	                    <br>
	                    <label>Um welche Instanz/welchen Raid geht es:</label><br/>
	                    <input type="text" id="zone-name" name="auto-instance" size="50" value=""/>
	                    <input type="hidden" id="zone-id" name="zone-id" value="0"/>
	                </div>
	                <div id="npc-detail" class="form-details" style="display:none">
	                    <label>Ist ein bestimmter Boss/NPC betroffen:</label><br/>
	                    <input type="text" id="auto-npc" name="auto-npc" size="50" value=""/>
	                    <input type="hidden" id="npc-id" name="npc-id" value="0"/>
	                </div>
	                <div id="quest-detail" class="form-details" style="display:none">
	                    <br>
	                    <label>Suche in diesem Feld nach dem Questnamen:</label><br/>
	                    <input type="text" id="detail-search" name="quest-detail" size="50" value=""/>
	                </div>
	                <br/><strong>Kategorienhilfe:</strong>
	                <ul>
	                    <li>Charakter: Fehler bei Talenten, Fähigkeiten. Fehlendes Equip oder anderes bitte einen GM direkt anschreiben.</li>
	                    <li>Quest: Wenn ein Quest im Spiel ist wähle diese Kategorie.</li>
	                    <li>Instanz: Alles rund um Instanzen und Raids, fehlerhafte Beute oder Bosse und ähnliches.</li>
	                    <li>NPC: Fehler mit Kreaturen/Bossen außerhalb von Raids und ohne direkten Zusammenhang mit einer Quest</li>
	                </ul>
	            </td>
	        </tr>
	        <tr>
	            <td valign="top" width="120"><strong>Link:</strong></td>
	            <td valign="top">
	                <input type="text" id="form-link" name="link" size="50" value=""/> <span id="link-tt"></span><br>
	                <span id="link2-wrapper" style="display:none"><input type="text" id="form-link2" name="link2" size="50" value=""/> <span id="link-tt2"></span><br></span>
	                Hier den Link von <a href="http://de.wowhead.com" target="_blank">http://de.wowhead.com</a> eintragen.</td>
	        </tr>
	        <tr>
	            <td valign="top" id="label-other-bugs">&nbsp;</td>
	            <td id="other-bugs">&nbsp;</td>
	        </tr>
	        <tr>
	            <td colspan="3"><hr /></td>
	        </tr>
	        <tr>
	            <td><strong>Titel:</strong></td>
	            <td colspan="2"><input type="text" id="form-title" name="title" size="50" value=""/></td>
	        </tr>
	        <tr>
	            <td><strong>Beschreibung:</strong></td>
	            <td colspan="2"><textarea rows="8" id="form-desc" name="desc" cols="95"></textarea></td>
	        </tr>
	        <tr>
	            <td>
	                <div class="submit">
	                    <button class="ui-button button1 comment-submit " type="submit" id="form-submit">
	                        <span><span>Eintragen</span></span>
	                    </button>
	                </div>
	            </td>
	        </tr>
	    </tbody>
	</table>
	<hr />

</div>

{form_close()}
            
<script type="text/javascript" src="/application/js/data.instances.js"></script>
