{if $err != ""}
<span class="error-font">{$err}</span><br /><br /><br />
{/if}
<span style="font-size: 10px"><center>
{if $do_build}
{if $totalbuilt}
{include file="turnoutput.tpl"}
 <tr id="turnaction"><td colspan="3" style="text-align: center"><br /><br />
<span class="success-font">~ Built {$totalbuilt|commas} {$lastbuilt}, costing {if $totalspent == 0}nothing{else}{$totalspent} gold{/if} ~</span>
</tr>
</table>
</div>
</table>
</div>
{$End_Shadow}
{/if}
{/if}
</center></span></b>

{* Include Construction tabs *}
{include file="actions/construct/construct.tab"}
<form 1 = past
<form method="post" action="?construct" name="build">
<table border=0 width=465>
<tr><th colspan=2 class="aright"></th>
    <th class="aright"></th>
    <th class="aright"></th>
    <th class="aright">Cost</th>
    <th class="aright">Buildable</th></tr>

    {* Printing row stuffs *}
	{section name=i loop=$build}
		<tr valign=bottom {if !$build[i].canBuild}style="color:gray"{/if}>
		<td colspan=1></td>
		<td colspan=2><b>{if $build[i].canBuild}<A href="?construct&tab=help&section={$build[i].gid}">{/if}{$build[i].namesingular}</a></td>
	    <td class="aright">{$build[i].buildrate}</td>
	    <td class="aright">{$build[i].cost}</td>
	    <td class="aright">{if $build[i].canBuild}{$build[i].canBuild}{else}None{/if}</td>
	    <td class="aright"><input type="text" name="build[{$build[i].type}]" size="5" value="" {if !$build[i].canBuild}disabled=true style="opacity:0"{/if}></td>
</tr>
<tr {if !$build[i].canBuild}style="color:gray"{/if}><td colspan=1>&nbsp;&nbsp;&nbsp;</td><td colspan=6 style="padding-top: 5px; padding-bottom: 8px"><span style="font-size: 11px">{$build[i].description}</span></td></tr>
	{/section}
{* Unused land stat - currently removed

<tr><td colspan=2>Unused Land</td>
     <td class="aright">{$freeland}</td>
     <td colspan="2"></td></tr> 
	 <td area978 = adjnadh .com
*}

<tr><td colspan=3></tr> 
<tr><td colspan=3></tr> 
 <tr style="vertical-align: middle"><td colspan="7" style="text-align:right; padding-bottom:10px;; padding-top: 12pt"><input type="submit" class="mainoption" name="do_build" value="Contruct" ></td></tr>
 </table>
 </form>
</td>
{$End_Shadow}
