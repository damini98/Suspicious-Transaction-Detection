 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forensics Analysis</title>
<link rel="stylesheet" type="text/css" href="css/style.css">

<style>
ul li.active{ background: #6b3447; }
.consolebody{ background: #000; color: #fff; padding: 10px; font-size:12px; }
.consolebody p{ margin: 5px 0px; padding: 5px; }
.hide{ display: none; }
.next-step{ display: inline !important; padding: 5px 30px; border: 1px solid darkgreen; border-radius: 4px; -webkit-box-shadow: inset 1px 6px 12px lightgreen, inset -1px -10px 5px darkgreen, 1px 2px 1px black; -moz-box-shadow: inset 1px 6px 12px lightgreen, inset -1px -10px 5px darkgreen, 1px 2px 1px black; box-shadow: inset 1px 6px 12px lightgreen, inset -1px -10px 5px  darkgreen, 1px 2px 1px black; background-color: green; color: white; text-shadow: 1px 1px 1px black; cursor: pointer; }
</style>

</head>

<?php $action = isset($_GET['action']) ? $_GET['action'] : ""; ?>

<body>
	<table  class="wraper" border="0">
		<?php include('menu2.php'); ?>
		<tr width="100%">
			<?php include('left_sidebar_cassandra_deletion.php'); ?>
			<td  width="80%" height="505" valign="top" class="td_m" > 
				<h3>Backing Up and Restoring Data</h3>
				
				<!-- create table action -->
				<?php if($action == 'create'){ ?>
				<div id="create">
					<button id="btnCreate">Create Table</button>
					<div class="showAnimation hide">
						<p>1. Connecting . . .</p>
						<div class="consolebody">$ cqlsh	Connected to Test Cluster at 127.0.0.1:9042.<br/>
						[cqlsh 5.0.1 | Cassandra 3.11.4 | CQL spec 3.4.4 | Native protocol v4]<br/>
						Use HELP for help.</div>
						<p>Connection established.</p>
						<p>2. Creating a Keyspace using Cqlsh . . .</p>
						<div class="consolebody">
							cqlsh> CREATE KEYSPACE test_sample WITH replication = {'class': 'SimpleStrategy', 'replication_factor': '3'} AND durable_writes = true;
						</div>
						<p>3. Creating a Table . . .</p>
						<div class="consolebody">
							cqlsh> USE test_sample;
						</div>
						<p></p>
						<div class="consolebody">
							cqlsh:test_sample> CREATE Table student(id uuid,description varchar,name varchar,rollno int, PRIMARY KEY(id));
						</div>
						<p>Table `student` created successfully.</p>
						
						<a href="?action=insert"><button class="next-step">Next Step >> </button></a>
						
						<br/><br/>	
					</div>
					
				</div>
				<?php } ?>
				
				<!-- insert data action -->
				<?php if($action == 'insert'){ ?>
				<div id="insert">
					<button id="btnCreate">Insert Data</button>
					<div class="showAnimation hide">
						<p>1. Inserting record . . .</p>
						<div class="consolebody">cqlsh:test_sample> insert into student (id, description ,name, rollno) values (now(),'Actor','Amitab bacchan', 251);</div>
						<p>Record inserted.</p>
						<p>2. Inserting record . . .</p>
						<div class="consolebody">cqlsh:test_sample> insert into student (id,name,description) values (now(),'Politician','Narendra Modi', 254);</div>
						<p>Record inserted.</p>
						<p>3. Inserting record . . .</p>
						<div class="consolebody">cqlsh:test_sample> insert into student (id,name,description) values (now(),'Cricketer','Rahul Dravid', 250);</div>
						<p>Record inserted.</p>
						
						<a href="?action=show"><button class="next-step">Next Step >> </button></a>
						
						<br/><br/>	
					</div>
				</div>
				<?php } ?>
				
				<!-- show data action -->
				<?php if($action == 'show'){ ?>
				<div id="show">
					<button id="btnCreate">Show Data</button>
					<div class="showAnimation hide">
						<p>1. Fetching records . . .</p>
						<div class="consolebody">cqlsh:test_sample> select * from student;</div>
						<p>Found following records</p>
						
						<div class="consolebody">
						id &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&nbsp;
						| description &emsp;&nbsp;&nbsp;| name &emsp;&emsp;&emsp;&emsp;| rollno<br/>
						--------------------------------------------------+----------------+------------------------<br/>2c848360-f0cb-11e9-893e-8d83811a3e5a &emsp;&nbsp;| Actor &emsp;&emsp;&emsp;&emsp;| Amitab bacchan | 251<br/>
						5e1969b0-f18c-11e9-8bbd-81ce107785f7 &emsp;&nbsp;| Politician&emsp;&emsp;&emsp;| Narendra Modi | 254<br/>
						e5738ed0-f0c5-11e9-893e-8d83811a3e5a &emsp;| Cricketer &emsp;&emsp;&emsp;| Rahul Dravid &emsp;| 250<br/><br/>
						(3 rows)
						</div>
						
						<br/>
						<a href="?action=delete"><button class="next-step">Next Step >> </button></a>
						
						<br/><br/>	
					</div>
					
				</div>
				<?php } ?>
				
				<!-- delete data action -->
				<?php if($action == 'delete'){ ?>
				<div id="delete">
					<button id="btnCreate">Delete Data</button>
					<div class="showAnimation hide">
						<p>1. Fetching records . . .</p>
						<div class="consolebody">cqlsh:test_sample> select * from student;</div>
						<p>Found following records</p>
						
						<div class="consolebody">
						id &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&nbsp;
						| description &emsp;&nbsp;&nbsp;| name &emsp;&emsp;&emsp;&emsp;| rollno<br/>
						--------------------------------------------------+----------------+------------------------<br/>2c848360-f0cb-11e9-893e-8d83811a3e5a &emsp;&nbsp;| Actor &emsp;&emsp;&emsp;&emsp;| Amitab bacchan | 251<br/>
						5e1969b0-f18c-11e9-8bbd-81ce107785f7 &emsp;&nbsp;| Politician&emsp;&emsp;&emsp;| Narendra Modi | 254<br/>
						e5738ed0-f0c5-11e9-893e-8d83811a3e5a &emsp;| Cricketer &emsp;&emsp;&emsp;| Rahul Dravid &emsp;| 250<br/><br/>
						(3 rows)
						</div>
						<p>2. Deleting record . . .</p>
						<div class="consolebody">cqlsh:test_sample> delete from student where id=5e1969b0-f18c-11e9-8bbd-81ce107785f7
						</div>
						<p>Record Deleted.</p>
						<p>3. Fetching updated records . . .</p>
						<div class="consolebody">cqlsh:test_sample> select * from student;</div>
						<p></p>
						<div class="consolebody">
						id &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&nbsp;
						| description &emsp;&nbsp;&nbsp;| name &emsp;&emsp;&emsp;&emsp;| rollno<br/>
						--------------------------------------------------+----------------+------------------------<br/>2c848360-f0cb-11e9-893e-8d83811a3e5a &emsp;&nbsp;| Actor &emsp;&emsp;&emsp;&emsp;| Amitab bacchan | 251<br/>
						e5738ed0-f0c5-11e9-893e-8d83811a3e5a &emsp;| Cricketer &emsp;&emsp;&emsp;| Rahul Dravid &emsp;| 250<br/><br/>
						(2 rows)
						</div>
						<br/>
						<p>4. Read sstable command (sstabledump)</p>
						<p>Reading sstable . . . </p>
						<div class="consolebody">sstabledump /var/lib/cassandra/data/test_sample/student-658ffeb0f0c511e9893e8d83811a3e5a/md-1-big-Data.db > eventlog_dump_2019oct25</div>
						<p></p>
						<div class="consolebody">
						<pre class="lang-js prettyprint prettyprinted" style=""><code><span>[</span>
  <span>{</span>
    <span>"partition" : {</span>
      <span>"key" : [ "1df47c60-f0cb-11e9-893e-8d83811a3e5a" ],</span>
      <span>"position" : 0,</span>
      <span>"deletion_info" : { <span>
	   <span>"marked_deleted" : "2019-10-17T10:46:22.137377Z",</span> 
	   <span>"local_delete_time" : "2019-10-17T10:46:22Z"</span> 
      <span>}</span>
    <span>},</span>
    <span>"rows" : [ ]</span>
 <span>},</span>
 <span>{</span>
    <span>"partition" : {</span>
      <span>"key" : [ "2c848360-f0cb-11e9-893e-8d83811a3e5a" ],</span>
      <span>"position" : 31</span>
    <span>},</span>
    <span>"rows" : [</span>
      <span>{</span>
	  <span>"type" : "row",</span>
	  <span>"position" : 97,</span>
	  <span>"liveness_info" : { </span>
		<span>"tstamp" : "2019-10-17T10:45:02.740880Z"</span>
	   <span>},</span>
	   <span>"cells" : [</span>
	      <span>{ </span>
		  <span>"name" : "description",</span> 
		  <span>"value" : "Actor"</span>
	      <span>},</span>
	      <span>{ </span>
		  <span>"name" : "name",</span>
		  <span>"value" : "Amitab bacchan"</span> 
	      <span>},</span>
	      <span>{ </span>
		  <span>"name" : "rollno", </span>
		  <span>"value" : 251 </span>
	      <span>}</span>
	    <span>]</span>
	<span>}</span>
     <span>]</span>
  <span>},</span>
  <span>{</span>
     <span>"partition" : {</span>
       <span>"key" : [ "e5738ed0-f0c5-11e9-893e-8d83811a3e5a" ],</span>
       <span>"position" : 98</span>
      <span>},</span>
      <span>"rows" : [</span>
	 <span>{</span>
	    <span>"type" : "row",</span>
	    <span>"position" : 162,</span>
	    <span>"liveness_info" : {</span> 
		<span>"tstamp" : "2019-10-17T10:07:16.026234Z"</span>
	     <span>},</span> 
	    <span>"cells" : [</span>
		<span>{</span> 
		<span>"name" : "description",</span> 
		<span>"value" : "Cricketer"</span> 
		<span>},</span>
		<span>{</span> 
		<span>"name" : "name",</span>
		<span>"value" : "Rahul Dravid"</span> 
		<span>},</span>
		<span>{</span> 
		<span>"name" : "rollno",</span> 
		<span>"value" : 250</span> 
		<span>}</span>
	    <span>]</span>
	<span>}</span>
    <span>]</span>
  <span>}</span>
<span>]</span></code></pre></div>
					<br/>
					<br/>
					</div>
				</div>
				<?php } ?>
				
				<!-- recover data action -->
				<?php if($action == 'recover'){ ?>
				<div id="delete">
					<button id="btnCreate">Recover Data</button>
					<div class="showAnimation hide">
						<p>1. marked deleted record</p>
						<div class="consolebody">sstabledump /var/lib/cassandra/data/test_sample/student-658ffeb0f0c511e9893e8d83811a3e5a/md-1-big-Data.db > eventlog_dump_2019oct25</div>
						<p></p>
						<div class="consolebody">
						<pre class="lang-js prettyprint prettyprinted" style=""><code><span>[</span>
  <span>{</span>
    <span>"partition" : {</span>
      <span>"key" : [ "1df47c60-f0cb-11e9-893e-8d83811a3e5a" ],</span>
      <span>"position" : 0,</span>
      <span>"deletion_info" : { <span>
	   <span>"marked_deleted" : "2019-10-17T10:46:22.137377Z",</span> 
	   <span>"local_delete_time" : "2019-10-17T10:46:22Z"</span> 
      <span>}</span>
    <span>},</span>
    <span>"rows" : [ ]</span>
 <span>},</span>
 <span>.</span>
 <span>.</span>
 <span>.</span>
<span>]</span></code></pre></div>
						<p>2. Deleted Record</p>
						<div class="consolebody">
						<pre class="lang-js prettyprint prettyprinted" style=""><code><span>[</span>
  <span>{</span>
    <span>"partition" : {</span>
      <span>"key" : [ "5e1969b0-f18c-11e9-8bbd-81ce107785f7" ],</span>
      <span>"position" : 0,</span>
    <span>},</span>
    <span>"rows" : [</span>
	<span>{</span>
	  <span>"type" : "row",</span>
	  <span>"position" : 66,</span>
	  <span>"liveness_info" : { </span>
		<span>"tstamp" : "2019-10-18T09:47:58.790803Z"</span>
	   <span>},</span>
	   <span>"cells" : [</span>
	      <span>{ </span>
		  <span>"name" : "Politician",</span> 
		  <span>"value" : "Actor"</span>
	      <span>},</span>
	      <span>{ </span>
		  <span>"name" : "name",</span>
		  <span>"value" : "Narendra Modi"</span> 
	      <span>},</span>
	      <span>{ </span>
		  <span>"name" : "rollno", </span>
		  <span>"value" : 254  </span>
	      <span>}</span>
	    <span>]</span>
	<span>}</span>
  <span>}</span>
<span>]</span></code></pre></div>
						<p>Recovery of data is in progress . . .</p>
						<p>Uploading recovery file . . .</p>
						<p>Recovered deleted record . . .</p>
						<p>Fetching current records from table . . .</p>
						<div class="consolebody">cqlsh:test_sample&gt; select * from student;</div>
						<br/>
						<div class="consolebody">
						id &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&nbsp;
						| description &emsp;&nbsp;&nbsp;| name &emsp;&emsp;&emsp;&emsp;| rollno<br/>
						--------------------------------------------------+----------------+------------------------<br/>2c848360-f0cb-11e9-893e-8d83811a3e5a &emsp;&nbsp;| Actor &emsp;&emsp;&emsp;&emsp;| Amitab bacchan | 251<br/>
						5e1969b0-f18c-11e9-8bbd-81ce107785f7 &emsp;&nbsp;| Politician&emsp;&emsp;&emsp;| Narendra Modi | 254<br/>
						e5738ed0-f0c5-11e9-893e-8d83811a3e5a &emsp;| Cricketer &emsp;&emsp;&emsp;| Rahul Dravid &emsp;| 250<br/><br/>
						(3 rows)
						</div>
						
					<br/>
					<br/>
					</div>
				</div>
				<?php } ?>
				
				
			</td>
		</tr>
		<tr class="tr_row">
			<td height="20" colspan="2" bgcolor="#9F6479" align="center"><span class="style11">Copyright &copy; 2019 College of Engineering, Pune</span></td>
		</tr>
	</table>
	
	<script src="jquery-3.2.1.min.js"></script>
	<script>
		$(function(){
			$('#btnCreate').click(function(){
				$('.showAnimation').removeClass('hide');
				cssTyping('.showAnimation');
			});
		});
		
		function cssTyping(element){
			var children = $(element).children();
			$(element).html('');
			$(children).hide().appendTo(element).each(function (i) {
				$(this).delay(1000 * i).css({
					display: 'block',
					opacity: 0
				}).animate({
					opacity: 1
				}, 100);
				$('html, body').animate({
					scrollTop: $('.showAnimation').height()
				}, 1000);
			});
		}
	</script>
	
</body>
</html>
