
<br class="spacer" />

<table width="100%" cellpadding="4" cellspacing="0" class="forumline">
<thead>
	<caption><table cellspacing="0" cellpadding="0" width="100%" class="forumheader">
	<tr>
		<td align="center" class="forumheader-mid">{L_PENDING_MEMBERS}</td>
	</tr></table></caption>
</thead>
<tbody>
	<tr> 
	  <th class="forum" class="thCornerL" height="25">{L_PM}</th>
	  <th class="forum" class="thTop">{L_USERNAME}</th>
	  <th class="forum" class="thTop">{L_POSTS}</th>
	  <th class="forum" class="thTop">{L_FROM}</th>
	  <th class="forum" class="thTop">{L_EMAIL}</th>
	  <th class="forum" class="thTop">{L_WEBSITE}</th>
	  <th class="forum" class="thCornerR">{L_SELECT}</th>
	</tr>
	<!-- BEGIN pending_members_row -->
	<tr> 
	  <td class="{pending_members_row.ROW_CLASS}" align="center">&nbsp;{pending_members_row.PM_IMG}&nbsp;</td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen"><a href="{pending_members_row.U_VIEWPROFILE}" class="gen">{pending_members_row.USERNAME}</a></span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen">{pending_members_row.POSTS}</span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen">{pending_members_row.FROM}</span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen">&nbsp;{pending_members_row.EMAIL_IMG}&nbsp;</span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="gen">&nbsp;{pending_members_row.WWW_IMG}&nbsp;</span></td>
	  <td class="{pending_members_row.ROW_CLASS}" align="center"><span class="chstyled"><input type="checkbox" name="pending_members[]" value="{pending_members_row.USER_ID}" checked="checked" /></span></td>
	</tr>
	<!-- END pending_members_row -->
	<tr> 
	  <td class="cat" colspan="8" align="right"><span class="cattitle"> 
		<input type="submit" name="approve" value="{L_APPROVE_SELECTED}" class="mainoption" />
		&nbsp; 
		<input type="submit" name="deny" value="{L_DENY_SELECTED}" class="liteoption" />
		</span></td>
	</tr>
</tbody>
</table>
