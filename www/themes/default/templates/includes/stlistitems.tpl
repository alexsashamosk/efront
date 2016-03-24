{*moduleStlistitems: The stlistitems*}
	{capture name = "moduleStlistitems"}
								
		<tr>
			<td class = "moduleCell">
						
						<table style = "width:100%" class = "sortedTable" sortBy = "0">
							<tr class = "topTitle">
								<td class = "topTitle" name="name">{$smarty.const._NAMELIST}</td>
								<td class = "topTitle" code="code">{$smarty.const._CODELIST}</td>
								<td class = "topTitle" pz_prepod="pz_prepod">{$smarty.const._PZLIST}</td>
								<td class = "topTitle" lk_prepod="lk_prepod">{$smarty.const._LKLIST}</td>
								<td class = "topTitle" 	type="type">{$smarty.const._TYPELIST}</td>
								<td class = "topTitle" number_course="number_course">{$smarty.const._NUMBERCOURSELIST}</td>		
							</tr>
							
							{foreach name = 'listitems_list' key = 'key' item = 'listitems' from = $T_USERTYPES_DATA}
							
							<tr class = "{cycle values = "oddRowColor, evenRowColor"}">
								<td>{$listitems.name}</td>
								<td>{$listitems.code}</td>
								<td>{$T_PR_PATHS[$listitems.pz_prepod]}</td>
								<td>{$T_PR_PATHS[$listitems.lk_prepod]}</td>
								<td>{$T_TL_PATHS[$listitems.type]}</td>
								<td>{$T_NC_PATHS[$listitems.number_course]}</td>
								<td class = "centerAlign">
								</td>
								
							</tr>
							
							{foreachelse}
							
	                        <tr class = "defaultRowHeight oddRowColor">
	                        	<td class = "emptyCategory" colspan = "100%">{$smarty.const._NODATAFOUND}</td>
	                        </tr>
							
							{/foreach}
						</table>
                
                	{eF_template_printBlock title = $smarty.const._LISTITEMS data = $smarty.capture.t_roles_code image = 'listitems.png'}
			</td>
		</tr>
	{/capture}