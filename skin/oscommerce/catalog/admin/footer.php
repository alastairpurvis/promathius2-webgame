<?php
/*
  $Id: footer.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require(DIR_WS_INCLUDES . 'counter.php');
?>
                <tr>
                    <td style="padding-top:20px">
                    <table cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td width="11"><img src="images/footerLeft.gif" /></td>
                            <td style="background: url('images/footerBg.gif'); text-align: center;">
							<table cellspacing="0" cellpadding="0" width="96%" align=center>
							<td align=center>
                            	<div class="header" style="margin-top: 5px"><a href="<? echo tep_href_link(FILENAME_PRIVACY); ?>">Privacy Policy</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_LEGAL); ?>">Legal Information</a></div>
                            	<div class="footer">Copyright &copy; 2009 SKIN NUTRITION. All rights reserved.</a></div>
                          	</td>
                            </table>
                            </td>
                            <td width="11"><img src="images/footerRight.gif" /></td>

                        </tr>
                    </table>
                    </td>
                </tr>
                <tr>
                    <td><img src="images/footerShadow.gif" /></td>
                </tr>
                <tr>
                    <td><img src="images/pxl-trans.gif" width="1" height="10" /></td>

                </tr>
            </table>
            </td>
        </tr>
        </form>
    </table>
</div></div>
<?php

?>
