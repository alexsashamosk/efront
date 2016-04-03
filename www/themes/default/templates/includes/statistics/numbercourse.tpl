{capture name='display_numbercourse_statistics'}

<table class = "statisticsTools statisticsSelectList">
                <tr>
                    <td id = "right">
                        {$smarty.const._EXPORTSTATS}
                        <a href = "{$smarty.server.PHP_SELF}?{$smarty.server.QUERY_STRING}&excel=1">
                            <img src = "images/file_types/xls.png" title = "{$smarty.const._XLSFORMAT}" alt = "{$smarty.const._XLSFORMAT}" />
                        </a>
                    </td></tr>
            </table>

<form name = "systemperiod">
<table class = "statisticsSelectDate">
        <tr><td class = "labelCell">{$smarty.const._SELECTGROUP}:&nbsp;</td>
                            <td><select style = "vertical-align:middle" id = "group_filter" name = "group_filter" required>
                            <option value = "-1" class = "inactiveElement" {if !$smarty.get.group_filter}selected{/if}>{$smarty.const._SELECTGROUP}</option>
                            {foreach name = "group_options" from = $T_GROUPS item = 'group' key='id'}

                                <option value = "{$group.id}" {if $smarty.get.group_filter == $group.id}selected{/if}>{$T_NUMBERCOURSE[$group.number_course]}</option>
                            {/foreach}
                            </select>
        <tr><td class = "labelCell">{$smarty.const._NAMEDEP}:&nbsp;</td>
                            <td><select style = "vertical-align:middle" id = "dep_filter" name = "dep_filter" required>
                                <option value = "-1" class = "inactiveElement" {if !$smarty.get.dep_filter}selected{/if}>
                                {$smarty.const._NAMEDEP}
                                </option>
                            {foreach name = "dep_options" from = $T_DEP_PATHS item = 'departments' key='id'}
                                <option value = "{$departments.id}" {if $smarty.get.dep_filter == $departments.id}selected{/if}>{$departments.name}
                                </option>
                            {/foreach}
                            </select>
        <tr><td class = "labelCell">{$smarty.const._COURSE}:&nbsp;</td>
                            <td><select style = "vertical-align:middle" id = "course_filter" name = "course_filter" required>
                                <option value = "-1" class = "inactiveElement" {if !$smarty.get.course_filter}selected{/if}>
                                {$smarty.const._COURSE}
                                </option>
                            {foreach name = "course_options" from = $T_COURSEREP_PATHS item = 'course' key='id'}
                                <option value = "{$course.id}" {if $smarty.get.course_filter == $course.id}selected{/if}>{$course.name}
                                </option>
                            {/foreach}
                            </select>
                            <input class = "flatButton" type = "button" value="{$smarty.const._SUBMIT}" onclick = "document.location='administrator.php?ctg=statistics&option=numbercourse&tab=system_traffic&dep_filter='+$('dep_filter').options[$('dep_filter').selectedIndex].value+'&group_filter='+$('group_filter').options[$('group_filter').selectedIndex].value+'&course_filter='+$('course_filter').options[$('course_filter').selectedIndex].value"> 
                            </td>                         
                            </tr>
</table>
</form>

<tr>
            <td class = "moduleCell">
                        
                        <table style = "width:100%" class = "sortedTable" sortBy = "0">
                        
                            <tr class = "topTitle">
                                <td class = "topTitle" name="name" style="font-weight:bold">{$smarty.const._STPR}</td>
                            {foreach name = "course_options" from = $T_COURSEREP_PATHS item = 'course' key='id'}
                            {if $smarty.get.course_filter == $course.id} 
                                <td class = "topTitle centerAlign noSort" style="font-weight:bold">
                                {$course.name} </td>  
                            {/if}
                                {/foreach}

                            </tr>
                        
                            
                            {foreach name = 'report' key = 'id' item = 'report' from = $T_REPORT_PATHS}
                            <tr class = "{cycle values = "oddRowColor, evenRowColor"}">
                            
                                <td>{$report.name} {$report.surname}</td>
                                

                                <td class = "centerAlign" style="font-weight:bold" class = "editLink">{$report.final_grade} б. 
                                {if $report.final_grade >= 90} ({$smarty.const._VERYGOOD}) {/if}
                                {if $report.final_grade < 90 && $report.final_grade >= 74} ({$smarty.const._GOOD}) {/if}
                                {if $report.final_grade < 74 && $report.final_grade >= 60} ({$smarty.const._UD}) {/if}
                                {if $report.final_grade < 60} ({$smarty.const._NOUD}) {/if}
                                </td>
 
                                {foreachelse}
                            </tr>
                            
                            
                            
                            <tr class = "defaultRowHeight oddRowColor">
                                <td class = "emptyCategory" colspan = "100%">{$smarty.const._NODATAFOUND}</td>
                            </tr>
                            
                            {/foreach}
                        </table>
                
                    
            </td>
        </tr>
{/capture}
{eF_template_printBlock title = $smarty.const._NUMBERCOURSESTATISTICS data = $smarty.capture.display_numbercourse_statistics image = '32x32/reports.png' help = 'Reports'}
