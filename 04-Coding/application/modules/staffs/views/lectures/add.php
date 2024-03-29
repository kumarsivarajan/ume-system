<form class="form-horizontal" role="form" method="post" action="<?php echo site_url(); ?>staffs/lectures/add">
	<div class="toolbar col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
		<div class="left">
			<!--For icon: http://getbootstrap.com/components/-->
			<a href="<?php echo site_url(); ?>staffs/lectures/index/<?php echo $this->uri->segment(4); ?>" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Back</a>
			<button type="submit" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-ok-circle"></i> Save</button>
			<button type="reset" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-ban-circle"></i> Reset</button>
		</div>
		<div class="right">
			<h1><?php echo $title; ?></h1>
		</div>
	</div>
	<div class="content">
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Lecture account</h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label for="sta_card_id" class="col-sm-2 control-label">Card ID</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="sta_card_id" placeholder="Card ID" name="sta_card_id" value="<?php echo set_value('sta_card_id'); ?>"  pattern=".{5}" required title="Allow enter fixed 5 characters">
								<?php echo form_error('sta_card_id'); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="sta_name" class="col-sm-2 control-label">Name in Latin</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="sta_name" placeholder="Name in latin" name="sta_name" value="<?php echo set_value('sta_name'); ?>"  pattern=".{3,50}" required title="Allow enter from 3 to 50 characters">
								<?php echo form_error('sta_name'); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="sta_name_kh" class="col-sm-2 control-label">Name in Khmer</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="sta_name_kh" placeholder="Name in khmer" name="sta_name_kh" value="<?php echo set_value('sta_name_kh'); ?>"  pattern=".{3,50}" required title="Allow enter from 3 to 50 characters">
								<?php echo form_error('sta_name_kh'); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="sta_email" class="col-sm-2 control-label">Email</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="sta_email" placeholder="Email" name="sta_email" value="<?php echo set_value('sta_email'); ?>"  pattern=".{6,50}" title="Allow enter from 6 to 50 characters">
								<?php echo form_error('sta_email'); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="sta_sex" class="col-sm-2 control-label">Sex</label>
							<div class="col-sm-6">
								<?php
								echo form_dropdown('sta_sex', array('m' => 'Male', 'f' => 'Female'), set_value('sta_sex'), 'id="sta_sex" class="form-control"');
								?>
							</div>
						</div>
						<div class="form-group">
							<label for="st_address" class="col-sm-2 control-label">Address</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="sta_address" placeholder="Address" name="sta_address" value="<?php echo set_value('sta_address'); ?>"  pattern=".{6,200}" title="Allow enter from 6 to 200 characters" />
								<?php echo form_error('sta_address'); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="use_status" class="col-sm-2 control-label">Enable</label>
							<div class="col-sm-6">
								<div class="checkbox">
									<label><input type="checkbox" name="sta_status" id="use_status" value="1" <?php echo set_checkbox('sta_status', 1, TRUE); ?>> Check to enable this lecture account</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>