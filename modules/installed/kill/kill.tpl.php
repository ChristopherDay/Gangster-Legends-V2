<?php

	class killTemplate extends template {
		
		public $killPage = '
			<div class="panel panel-primary">
                <div class="panel-heading">What user do you want to kill</div>
                <div class="panel-body">

					{#unless detectives}
						<p>
							Your have not found anyone, you need to <a href="?page=detectives">hire a detective<a/>.
						</p>
					{/unless}

					{#if detectives}
						<form action="?page=kill&action=shoot" method="post">
							{#each detectives}
								<label class="select-detective">
									<div class="crime-holder">
										<p>
											<span class="action">
												<input type="radio" name="detective" value="{id}" />
												{>userName} 
											</span> 
											<span class="cooldown">
												<small>
													Expires in <span data-reload-when-done data-timer-type="inline" data-timer="{expires}"></span>
												</small>
											</span> 
											<span class="commit">
												{location}
											</span>
										</p>
									</div>
								</label>
							{/each}

							<div class="clearfix"></div>

							<p>
								<em>
									<small>
										You need to be in the same location to kill this user.
									</small>
								</em>
							</p>

							<h4>How many bullets are you shooting?</h4>

							<input type="number" name="bullets" class="form-control form-control-inline" />
							<button class="btn" name="submit" value="1">
								Shoot!
							</button>
						</form>

					{/if}
                </div>
            </div>
			
		';

	}

?>