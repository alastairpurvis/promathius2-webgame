<h1>Welcome to the {$servname} Stock Market!</h1>
<br />

{if $trans != ''}
	<hr>
	{$trans}
	<hr>
	<br />
{/if}

<table class="inputtable stocks" width="50" cellSpacing="0" bordercolor="#000000">
<tr>
<th>&nbsp;</th>
{section name=names loop=$stocknames}
	<th width="25">{$stocknames[names].symbol}</th>
{/section}
</tr><tr>
<td><p align='right'><img src="images/scale.jpg" height="360" width="36"></p></td>
{section name=index loop=$stocknames}
	<td>
	<img src="images/redfade.gif" width="30" height="{$stocknames[index].bprice}" /><img src="{$dat}spacer.gif" height="0" width="0" /><br />
	<img src="images/greenfade.gif" width="30" height="{$stocknames[index].price}" /><br />
	</td>
{/section}
</tr><tr>

<td class="stbl">Price:</td>
{section name=price loop=$stocknames}
	<td class="stbl">
	${$stocknames[price].lprice}
	</td>
{/section}
</tr><tr>

<td class="stbl">Yesterday:</td>
{section name=price loop=$stocknames}
	<td class="stbl">
	${$stocknames[price].lprice-$stocknames[price].days_1|cnum}
	</td>
{/section}
</tr><tr>

<td class="stbl"><nobr>2 days ago:</nobr></td>
{section name=price loop=$stocknames}
	<td class="stbl">
	${$stocknames[price].days_1-$stocknames[price].days_2|cnum}
	</td>
{/section}
</tr>

<td class="stbl"><nobr>3 days ago:</nobr></td>
{section name=price loop=$stocknames}
	<td class="stbl">
	${$stocknames[price].days_2-$stocknames[price].days_3|cnum}
	</td>
{/section}
</tr>

</table>

<br /><br />
<form action="{$main}?stocks{$authstr}" method="post">
<table cellSpacing='20'><tr><td>
{section name=buying loop=$stockbuy}
		<table class="inputtable" width="10">
		<tr><th colspan="2"><nobr>Trade shares of {$stockbuy[buying].name} ({$stockbuy[buying].symbol})</nobr></th><th>Max</th></tr>
		<tr><th style="text-align: left;">Owned:</td><td style="text-align: right;">{$stockbuy[buying].owned}</td><td>&nbsp;</td></tr>
		<tr><th style="text-align: left;">Price:</td><td style="text-align: right;">${$stockbuy[buying].price}</td><td>&nbsp;</td></tr>
		<tr>
		   <th style="text-align: left;"><nobr>Buy shares</nobr></th>
		   <td style="text-align: right;"><input type="text" length="5" name="buy[{$stockbuy[buying].id}]"></td>
		   <td style="text-align: center;"><input type="checkbox" name="bmax[{$stockbuy[buying].id}]"></td>
		</tr>
		<tr>
		   <th style="text-align: left;"><nobr>Sell shares</nobr></th>
		   <td style="text-align: right;"><input type="text" length="5" name="sell[{$stockbuy[buying].id}]"></td>
		   <td style="text-align: center;"><input type="checkbox" name="smax[{$stockbuy[buying].id}]"></td>
		</tr>
		<tr>
		</table>
	</td>
	{cycle values="<td>,</tr><tr><td>"}
{/section}
</td><td></td>
</tr></table>

<input type="submit" name="do_trade" value="Complete Transaction">
</form>
