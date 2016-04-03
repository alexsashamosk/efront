{*moduleDepartments: The departments list*}
	{capture name = "moduleDepartments"}
								{literal}
							<script type="text/javascript">
							<!--
							  function deleteDepartments(el, departments) {
								parameters = {delete_departments:departments, method: 'get'};
								 var url    = 'administrator.php?ctg=departments';
								ajaxRequest(el, url, parameters, onDeleteDepartments);	
							}
							function onDeleteDepartments(el, response) {
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
				{if $smarty.get.add_departments || $smarty.get.edit_departments}
					{capture name = "t_departments_form"}
						{eF_template_printForm form = $T_DEPARTMENTS_FORM}
					{/capture}

					{capture name='t_new_departments_code'}
			<div class = "tabber">
				{eF_template_printBlock tabber = "departments" title=$smarty.const._DEPOPTIONS data=$smarty.capture.t_departments_form image='32x32/generic.png'  options = $T_STATS_LINK}

			{if $smarty.get.edit_departments}
				<script>var editDepartments = '{$smarty.get.edit_departments}';</script>
			{/if}
			</div>
		{/capture}
		{if $smarty.get.add_departments}
				{eF_template_printBlock title = $smarty.const._NEWDEPARTMENTS data = $smarty.capture.t_new_departments_code image = 'departments.png'}
		{else}
				{eF_template_printBlock title = "`$smarty.const._OPTIONSFORDEP` <span class = 'innerTableName'>&quot;`$T_CURRENT_DEPARTMENTS->departments.name`&quot;</span>" data = $smarty.capture.t_new_departments_code image = 'departments.png'}
		{/if}


				{else}
					{capture name = 't_roles_code'}
						<script>var activate = '{$smarty.const._ACTIVATE}';var deactivate = '{$smarty.const._DEACTIVATE}';</script>
						{if !isset($T_CURRENT_USER->coreAccess.configuration) || $T_CURRENT_USER->coreAccess.configuration == 'change'}
							<div class = "headerTools">
								<span>
									<img src = "images/16x16/add.png" title = "{$smarty.const._NEWDEPARTMENTS}" alt = "{$smarty.const._NEWDEPARTMENTS}">
									<a href = "administrator.php?ctg=departments&add_departments=1" title = "{$smarty.const._NEWDEPARTMENTS}" >{$smarty.const._NEWDEPARTMENTS}</a>                                                    
								</span>
							</div>
				                                
							{assign var = "change_departments" value = 1}
						{/if}
						
						<table style = "width:100%" class = "sortedTable" sortBy = "0">
							<tr class = "topTitle">
								<td class = "topTitle" name="name">{$smarty.const._NAMEDEP}</td>
								<td class = "topTitle" code="code">{$smarty.const._CODEDEP}</td>
								<td class = "topTitle" code="code">{$smarty.const._NAMEFAC}</td>
								<td class = "topTitle" code="code">Link</td>
								<td class = "topTitle centerAlign" name="active">{$smarty.const._ACTIVE2}</td>
	                            
								{if $change_departments}
								<td class = "topTitle centerAlign">{$smarty.const._OPERATIONS}</td>
								{/if}
							</tr>
							
							{foreach name = 'departments_list' key = 'key' item = 'departments' from = $T_USERTYPES_DATA}
							
							<tr class = "{cycle values = "oddRowColor, evenRowColor"}">
								<td>
									<a href = "administrator.php?ctg=departments&edit_departments={$departments.id}"  class = "editLink">{$departments.name}</a>
								</td>
								<td>{$departments.code}</td>
								<td>{$T_DIRECTIONS_PATHS[$departments.faculty_id]}</td>
								<td>{$departments.link}</td>
								<td class = "centerAlign">
								{if $departments.active == 1}
								<a href = "administrator.php?ctg=departments&deactivate_departments={$departments.id}"  class = "editLink">
									<img class = "ajaxHandle" src = "images/16x16/trafficlight_green.png" alt = "{$smarty.const._DEACTIVATE}" title = "{$smarty.const._DEACTIVATE}" > </a>
								{else}
								<a href = "administrator.php?ctg=departments&activate_departments={$departments.id}"  class = "editLink">
									<img class = "ajaxHandle" src = "images/16x16/trafficlight_red.png"   alt = "{$smarty.const._ACTIVATE}"   title = "{$smarty.const._ACTIVATE}" > </a>
								{/if}
								</td>
								
								{if $change_departments}
								<td class = "centerAlign">
									<a href = "administrator.php?ctg=departments&edit_departments={$departments.id}"  class = "editLink"><img src = "images/16x16/edit.png" title = "{$smarty.const._EDIT}" alt = "{$smarty.const._EDIT}" /></a>
									<img class = "ajaxHandle" border = "0" src = "images/16x16/error_delete.png" title = "{$smarty.const._DELETE}" alt = "{$smarty.const._DELETE}" onclick = "if (confirm('{$smarty.const._AREYOUSUREYOUWANTTODELETEDEPARTMENTS}')) deleteDepartments(this, '{$departments.id}');"/>
							
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
                
                	{eF_template_printBlock title = $smarty.const._DEPARTMENTS data = $smarty.capture.t_roles_code image = 'departments.png'}
				{/if}
			</td>
		</tr>
	{/capture}