<?php

    class profileTemplate extends template {
        
		public $online = "<strong class=\"text-success\">Online</strong>";
		public $offline = "<strong class=\"text-danger\">Offline</strong>";
		public $AFK = "<strong class=\"text-warning\">AFK</strong>";
		
		public $editProfile = '
			<form action="#" method="post">
				<div class="row">
					<div class="col-md-3 text-right">
						<strong>Bio</strong>
					</div>
					<div class="col-md-9">
						<textarea rows="5" name="bio" class="form-control" placeholder="A little bio about yourself ...">{bio}</textarea>
					</div>
				</div><br />
				<div class="row">
					<div class="col-md-3 text-right">
						<strong>Picture</strong>
					</div>
					<div class="col-md-9">
						<input type="text" name="pic" class="form-control" value="{picture}" placeholder="e.g. http://www.someurl.com/picture.png" />
					</div>
				</div><br />
				<div class="row">
					<div class="col-md-12">
						<button type="submit" name="submit" value="true" class="btn pull-right">Update</button>
					</div>
				</div>
			</form>';
		
		public $profile = '
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<img src="{picture}" style="max-height: 150px; max-width: 300px;" class="img-rounded img-thumbnail" alt="{name}\'s Profile" />
				</div>
				<div class="col-md-8 col-lg-9">
					<table class="table table-borderless table-condensed">
						<tr>
							<th width="100px">Username</th>
							<td class="text-left">{name}</td>
						</tr>
						<tr>
							<th width="100px">Rank</th>
							<td class="text-left">{rank}</td>
						</tr>
						<tr>
							<th width="100px">Family</th>
							<td class="text-left">{family}</td>
						</tr>
						<tr>
							<th width="100px">Status</th>
							<td class="text-left">{status}</td>
						</tr>
						<tr>
							<th width="100px">Bio</th>
							<td class="text-left">{bio}</td>
						</tr>
					</table>
				</div>
			</div>

			{#if edit}
				<div class="row">
					<div class="col-md-12">
						<a href="?page=profile&action=edit" class="btn pull-right">Edit Profile</a>
					</div>
				</div>
			{/if}
		';
        
    }

?>