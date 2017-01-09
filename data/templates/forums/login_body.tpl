<form action="{S_LOGIN_ACTION}" method="post" target="_top">

<table width="100%" cellpadding="4" cellspacing="0" class="forumline" align="center">
<thead>
	<caption>
	<table cellspacing="0" cellpadding="0" width="100%" class="forumheader">
		<tr>
			<td align="center" class="forumheader-mid">{L_ENTER_PASSWORD}</td>
		</tr>
	</table>
	</caption>
</thead>

<tbody>
  <tr> 
<td class="row1" width=50%>
	<table cellpadding="3" cellspacing="1" width="100%">
		<tr>
			<td>
			<span style="font-size: 10px">
			In order to login you must be registered. Registering takes only a few seconds but gives you increased capabilities. Before you login please ensure you are familiar with our terms of use and related policies. Please ensure you read any forum rules as you navigate around the board.</span></td>
		</tr>
	</table>
</td>
	<td class="row1"><table cellpadding="2" cellspacing="1" width="100%">
		  <tr> 
			<td colspan="2" align="center">&nbsp;</td>
		  </tr>
		  <tr> 
			<td width="30%" align="left" valign="top"><span class="gensmall"><b>{L_USERNAME}:</b></span></td>
			<td> 
				<input type="text" class="post" name="username" size="25" maxlength="40" value="{USERNAME}" tabindex="1"/>
				<br />
				<span class="gensmall"><a href="../register.php?mode=register" class="gentiny">Register</a></span></td>
		  </tr>
		  <tr> 
			<td align="left" valign=top><span class="gensmall"><b>{L_PASSWORD}:<b></span></td>
			<td> 
			  <input type="password" class="post" name="password" size="25" maxlength="32" tabindex="2"/><br />
			<span class="gensmall"><a href="{U_SEND_PASSWORD}" class="gentiny">{L_SEND_PASSWORD}</a></span></td>
		  
		  </tr>
		<!-- BEGIN switch_allow_autologin -->
		  <tr> 
		  <td>
			<td colspan="1"><table cellspacing="0" cellpadding="0"><tr><td>
<td><span class="cbstyled"><input type="checkbox" name="autologin" tabindex="3"/></td><td><span class="gensmall">&nbsp;{L_AUTO_LOGIN}&nbsp;</span></td></tr></table></td>
		  </tr>
		<!-- END switch_allow_autologin -->

		</table></td>
	<tr>
		<td class="catBottom" colspan="2" align="center" height="28">{S_HIDDEN_FIELDS}<input type="submit" name="login" tabindex="4" class="mainoption" value="{L_LOGIN}"/></td>
	</tr>
  </tr>
 </tbody>
</table>

</form>

