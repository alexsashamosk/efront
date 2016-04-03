{*moduleStlistitems: The stlistitems*}
	{capture name = "moduleStlistitems"}
								
		<tr>
			<td class = "moduleCell"> 

						{if $smarty.now|date_format:"%m/%d" > "01/01" && $smarty.now|date_format:"%m/%d" < "07/01"} 
					  
					  Семестр 2:

					   {elseif $smarty.now|date_format:"%m/%d" > "08/01" && $smarty.now|date_format:"%m/%d" < "01/01"}

						Семестр 1:

					   {/if}

						<table style = "width:100%" class = "sortedTable" sortBy = "0">
							<tr class = "topTitle">
								<td class = "topTitle" name="name" style="font-weight:bold">{$smarty.const._NAMELIST}</td>
								<td class = "topTitle" code="code" style="font-weight:bold">{$smarty.const._CODELIST}</td>
								<td class = "topTitle" pz_prepod="pz_prepod" style="font-weight:bold">{$smarty.const._PZLIST}</td>
								<td class = "topTitle" lk_prepod="lk_prepod" style="font-weight:bold">{$smarty.const._LKLIST}</td>
								<td class = "topTitle" 	type="type" style="font-weight:bold">{$smarty.const._TYPELIST}</td>
								<td class = "topTitle" number_course="number_course" style="font-weight:bold">{$smarty.const._NUMBERCOURSELIST}</td>		
							</tr>
							
					  {if $smarty.now|date_format:"%m/%d" > "01/01" && $smarty.now|date_format:"%m/%d" < "07/01"} 

					  {foreach name = 'listitems_list' key = 'key' item = 'listitems' from = $T_USERTYPESSECOND_DATA}

					  <tr class = "{cycle values = "oddRowColor, evenRowColor"}">
								<td  style="font-weight:bold" class = "editLink">{$listitems.name}</td>
								<td >{$listitems.code}</td>
								<td>{$T_PR_PATHS[$listitems.pz_prepod]}</td>
								<td>{$T_PR_PATHS[$listitems.lk_prepod]}</td>
								<td style="font-weight:bold" class = "editLink">{$T_TL_PATHS[$listitems.type]}</td>
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

					   {elseif $smarty.now|date_format:"%m/%d" > "08/01" && $smarty.now|date_format:"%m/%d" < "01/01"}

						{foreach name = 'listitems_list' key = 'key' item = 'listitems' from = $T_USERTYPESFIRST_DATA}

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

					   {/if}

							
							
							
                
                	{eF_template_printBlock title = $smarty.const._LISTITEMS data = $smarty.capture.t_roles_code image = 'listitems.png'}
			  
			</td>
		</tr>
	{/capture}