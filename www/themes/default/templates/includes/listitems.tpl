{*moduleListitems: The listitems*}
	{capture name = "moduleListitems"}
								{literal}
							<script type="text/javascript">
							<!--
							  function deleteListitems(el, listitems) {
								parameters = {delete_listitems:listitems, method: 'get'};
								 var url    = 'administrator.php?ctg=listitems';
								ajaxRequest(el, url, parameters, onDeleteListitems);	
							}
							function onDeleteListitems(el, response) {
								new Effect.Fade(el.up().up());
							    /*try {
							        eF_js_changePage(0, 0);
							    } catch (e) {alert(e);}*/
							}
							// -->
							function activateListitems(el, listitems) {
								if (el.className.match('red')) {
							    	parameters = {activate_listitems:listitems, method: 'get'};
								} else {
									parameters = {deactivate_listitems:listitems, method: 'get'};
								}
							    var url    = 'administrator.php?ctg=listitems';
							    ajaxRequest(el, url, parameters, onActivateListitems);
							}
							function onActivateListitems(el, response) {
							    if (response == 0) {
							    	setImageSrc(el, 16, "trafficlight_red.png");
							        el.writeAttribute({alt:activate, title:activate});
							    } else if (response == 1) {
							    	setImageSrc(el, 16, "trafficlight_green.png");
							        el.writeAttribute({alt:deactivate, title:deactivate});
							    }
							}
							</script>

							{/literal}
		<tr>
			<td class = "moduleCell">
				{if $smarty.get.add_listitems || $smarty.get.edit_listitems}
					{capture name = "t_listitems_form"}
						{eF_template_printForm form = $T_LISTITEMS_FORM}
					{/capture}

					{capture name='t_new_listitems_code'}
			<div class = "tabber">
				{eF_template_printBlock tabber = "listitems" title=$smarty.const._FACOPTIONS data=$smarty.capture.t_listitems_form image='32x32/generic.png'  options = $T_STATS_LINK}

			{if $smarty.get.edit_listitems}
				<script>var editListitems = '{$smarty.get.edit_listitems}';</script>
			{/if}
			</div>
		{/capture}
		{if $smarty.get.add_listitems}
				{eF_template_printBlock title = $smarty.const._NEWFLISTITEMS data = $smarty.capture.t_new_listitems_code image = '32x32/users.png'}
		{else}
				{eF_template_printBlock title = "`$smarty.const._OPTIONSFORFAC` <span class = 'innerTableName'>&quot;`$T_CURRENT_LISTITEMS->listitems.name`&quot;</span>" data = $smarty.capture.t_new_listitems_code image = '32x32/users.png'}
		{/if}


				{else}
					{capture name = 't_roles_code'}
						<script>var activate = '{$smarty.const._ACTIVATE}';var deactivate = '{$smarty.const._DEACTIVATE}';</script>
						{if !isset($T_CURRENT_USER->coreAccess.configuration) || $T_CURRENT_USER->coreAccess.configuration == 'change'}
							<div class = "headerTools">
								<span>
									<img src = "images/16x16/add.png" title = "{$smarty.const._NEWFLISTITEMS}" alt = "{$smarty.const._NEWFLISTITEMS}">
									<a href = "administrator.php?ctg=listitems&add_listitems=1" title = "{$smarty.const._NEWFLISTITEMS}" >{$smarty.const._NEWFLISTITEMS}</a>                                                    
								</span>
							</div>
				                                
							{assign var = "change_listitems" value = 1}
						{/if}
						
						<table style = "width:100%" class = "sortedTable" sortBy = "0">
							<tr class = "topTitle">
								<td class = "topTitle" name="name">{$smarty.const._NAMELIST}</td>
								<td class = "topTitle" code="code">{$smarty.const._CODELIST}</td>
								<td class = "topTitle" pz_prepod="pz_prepod">{$smarty.const._PZLIST}</td>
								<td class = "topTitle" lk_prepod="lk_prepod">{$smarty.const._LKLIST}</td>
								<td class = "topTitle" 	type="type">{$smarty.const._TYPELIST}</td>
								<td class = "topTitle" number_course="number_course">{$smarty.const._NUMBERCOURSELIST}</td>
								<td class = "topTitle" kafedra_id="kafedra_id">{$smarty.const._DEPLIST}</td>
								<td class = "topTitle centerAlign" name="active">{$smarty.const._ACTIVE2}</td>
	                            
								{if $change_listitems}
								<td class = "topTitle centerAlign">{$smarty.const._OPERATIONS}</td>
								{/if}
							</tr>
							
							{foreach name = 'listitems_list' key = 'key' item = 'listitems' from = $T_USERTYPES_DATA}
							
							<tr class = "{cycle values = "oddRowColor, evenRowColor"}">
								<td>
									<a href = "administrator.php?ctg=listitems&edit_listitems={$listitems.id}"  class = "editLink">{$listitems.name}</a>
								</td>
								<td>{$listitems.code}</td>
								<td>{$T_PR_PATHS[$listitems.pz_prepod]}</td>
								<td>{$T_PR_PATHS[$listitems.lk_prepod]}</td>
								<td>{$T_TL_PATHS[$listitems.type]}</td>
								<td>{$T_NC_PATHS[$listitems.number_course]}</td>
								<td>{$T_DIRECTIONS_PATHS[$listitems.kafedra_id]}</td>
								<td class = "centerAlign">
								{if $listitems.active == 1}
								
									<img class = "ajaxHandle" src = "images/16x16/trafficlight_green.png" alt = "{$smarty.const._DEACTIVATE}" title = "{$smarty.const._DEACTIVATE}" {if $change_listitems}onclick = "activateListitems(this, '{$listitems.id}')"{/if}>
								{else}
								
									<img class = "ajaxHandle" src = "images/16x16/trafficlight_red.png"   alt = "{$smarty.const._ACTIVATE}"   title = "{$smarty.const._ACTIVATE}" {if $change_listitems}onclick = "activateListitems(this, '{$listitems.id}')"{/if}>
								{/if}
								</td>
								
								{if $change_listitems}
								<td class = "centerAlign">
									<a href = "administrator.php?ctg=listitems&edit_listitems={$listitems.id}"  class = "editLink"><img src = "images/16x16/edit.png" title = "{$smarty.const._EDIT}" alt = "{$smarty.const._EDIT}" /></a>
									<img class = "ajaxHandle" border = "0" src = "images/16x16/error_delete.png" title = "{$smarty.const._DELETE}" alt = "{$smarty.const._DELETE}" onclick = "if (confirm('{$smarty.const._AREYOUSUREYOUWANTTODELETECOURSE}')) deleteListitems(this, '{$listitems.id}');"/>
							
								</td>
								{/if}
							</tr>
							
							{foreachelse}
							
	                        <tr class = "defaultRowHeight oddRowColor">
	                        	<td class = "emptyCategory" colspan = "100%">{$smarty.const._NODATAFOUND}</td>
	                        </tr>
							
							{/foreach}
						</table>
					{/capture}
                
                	{eF_template_printBlock title = $smarty.const._LISTITEMS data = $smarty.capture.t_roles_code image = 'listitems.png'}
				{/if}
			</td>
		</tr>
	{/capture}