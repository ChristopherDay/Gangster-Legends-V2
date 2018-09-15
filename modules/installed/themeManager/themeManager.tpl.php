<?php

    class themeManagerTemplate extends template {

        public $themeHolder = '
        {#each themes}
        <div class="theme-holder">
            <p>{name} ({cooldown}) <span class="commit"><a href="?page=themes&action=commit&theme={id}">Commit</a></span></p>
            <div class="theme-perc">
                <div class="perc" style="width:{percent}%;"></div>
            </div>
        </div>
        {/each}
        {#unless themes}
            <div class="text-center"><em>There are no themes</em></div>
        {/unless}';

        public $themeList = '


            <table class="table table-condensed table-responsive table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="90px">Name</th>
                        <th>Description</th>
                        <th width="70px">Version</th>
                        <th width="90px">Author</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each themes}
                        <tr>
                            <td>{name}</td>
                            <td>{description}</td>
                            <td>{version}</td>
                            <td><a href="{author.url}" target="_blank">{author.name}</a></td>
                            <td>
                                [<a href="?page=admin&module=themes&action=edit&themeName={id}">Edit</a>] 
                                {#unless themeCantBeDisabled}
                                [<a href="?page=admin&module=themes&action=delete&themeName={id}">Deactivate</a>]
                                {/unless}
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $themeDelete = '
            <form method="post" action="?page=admin&module=themes&action=delete&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this theme?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this theme</button>
                </div>
            </form>
        
        ';
        public $themeForm = '
            <form method="post" action="?page=admin&module=themeManager&action=install">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="pull-left">Theme File (Zipped)</label>
                            <input type="file" class="form-control" name="file" />
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Upload</button>
                </div>
            </form>
        ';
    }

?>