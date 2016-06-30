<?php

$table_name = 'table_name';
$rows 		= [];
$headers 	= [];
$row_count	= 0;

##set table name
if( isset($_POST['table_name']) && !empty($_POST['table_name']) )
	$table_name = $_POST['table_name'];

##get data
if( isset($_POST['data']) ){

	$_POST['data'] = trim($_POST['data']);

	##get data
	$data = explode("\n", $_POST['data']);
	
	foreach($data as $d)
		$rows[] = explode("\t", $d);
	
	if( count($rows) ){
		$headers = $rows[0];
		unset( $rows[0] ); //remove headers from data
	}
	
	$row_count = count($rows);
}

?><!DOCTYPE HTML>
<html lang="en-US">
<head>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Importer</title>
	
	<link rel="stylesheet" href="bootstrap.min.css" />
	<script type="text/javascript" src="jquery-3.0.0.min.js"></script>
	
	<style type="text/css">
		body{ background-color: #eaeaea; }
		
		.wrapper{ margin-top: 10px; }
		
		ul.insert_query li{ background-color: #eaeaea; }
		ul.insert_query li:hover{ background-color: #f5f5f5; }
		
		.bulk_insert_query{ background-color: #f5f5f5; }
	</style>
	
	<script type="text/javascript">
		//http://stackoverflow.com/questions/9975707/use-jquery-select-to-select-contents-of-a-div
	    jQuery.fn.selectText = function(){
			this.find('input').each(function() {
				if($(this).prev().length == 0 || !$(this).prev().hasClass('p_copy')) { 
					$('<p class="p_copy" style="position: absolute; z-index: -1;"></p>').insertBefore($(this));
				}
				$(this).prev().html($(this).val());
			});
			var doc = document;
			var element = this[0];
			//console.log(this, element);
			if (doc.body.createTextRange) {
				var range = document.body.createTextRange();
				range.moveToElementText(element);
				range.select();
			} else if (window.getSelection) {
				var selection = window.getSelection();        
				var range = document.createRange();
				range.selectNodeContents(element);
				selection.removeAllRanges();
				selection.addRange(range);
			}
		};	
	</script>
</head>
<body>
	
	<div class="wrapper">
		
		<div class="container">
			
			<form role="form" method="post">
				
				<!--[ Table Name]-->
				<div class="form-group">
					<label for="table_name">Table Name:</label>
					<input type="text" name="table_name" class="form-control" id="table_name" placeholder="Optional" value="<?php echo $table_name; ?>"/>
				</div>
				
				<!--[ Texarea ]-->
				<div class="form-group">
					<label for="data">Data:</label>
					<textarea name="data" id="data" rows="15" class="form-control"><?php echo isset($_POST['data']) ? $_POST['data'] : ''; ?></textarea>
				</div>
				
				<!--[ Show Table Checkbox ]-->
				<div class="checkbox">
					<label><input type="checkbox" name="show_table" <?php echo isset($_POST['show_table']) ? 'checked' : ''; ?> /> Show Table</label>
				</div>

				<!--[ Show Insert Query Checkbox ]-->
				<div class="checkbox">
					<label><input type="checkbox" name="show_insert_query" <?php echo isset($_POST['show_insert_query']) ? 'checked' : ''; ?> /> Show Insert Query(s)</label>
				</div>
				
				<!--[ Show Bulk Insert Query Checkbox ]-->
				<div class="checkbox">
					<label><input type="checkbox" name="show_bulk_insert_query" <?php echo isset($_POST['show_bulk_insert_query']) ? 'checked' : ''; ?> /> Show Bulk Insert Query(s)</label>
				</div>
				
				<!--[ BUTTONs ]-->
				<button type="submit" class="btn btn-default">Submit</button>
				<button type="reset" class="btn btn-danger pull-right" onclick="window.location = window.location.pathname;">Reset</button>
				
			</form>
			
			<hr />
			
			<?php if( isset($_POST['show_table']) ): ?>
				<!--[ TABLE ]-->
				<br />
				<br />
				<table class="table table-hover">
					<thead>
						<tr>
							<?php 
								foreach($headers as $header)
									echo '<th>'.$header.'</th>';
							?>
						</tr>
					</thead>
					<tbody>
						<?php foreach($rows as $row): ?>
							<tr>
								<?php 
									foreach($row as $r) 
										echo '<td>'.$r.'</td>';
								?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			
			<?php if( isset($_POST['show_insert_query']) ): ?>
				<!--[ INSERT QUERYs ]-->
				<br />
				<br />
				<a href="javascript:;" onclick="$('.insert_query').selectText();">Select all</a>
				<ul class="list-group insert_query">
					
					<?php foreach($rows as $r): ?>
						<li class="list-group-item">
							<?php echo 'INSERT INTO '.$table_name.' ('.implode(array_values($headers), ',').') VALUES ("'.implode(array_values($r), '","').'");'; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			
			<?php if( isset($_POST['show_bulk_insert_query']) ): ?>
				<!--[ BULK INSERT QUERYs ]-->
				<br />
				<br />
				<a href="javascript:;" onclick="$('.bulk_insert_query').selectText();">Select all</a>
				<div class="bulk_insert_query">
				
					<?php echo 'INSERT INTO '.$table_name.' ('.implode(array_values($headers), ',').') VALUES '; ?>
					
					<?php foreach($rows as $index => $r): ?>
						<div>
							<?php
								$last_character = ',';
								if( $index == $row_count )
									$last_character = ';';
								
								echo '("'.implode(array_values($r), '","').'")'.$last_character;
							?>
						</div>
					<?php endforeach; ?>
					
				</div>
			<?php endif; ?>
			
			
		</div><!--/.container -->

	</div><!--/.wrapper -->
	
</body>
</html>