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
#dump table tr td, #dump table tr th{ border: 1px solid #ccc; }
</style>

</head>

<body>
	<table  class="wraper" border="0">
		<?php include('menu2.php'); ?>
		<tr width="100%">
			<?php include('left_sidebar_cassandra_workflow.php'); ?>
			<td  width="80%" height="505" valign="top" class="td_m" > 
				<h3>Workflow</h3>
				
				<h5>1. Actual Methodology</h5>
				<p><img src="img/actual-methodology.png" alt="actual-methodology.png" style="width:100%;"/></p>
				
				<br/><br/>
				<h5>2. Export Data from Cassandra Table</h5>
				<p>Command :</p>
				<div class="consolebody">COPY record_of_transactions TO 'record_of_transactions.csv';</div>
				<p><img src="img/export-data.png" alt="export-data.png" style="width:100%;"/></p>
				
				<br/><br/>
				<h5>3. Make Transaction on table</h5>
				<p>Command :</p>
				<div class="consolebody">
				cqlsh:bank_keyspace> update record_of_transactions set is_loan_defaulter=true where cust_trans_id=4a847510-2d5b-11ea-8202-4ffd11dfcd72;<br/>
				cqlsh:bank_keyspace> update record_of_transactions set amount=0 where cust_trans_id=d426d510-2d5b-11ea-8202-4ffd11dfcd72;<br/>
				cqlsh:bank_keyspace> insert into record_of_transactions(cust_trans_id, customer_id, type_of_operation, amount, is_loan_defaulter, tans_date_time) values (now(), 'test009', 'FD', 300000, 75000, False, '2019-11-21 01:05');
				</div>
				<p><img src="img/make-transaction.png" alt="make-transaction.png" style="width:100%;"/></p>
				
				<br/><br/>
				<h5>4. Export SSTable log</h5>
				<p>Command :</p>
				<div class="consolebody">admin@nskdnd-455-001in:~/Desktop/data$ sstabledump /var/lib/cassandra/data/bank_keyspace/record_of_transactions-d7054fd02d4e11ea82024ffd11dfcd72/md-6-big-Data.db > event_dump_08012020;</div>
				<p><img src="img/export-sstable.png" alt="export-sstable.png" style="width:100%;"/></p>
				
				<br/><br/>
				<h5>5. Another Transaction (Delete)</h5>
				<p>Command :</p>
				<div class="consolebody">cqlsh:bank_keyspace> delete from record_of_transactions where cust_trans_id=64341f00-2d5c-11ea-4ffd11dfcd72;</div>
				<p><img src="img/delete-transaction.png" alt="delete-transaction.png" style="width:100%;"/></p>
				
				<br/><br/>
				<h5>6. Export SSTable log</h5>
				<p>Command :</p>
				<div class="consolebody">admin@nskdnd-455-001in:~/Desktop/data$ sstabledump /var/lib/cassandra/data/bank_keyspace/record_of_transactions-d7054fd02d4e11ea82024ffd11dfcd72/md-7-big-Data.db > event_dump_08012020;</div>
				<p><img src="img/export-sstable-2.png" alt="export-sstable-2.png" style="width:100%;"/></p>
				
				<br/><br/>
				
			</td>
		</tr>
		<tr class="tr_row">
			<td height="20" colspan="2" bgcolor="#9F6479" align="center"><span class="style11">Copyright &copy; 2019 College of Engineering, Pune</span></td>
		</tr>
	</table>
	
</body>
</html>
