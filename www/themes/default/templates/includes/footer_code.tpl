{if $T_THEME_SETTINGS->options.show_footer && $T_CONFIGURATION.show_footer}
	{if $T_CONFIGURATION.additional_footer}
		{$T_CONFIGURATION.additional_footer}
	{else}	
		<div><a href = "{$smarty.const._IBM}">{$smarty.const._IBMNAME}</a>  &bull; <a href = "index.php?ctg=contact">{$smarty.const._CONTACTUS}</a></div>
	{/if}
{/if}
