{literal}

<script language="javascript">

var curr = -1;

function CheckAll (check){
	var path = document.messages;
	 for (var i=0;i<path.elements.length;i++) {
		e = path.elements[i];
		checkname = "all";
		if(check==2) checkname = "all2";
		if( (e.name!=checkname)  && (e.type=="checkbox") ) {
			 e.checked = path.all.checked;
			 if(check==2) e.checked = path.all2.checked;
		}
	 }
}


function show(which) {
	if(curr != which) {
		eval("mess_val = message_" + which);
		eval("link_val = 'link_" + which + "'");
		curr = which;
	 	document.getElementById("show_mess").innerHTML = mess_val;
		var request = "/?m_read&amp;id_num=" + which + "{/literal}{$authstr}{literal}";
		var execframe = document.getElementById("theframe");
		execframe.src = request.replace(/&amp;/g, '&');
		var readspan = document.getElementById("link_"+which);
		readspan.style.fontWeight = "normal";
	}
	else {
		curr = -1;
		document.getElementById("show_mess").innerHTML = "<!---Hold--->";
	}
}

{/literal}


{section name=v loop=$jmessage}
	{section name=v loop=$jmessage}
		{if $jmessage[v].read == 0 }
			message_{$jmessage[v].id} = ' <table class="inputtable">	<tr><th class="acenter">Viewing Message</th></tr><tr><td>	  <form method="post" action="{$sitedir}{$main}?messages{$authstr}">	  <input type="hidden" name="msg_id" value="{$jmessage[v].id}">	  <input type="hidden" name="msg_src" value="{$jmessage[v].from_num}">	  <input type="hidden" name="msg_src_name" value="{$jmessage[v].from_name}">	  <input type="hidden" name="msg_title" value="{$jmessage[v].title}">	  <input type="hidden" name="msg_body" value="{$jmessage[v].msg_escaped|escape:"javascript"}">	  <table>	  <tr>	  <td class="aleft">	   <b>From:</b> {$jmessage[v].from_name} <a href=?profiles&amp;num={$jmessage[v].from_num}{$authstr}>(#{$jmessage[v].from_num})</a><br />	   <b>Subject:</b> {$jmessage[v].title}<br />	   <b>Date:</b> {$jmessage[v].date|escape:"javascript"}<br />	   <br /><tt>{$jmessage[v].msg|escape:"quotes"}{* $jmessage[v].msg *}</tt>	  </td>	  </tr>	  <tr><td><div align="center">	  <input type="submit" name="do_reply" value="Reply">	  <input type="submit" name="do_forward" value="Forward">	  <input type="submit" name="do_delete" value="Delete">	  </div></td></tr>	  </table>	  </form>	  </td></tr><tr><th><hr></th></tr></table> ';
		{/if}
	{/section}
{/section}

</script>




<table class="inputtable" width="100%">
<tr><th class="acenter">
<a href="{$main}?messages{$authstr}">{$inboxname}</a>
</th><th class="acenter">
<a href="{$main}?sentmail{$authstr}">{$sentname}</a>
</th></tr>
</table>
<br />
<br />
{if $do_reply}
	<form method="post" action="{$main}?messages{$authstr}">
	<div>
	Sending Reply To {$reply_name} <a href=?profiles&amp;num={$reply_src}{$authstr}>(#{$reply_src})</a><br />
	Message Title: <input type="text" name="msg_title" size="40" value="Re: {$reply_title}" maxlength="80"><br />
	<input type="hidden" name="msg_replyto" value="{$reply_id}">
	<input type="hidden" name="msg_dest" value="{$reply_src}">
	<textarea rows="10" cols="60" name="msg_body">{$reply_body}</textarea><br />
	<input type="submit" name="do_message" value="Send Reply to {$uera.empire} #{$reply_src}">
	</div>
	</form>
{/if}

{if $do_forward}
	<form method="post" action="{$main}?messages{$authstr}">

	Forward To:<select name="forward_num1" size="1">
	{section name=num_go loop=$numbers}
	<option value="{$numbers[num_go].num}" class="m{$numbers[num_go].color}" {if $numbers[num_go].num == -1} selected {/if}>{$numbers[num_go].num} - {$numbers[num_go].empire}</option>
	{/section}
	</select><br />


	Forward To:<select name="forward_num2" size="1">
	{section name=num_go loop=$numbers}
	<option value="{$numbers[num_go].num}" class="m{$numbers[num_go].color}" {if $numbers[num_go].num == -1} selected {/if}>{$numbers[num_go].num} - {$numbers[num_go].empire}</option>
	{/section}
	</select><br />

	Forward To:<select name="forward_num3" size="1">
	{section name=num_go loop=$numbers}
	<option value="{$numbers[num_go].num}" class="m{$numbers[num_go].color}" {if $numbers[num_go].num == -1} selected {/if}>{$numbers[num_go].num} - {$numbers[num_go].empire}</option>
	{/section}
	</select><br /><br /><br />




	<div>
	Message Title: <input type="text" name="msg_title" size="40" value="{$forward_title}" maxlength="80"><br />
	<textarea rows="10" cols="60" name="msg_body">{$forward_msg}</textarea><br />
	<input type="submit" name="send_forward" value="Forward">
	</div>
	</form>
{/if}

<div id="show_mess">
{if $view!=""}
	<table class="inputtable">
	<tr><th class="acenter">Viewing Message</th></tr><tr><td>
	{if $sent==0}
	  <form method="post" action="{$main}?messages{$authstr}">

	  <input type="hidden" name="msg_id" value="{$vmessage.id}">
	  <input type="hidden" name="msg_src" value="{$vmessage.src}">
	  <input type="hidden" name="msg_src_name" value="{$vsrc.empire}">
	  <input type="hidden" name="msg_title" value="{$vmessage.title}">
	  <input type="hidden" name="msg_body" value="{$vmessage.msg_escaped}">
	  <table>
	  <tr>
	  <td class="aleft">
	   <b>From:</b> {$vsrc.empire} <a href=?profiles&amp;num={$vsrc.num}{$authstr}>(#{$vsrc.num})</a><br />
	   <b>Subject:</b> {$vmessage.title}<br />
	   <b>Date:</b> {$time}<br />
	   <br />
	   <tt>{$vmessage.msg}</tt>
	  </td>
	  </tr>

	  <tr><td><div align="center">
	  <input type="submit" name="do_reply" value="Reply">
	  <input type="submit" name="do_forward" value="Forward">
	  <input type="submit" name="do_delete" value="Delete">
	  </div></td></tr>
	  </table>

	  </form>
	{/if}

	{if $sent==1}
		  <form method="post" action="{$main}?messages{$authstr}">
		  <input type="hidden" name="msg_body" value="{$smessage.msg_escaped}">
		  <input type="hidden" name="msg_id" value="{$smessage.id}">
		  <input type="hidden" name="msg_src_name" value="{$vsrc.empire}">
		  <input type="hidden" name="msg_src" value="{$smessage.src}">
	  	<input type="hidden" name="msg_title" value="{$smessage.title}">
		  <table>
		  <tr>
		  <td class="aleft">
		   <b>To:</b> {$vsrc.empire} <a href=?profiles&amp;num={$vsrc.num}{$authstr}>(#{$vsrc.num})</a><br />
		   <b>Subject:</b> {$smessage.title}<br />
		   <b>Date:</b> {$time}<br />
		   <br />
		   <tt>{$smessage.msg}</tt>
		  </td>
		  </tr>

		  <tr><td><div align="center">
		  {if $smessage.deleted == 0 or $smessage.deleted == 2}
		  		  <input type="submit" name="do_revoke" value="Revoke">
		  {/if}
		  <input type="submit" name="do_forward" value="Forward">
		  <input type="submit" name="do_delete" value="Delete"></div></td></tr>
		  </table>
	  </form>
	{/if}
	</td></tr><tr><th><hr></th></tr></table>
{/if}
</div>

{if $sent==0 && $prof_target==""}
	{if $num_msg>0}

		<form name="messages" method="post" action="{$main}?messages{$authstr}"> {* I want access to all the elements therein *}

		<table class="scorestable">
		<tr class="era{$color}"><th colspan="4">{$inboxname}</th></tr>

		<tr class="era{$color}">
		<th style="width:30%">
		<a href="{$main}?messages&amp;order_by=title&amp;asc={$title_order}{$authstr}">Title</a>
		</th>

		<th style="width:30%">
		<a href="{$main}?messages&amp;order_by=from&amp;asc={$from_order}{$authstr}">From</a>
		</th>


        <th style="width:15%">
        <a href="{$main}?messages&amp;order_by=date&amp;asc={$date_order}{$authstr}"> Date</a>
        </th>

        <th style="width:15%">
        <input name="all" type="checkbox" value="Check All" onClick="CheckAll(1);">
        </th>

        </tr>
		{section name=sel loop=$message}
				<tr>
				{if $message[sel].read==0}
					<td><a style="font-weight: bold;" id="link_{$message[sel].id}" href="{$main}?messages&amp;view={$message[sel].id}{$authstr}" onclick="show({$message[sel].id}); return false;">{$message[sel].title}</a></td>
					{*<td><a class="new" href="{$main}?messages&amp;view={$message[sel].id}{$authstr}">{$message[sel].title}</a></td>*}
				{/if}
				{if $message[sel].read!=0}
					{*<td><a href="JavaScript:show({$message[sel].id}){$authstr}">{$message[sel].title}</a></td>*}
					<td><a href="{$main}?messages&amp;view={$message[sel].id}{$authstr}">{$message[sel].title}</a></td>
				{/if}
				<td class="acenter">{$message[sel].from_name} <a href=?profiles&amp;num={$message[sel].from_num}{$authstr}>(#{$message[sel].from_num})</a></td>
				<td class="acenter">{$message[sel].date}</td>
				<td class="acenter"><input type="checkbox" name="boxes[]" value="{$message[sel].id}"></td>
				</tr>

		{/section}

				<tr class="era{$color}">

				<th style="width:30%">
				<a href="{$main}?messages&amp;order_by=title&amp;asc={$title_order}{$authstr}">Title</a>
				</th>

				<th style="width:30%">
				<a href="{$main}?messages&amp;order_by=from&amp;asc={$from_order}{$authstr}">From</a>
				</th>


		        <th style="width:15%">
		        <a href="{$main}?messages&amp;order_by=date&amp;asc={$date_order}{$authstr}"> Date</a>
		        </th>

		        <th style="width:15%">
		        <input name="all2" type="checkbox" value="Check All" onClick="CheckAll(2);">
		        </th>

        </tr>


		</table>

		<div>
		<br /><br />
		<input type="hidden" name="jsenabled">
		{literal}
		<input type="submit" name="do_deleteall" value="Delete all messages" onClick="
			temp = window.confirm('Are you sure you want to delete all messages?');
			if(temp == 1) {
				document.forms.messages.jsenabled.value = 'jsenabled';
				document.forms.messages.submit();
			}

			else
				return false;
		">
		{/literal}
		<input type="submit" name="do_delete_selected" value="Delete selected">
		<input type="submit" name="do_delete_read" value="Delete read messages">
		</div>
		</form>
	{/if}

	{if $num_msg==0}
		No new messages...<hr>
	{/if}
{/if}

{* I've decided to make it so that you only have one template... *}


{if $sent!=0}
	{if $num_msg>0}
		<form name="messages" method="post" action="{$main}?sentmail{$authstr}">

		<table class="scorestable">
		<tr class="era{$color}"><th colspan="4">{$sentname}</th></tr>

		<tr class="era{$color}">
		<th style="width:30%">
		<a href="{$main}?sentmail&amp;order_by=title&amp;asc={$title_order}{$authstr}">Title</a>
		</th>

		<th style="width:30%">
		<a href="{$main}?sentmail&amp;order_by=to&amp;asc={$to_order}{$authstr}">To</a>
		</th>


        <th style="width:15%">
        <a href="{$main}?sentmail&amp;order_by=date&amp;asc={$date_order}{$authstr}"> Date</a>
        </th>

        <th style="width:15%">
        <input name="all" type="checkbox" value="Check All" onClick="CheckAll(1);">
        </th>

        </tr>

		{section name=s_sel loop=$sent_message}
				<tr>
				<td> <a href="{$main}?sentmail&amp;view={$sent_message[s_sel].id}{$authstr}">{$sent_message[s_sel].title}</a></td>
				<td class="acenter"><a href="{$main}?profiles&amp;num={$sent_message[s_sel].to_num}{$authstr}"> {$sent_message[s_sel].to_name} <a href=?profiles&amp;num={$sent_message[s_sel].to_num}{$authstr}>(#{$sent_message[s_sel].to_num})</a> </td>
				<td class="acenter">{$sent_message[s_sel].date}</td>
				<td class="acenter"><input type="checkbox" name="boxes[]" value="{$sent_message[s_sel].id}"></td>
				 </td></tr>

		{/section}

			<tr class="era{$color}">

			<th style="width:30%">
			<a href="{$main}?messages&amp;order_by=title&amp;asc={$title_order}{$authstr}">Title</a>
			</th>

			<th style="width:30%">
			<a href="{$main}?messages&amp;order_by=to&amp;asc={$to_order}{$authstr}">To</a>
			</th>


	        <th style="width:15%">
	        <a href="{$main}?messages&amp;order_by=date&amp;asc={$date_order}{$authstr}"> Date</a>
	        </th>

	        <th style="width:15%">
	        <input name="all2" type="checkbox" value="Check All" onClick="CheckAll(2);">
	        </th>

        </tr>


		</table>

		<div>
		<br /><br />
		<input type="submit" name="do_deleteall" value="Delete all messages">
		<input type="submit" name="do_delete_selected" value="Delete selected">
		</div>
		</form>
	{/if}

	{if $num_msg==0}
		No sent messages...<hr>
	{/if}

{/if}

<div style='display:none'><iframe src='about:blank' width='0' height='0' id='theframe'></iframe></div>



{if $sent==0}

{literal}
<script language="JavaScript">

function updateMsgNames() {
	msgnum = document.frmmsg.msg_dest_num.value;
	nchanged = true
	for (i = 0; i < document.frmmsg.msg_dest.options.length; i++) {
		 if (document.frmmsg.msg_dest.options[i].value == msgnum) {
			document.frmmsg.msg_dest.options[i].selected = true;
			nchanged = false;
		}
	}
	if (nchanged) {
		document.frmmsg.do_message.disabled = true;
	} else {
		document.frmmsg.do_message.disabled = false;
	}
}
function updateMsgNums() {
	document.frmmsg.msg_dest_num.value = document.frmmsg.msg_dest.value;
		document.frmmsg.do_message.disabled = false;
}
</script>
{/literal}

We currently have {$msgcreds} message credits remaining.<br /><br />
<form method="post" action="{$main}?messages{$authstr}" name="frmmsg">
<div>

{if $clan != 0}
	<INPUT TYPE="checkbox" NAME="allclan"> Message everyone in clan?
	<br />(If the above box is checked, the below field will be ignored.)<br />

	We support bbcode, smilies, and automatic URL conversions.<br /><br />
{/if}

Send a message to: <input type="text" value="{$prof_target}" name="msg_dest_num" size="3" maxlength="4" onChange="updateMsgNames()">
<select name="msg_dest" onClick="updateMsgNums()" class="dkbg">
	{section name=dropsel loop=$drop}
		<option value="{$drop[dropsel].num}" class="m{$drop[dropsel].color}"{if $prof_target == $drop[dropsel].num} selected {/if}>{$drop[dropsel].num} - {$drop[dropsel].name}</option>
	{/section}
</select>

<br />
Message Title: <input type="text" name="msg_title" size="40" maxlength="80">
<br />
<textarea rows="15" cols="60" name="msg_body"></textarea><br />
<input type="submit" name="do_message" value="Send Message">
</div>
</form>

{/if}




