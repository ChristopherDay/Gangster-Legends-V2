<?php

    class mailTemplate extends template {
        
        public $mail = '
        	<div class="well well-sm">
	        	<h3>
                    {subject} 
                    <small class="pull-right tiny">
                        By <a href="?page=profile&view={sender.U_id}">{sender.U_name}</a><br />
                        {date}
                    </small>
                </h3>
                <hr />
	        	<p>[{text}]</p>
            </div>
            <h4 class="text-left">Reply to {sender.U_name}</h4>
            {>newMail}
        ';

        public $newMail = '
            <form method="post" action="?page=mail&view=reply&id={id}">
            	<input rows="6" name="subject" class="form-control" placeholder="Subject" / >
            	<br />
            	<textarea rows="6" name="message" class="form-control" placeholder="Your reply ..."></textarea>
            	<br />
            	<div class="text-right">
            		<button class="btn btn-default">
            			Send
            		</button>
            	</div>
            </form>
        ';

        public $mailTable = '
	        <table class="table table-condensed table-responsive table-bordered table-striped">
	        	<thead>
		            <tr>
		                <th style="width:60px"></th>
		                <th style="width:130px">Date</th>
		                <th style="width:150px">
		                	{#if inbox}
		                		From
		                	{/if}
		                	{#unless inbox}
		                		To
		                	{/unless}
		                </th>
		                <th>Subject</th>
		                <th style="width:50px">Action</th>
		            </tr>
	        	</thead>
	        	<tbody>
		            {#unless mail}
		                <tr>
		                    <td colspan="5">
		                        <div class="text-center">
		                            <em> You have no mail</em>
		                        </div>
		                    </td>
		                </tr>
		            {/unless}
		            {#each mail}
		                <tr>
		                    <td class="text-center">{#unless read}*NEW*{/unless}</td>
		                    <td>{date}</td>
		                    <td>
			                	{#if inbox}
				                	<a href="?page=profile&view={sender.U_id}">{sender.U_name}</a>
			                	{/if}
			                	{#unless inbox}
				                	<a href="?page=profile&view={receiver.U_id}">{receiver.U_name}</a>
			                	{/unless}
		                    </td>
		                    <td>{subject}</td>
		                    <td class="text-center">
		                        <a href="?page=mail&view=read&id={id}">Read</a>
		                    </td>
		                </tr>
		            {/each}
	        	</tbody>
	        </table>
        ';

        public $mailInbox = '
	        <h4 class="text-left">
	    		Inbox
	    		<span class="small pull-right">
	    			<a href="?page=mail&view=outbox">Outbox</a>
	    		</span>
	    	</h4>

	    	{>mailTable}
        ';

        public $mailOutbox = '
	        <h4 class="text-left">
	    		Outbox
	    		<span class="small pull-right">
	    			<a href="?page=mail">Inbox</a>
	    		</span>
	    	</h4>

	    	{>mailTable}
        ';
        
    }

?>