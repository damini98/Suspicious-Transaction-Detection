<td width="20%" style="position: absolute;padding-left: 50px;padding-top: 35px;"> 
	<ul>
		<li><a href="#">Analyze And Restore</a></li> 
		<li class="<?php  if($action=="upload"){ echo "active"; } ?>"><a href="?action=upload">1. Upload Data</a></li> 
		<li class="<?php  if($action=="show"){ echo "active"; } ?>"><a href="?action=show">2. Current Data</a></li> 
		<li class="<?php  if($action=="dump"){ echo "active"; } ?>"><a href="?action=dump">3. Upload Log File</a></li>
		<li class="<?php  if($action=="show_dump"){ echo "active"; } ?>"><a href="?action=show_dump">4. Data of Log File</a></li>		
		<li class="<?php  if($action=="find"){ echo "active"; } ?>"><a href="?action=find">5. Find Suspicious Transactions</a></li> 
	</ul>
</td>