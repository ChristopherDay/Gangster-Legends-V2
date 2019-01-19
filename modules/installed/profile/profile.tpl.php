<?php

    class profileTemplate extends template {
        
		public $online = "<strong class=\"text-success\">Online</strong>";
		public $offline = "<strong class=\"text-danger\">Offline</strong>";
		public $AFK = "<strong class=\"text-warning\">AFK</strong>";

		public $editPassword = '


        	<div class="panel panel-primary">
                <div class="panel-heading">Edit Password</div>
                <div class="panel-body">

					<ul class="nav nav-tabs nav-justified">
						<li><a href="?page=profile&action=edit">Profile</a></li>
						<li class="active"><a href="?page=profile&action=password">Change Password</a></li>
					</ul>

					<form action="#" method="post">
						<div class="row">
							<div class="col-md-3 text-right">
								<strong>Old Password</strong>
							</div>
							<div class="col-md-9">
								<input type="password" name="old" class="form-control" value="" placeholder="******" />
							</div>
						</div><br />
						<div class="row">
							<div class="col-md-3 text-right">
								<strong>New Password</strong>
							</div>
							<div class="col-md-9">
								<input type="password" name="new" class="form-control" value="" placeholder="******" />
							</div>
						</div><br />
						<div class="row">
							<div class="col-md-3 text-right">
								<strong>Confirm Password</strong>
							</div>
							<div class="col-md-9">
								<input type="password" name="confirm" class="form-control" value="" placeholder="******" />
							</div>
						</div><br />
						<div class="row">
							<div class="col-md-12">
								<button type="submit" name="submit" value="true" class="btn">Update</button>
							</div>
						</div>
					</form>
                </div>
            </div>
			';

		public $editProfile = '

        	<div class="panel panel-primary">
                <div class="panel-heading">Edit Profile</div>
                <div class="panel-body">
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a href="?page=profile&action=edit">Profile</a></li>
						<li><a href="?page=profile&action=password">Change Password</a></li>
					</ul>
					<form action="#" method="post">
						<div class="row">
							<div class="col-md-3 text-right">
								<strong>Picture</strong>
							</div>
							<div class="col-md-9">
								<input type="text" name="pic" class="form-control" value="{picture}" placeholder="e.g. http://www.someurl.com/picture.png" />
							</div>
						</div><br />
						<div class="row">
							<div class="col-md-3 text-right">
								<strong>Bio</strong>
							</div>
							<div class="col-md-9">
								<textarea rows="15" name="bio" class="form-control" placeholder="A little bio about yourself ...">{bio}</textarea>
							</div>
						</div><br />
						<div class="row">
							<div class="col-md-12">
								<button type="submit" name="submit" value="true" class="btn">Update</button>
							</div>
						</div>
					</form>
                </div>
            </div>
			';
		
		public $profile = '

        	<div class="panel panel-primary">
                <div class="panel-heading">{user.name}\' Profile</div>
                <div class="panel-body">
					<div class="row">
						<div class="col-md-4 col-lg-3">
							<img src="{picture}" style="max-height: 150px; max-width: 100%;" class="img-rounded img-thumbnail" alt="{user.name}\'s Profile" />
						</div>
						<div class="col-md-8 col-lg-9">

							<table class="table table-borderless table-condensed">
								<tr>
									<th width="100px">Username</th>
									<td class="text-left">
										{>userName}
										<sup>(<{status}>)</sup>
									</td>
								</tr>
								<tr>
									<th width="100px">Status</th>
									<td class="text-left">
										{#if dead}
											<strong style="color: #900;">DEAD</strong>
										{/if}
										{#unless dead}
											<strong style="color: #090;">Alive</strong>
										{/unless}

									</td>
								</tr>
								{#if showRole}
									<tr>
										<th width="100px">Role</th>
										<td class="text-left">{role}</td>
									</tr>
								{/if}
								<tr>
									<th width="100px">Rank</th>
									<td class="text-left">{rank}</td>
								</tr>
								<tr>
									<th width="100px">Wealth</th>
									<td class="text-left">{moneyRank}</td>
								</tr>
								<tr>
									<th width="100px">Gang</th>
									<td class="text-left">
										<a href="?page=gang&action=view&id={gangID}">
											{gang}
										</a>
									</td>
								</tr>
								<tr>
									<th width="100px">Actions</th>
									<td class="text-left">
										<a href="?page=mail&action=new&name={user.name}"> Send Mail </a>
									</td>
								</tr>
							</table>
						</div>
					</div>
				
					{#if bio}
						[{bio}]
					{/if}
					{#unless bio}
						<em><small>The user has not set up there bio yet!</small></em>
					{/unless}

					{#if edit}
						<div class="row">
							<div class="col-md-12 text-right ">
								<a href="?page=profile&action=edit" class="btn">Edit Profile</a>
							</div>
						</div>
					{/if}
                </div>
            </div>
		';
        
    }

?>