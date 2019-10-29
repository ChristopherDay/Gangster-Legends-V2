<?php

    class loggedinTemplate extends template {
        
        public $newsArticle = '
            {#each news}
                <div class="well well-sm">
                    <h4>
                        {title} 
                        <small class="pull-right tiny">
                            By {>userName} {date}
                        </small>
                        <div class="clearfix"></div>
                    </h4>
                    <hr />
                    <p>[{text}]</p>
                </div>
            {/each}
        ';
  

        public $loggedinList = '
            <table class="table table-condensed table-responsive table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="150px">Author</th>
                        <th>Title</th>
                        <th width="160px">Date</th>
                        <th width="120px">Options</th>
                    </tr>
                </thead>
                <tbody>
                    {#each loggedin}
                        <tr>
                            <td>{gnauthor}</td>
                            <td>{gntitle}</td>
                            <td>{gndate}</td>
                            <td>
                                [<a href="?page=admin&module=loggedin&action=edit&id={id}">Edit</a>] 
                                [<a href="?page=admin&module=loggedin&action=delete&id={id}">Delete</a>]
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $loggedinDelete = '
            <form method="post" action="?page=admin&module=loggedin&action=delete&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this news post?</p>

                    <p><em>"{gntitle}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this news post</button>
                </div>
            </form>
        
        ';


        public $loggedinNewForm = '
            <form method="post" action="?page=admin&module=loggedin&action={editType}&id={id}">
                <div class="form-group">
                    <label class="pull-left">Title</label>
                    <input type="text" class="form-control" name="gntitle" value="{gntitle}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Text</label>
                    <textarea rows="8" type="text" class="form-control" name="gntext">{gntext}</textarea>
                </div>
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';
    }

?>