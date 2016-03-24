{*moduleFaculties: The faculties list*}
	{capture name = "moduleFaculties"}
								{literal}
							<script type="text/javascript">
							<!--

							  function deleteFaculties(el, faculties) {
								parameters = {delete_faculties:faculties, method: 'get'};
								 var url    = 'administrator.php?ctg=faculties';
								ajaxRequest(el, url, parameters, onDeleteFaculties);	
							}
							function onDeleteFaculties(el, response) {
								new Effect.Fade(el.up().up());
							    /*try {
							        eF_js_changePage(0, 0);
							    } catch (e) {alert(e);}*/
							}
							// -->
							</script>
							{/literal}
		<tr>
			<td class = "moduleCell">
				{if $smarty.get.add_faculties || $smarty.get.edit_faculties}
					{capture name = "t_faculties_form"}
						{eF_template_printForm form = $T_FACULTIES_FORM}
					{/capture}

					{capture name='t_new_faculties_code'}
			<div class = "tabber">
				{eF_template_printBlock tabber = "faculties" title=$smarty.const._FACOPTIONS data=$smarty.capture.t_faculties_form image='32x32/generic.png'  options = $T_STATS_LINK}

			{if $smarty.get.edit_faculties}
				<script>var editFaculties = '{$smarty.get.edit_faculties}';</script>
			{/if}
			</div>
		{/capture}
		{if $smarty.get.add_faculties}
				{eF_template_printBlock title = $smarty.const._NEWFACULTIES data = $smarty.capture.t_new_faculties_code image = '32x32/users.png'}
		{else}
				{eF_template_printBlock title = "`$smarty.const._OPTIONSFORFAC` <span class = 'innerTableName'>&quot;`$T_CURRENT_FACULTIES->faculties.name`&quot;</span>" data = $smarty.capture.t_new_faculties_code image = '32x32/users.png'}
		{/if}


				{else}
					{capture name = 't_roles_code'}
						<script>var activate = '{$smarty.const._ACTIVATE}';var deactivate = '{$smarty.const._DEACTIVATE}';</script>
						{if !isset($T_CURRENT_USER->coreAccess.configuration) || $T_CURRENT_USER->coreAccess.configuration == 'change'}
							<div class = "headerTools">
								<span>
									<img src = "images/16x16/add.png" title = "{$smarty.const._NEWFACULTIES}" alt = "{$smarty.const._NEWFACULTIES}">
									<a href = "administrator.php?ctg=faculties&add_faculties=1" title = "{$smarty.const._NEWFACULTIES}" >{$smarty.const._NEWFACULTIES}</a>                                                    
								</span>
							</div>
				                                
							{assign var = "change_faculties" value = 1}
						{/if}
						
						<table style = "width:100%" class = "sortedTable" sortBy = "0">
							<tr class = "topTitle">
								<td class = "topTitle" name="name">{$smarty.const._NAMEFAC}</td>
								<td class = "topTitle" code="code">{$smarty.const._CODEFAC}</td>
								<td class = "topTitle centerAlign" name="active">{$smarty.const._ACTIVE2}</td>
	                            
								{if $change_faculties}
								<td class = "topTitle centerAlign">{$smarty.const._OPERATIONS}</td>
								{/if}
							</tr>
							
							{foreach name = 'faculties_list' key = 'key' item = 'faculties' from = $T_USERTYPES_DATA}
							
							<tr class = "{cycle values = "oddRowColor, evenRowColor"}">
								<td>
									<a href = "administrator.php?ctg=faculties&edit_faculties={$faculties.id}"  class = "editLink">{$faculties.name}</a>
								</td>
								<td>{$faculties.code}</td>
								<td class = "centerAlign">
								{if $faculties.active == 1}
								<a href = "administrator.php?ctg=faculties&deactivate_faculties={$faculties.id}"  class = "editLink">
									<img class = "ajaxHandle" src = "images/16x16/trafficlight_green.png" alt = "{$smarty.const._DEACTIVATE}" title = "{$smarty.const._DEACTIVATE}" > </a>
								{else}
								<a href = "administrator.php?ctg=faculties&activate_faculties={$faculties.id}"  class = "editLink">
									<img class = "ajaxHandle" src = "images/16x16/trafficlight_red.png"   alt = "{$smarty.const._ACTIVATE}"   title = "{$smarty.const._ACTIVATE}" > </a>
								{/if}
								</td>
								
								{if $change_faculties}
								<td class = "centerAlign">
									<a href = "administrator.php?ctg=faculties&edit_faculties={$faculties.id}"  class = "editLink"><img src = "images/16x16/edit.png" title = "{$smarty.const._EDIT}" alt = "{$smarty.const._EDIT}" /></a>
									<img class = "ajaxHandle" border = "0" src = "images/16x16/error_delete.png" title = "{$smarty.const._DELETE}" alt = "{$smarty.const._DELETE}" onclick = "if (confirm('{$smarty.const._AREYOUSUREYOUWANTTODELETEFACULTIES}')) deleteFaculties(this, '{$faculties.id}');"/>
							
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
                
                	{eF_template_printBlock title = $smarty.const._FACULTIES data = $smarty.capture.t_roles_code image = 'faculties.png'}
				{/if}
			</td>
		</tr>
	{/capture}