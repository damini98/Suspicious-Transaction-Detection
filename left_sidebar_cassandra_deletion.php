<td width="20%" style="position: absolute;padding-left: 50px;padding-top: 35px;"> 
	<ul>
		<li><a href="#">Cassandra and Data Deletion</a></li> 
		<li class="<?php  if($action=="create"){ echo "active"; } ?>"><a href="?action=create">1. Create Table</a></li> 
		<li class="<?php  if($action=="insert"){ echo "active"; } ?>"><a href="?action=insert">2. Insert Data</a></li> 
		<li class="<?php  if($action=="show"){ echo "active"; } ?>"><a href="?action=show">3. Show Data</a></li> 
		<li class="<?php  if($action=="delete"){ echo "active"; } ?>"><a href="?action=delete">4. Delete Data</a></li> 
		<?php /* <li class="<?php  if($action=="download"){ echo "active"; } ?>"><a href="?action=download">Download Recovery File</a></li> */ ?>
		<li class="<?php  if($action=="recover"){ echo "active"; } ?>"><a href="?action=recover">5. Recover Data</a></li> 
	</ul>
</td>