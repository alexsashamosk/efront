    {capture name='final_grade'}
        <tr>
            <td class = "moduleCell">
                {if $smarty.get.sel_user && $smarty.get.course}
                <center><h3>{$smarty.const._FINALGRADEFOR} {$smarty.get.sel_user} </h3></center>
                        {eF_template_printForm form = $T_FINALGRADE_FORM}

            <div class = "tabber">
                {eF_template_printBlock tabber = "course" title=$smarty.const._FINALGRADE data=$smarty.capture.t_departments_form image='32x32/generic.png'  options = $T_STATS_LINK}

            </div>
        {if  $smarty.get.sel_user && $smarty.get.course}
                
                {eF_template_printBlock title = "`$smarty.const._FINALGRADE` <span class = 'innerTableName'>&quot;`$T_CURRENT_DEPARTMENTS->departments.name`&quot;</span>" data = $smarty.capture.t_new_departments_code image = 'departments.png'}
        {/if}


                {/if}
            </td>
        </tr>
    {/capture}