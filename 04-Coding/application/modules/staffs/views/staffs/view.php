<?php
$data->result_array();
$data = $data->result_array[0];
?>
<div class="toolbar col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
	<div class="left">
		<!--For icon: http://getbootstrap.com/components/-->
		<a href="<?php echo site_url(); ?>staffs/staffs/index/<?php echo $this->uri->segment(5); ?>" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Back</a>
		<a href="<?php echo site_url(); ?>staffs/staffs/add/<?php echo $this->uri->segment(5); ?>" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-plus-sign"></i> Create</a>
		<a href="<?php echo site_url(); ?>staffs/staffs/edit/<?php echo $this->uri->segment(4); ?>" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-plus-sign"></i> Edit</a>
	</div>
	<div class="right">
		<h1><?php echo $title; ?></h1>
	</div>
</div>
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">View Staff</h3>
		</div>
		<div class="panel-body">
			<dl class="dl-horizontal">
				<dt>Card ID</dt>
				<dd><?php echo $data['sta_card_id']; ?></dd>
				<dt>Name in Latin</dt>
				<dd><?php echo $data['sta_name']; ?></dd>
				<dt>Name in Khmer</dt>
				<dd><?php echo $data['sta_name_kh']; ?></dd>
				<dt>Sex</dt>
				<dd><?php echo strtoupper($data['sta_sex']); ?></dd>
				<dt>Position</dt>
				<dd><?php echo $data['sta_pos_title']; ?></dd>
				<dt>Job Type</dt>
				<dd><?php echo $data['sta_job_title']; ?></dd>
				<dt>Mobile Phone</dt>
				<dd><i class="glyphicon glyphicon-phone"></i> <?php echo $data['sta_phone']; ?></dd>
				<dt>Email</dt>
				<dd><i class="glyphicon glyphicon-envelope"></i> <?php echo $data['sta_email']; ?></dd>
				<dt>Address</dt>
				<dd><?php echo $data['sta_address']; ?></dd>
				<dt>Status</dt>
				<dd><?php echo status_string($data['sta_status']); ?></dd>
				<dt>Started Date</dt>
				<dd><i class="glyphicon glyphicon-calendar"></i> <?php echo get_date_string($data['sta_start_date']); ?></dd>
				<dt>Created</dt>
				<dd><i class="glyphicon glyphicon-calendar"></i> <?php echo get_date_string($data['sta_created']); ?></dd>
				<dt>Modified</dt>
				<dd><i class="glyphicon glyphicon-calendar"></i> <?php echo get_date_string($data['sta_modified']); ?></dd>
			</dl>
		</div>
	</div>
</div>