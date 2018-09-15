<?php

    class moduleManagerTemplate extends template {

        public $moduleHolder = '
        {#each modules}
        <div class="module-holder">
            <p>{name} ({cooldown}) <span class="commit"><a href="?page=modules&action=commit&module={id}">Commit</a></span></p>
            <div class="module-perc">
                <div class="perc" style="width:{percent}%;"></div>
            </div>
        </div>
        {/each}
        {#unless modules}
            <div class="text-center"><em>There are no modules</em></div>
        {/unless}';

        public $moduleList = '


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
                    {#each modules}
                        <tr>
                            <td>{name}</td>
                            <td>{description}</td>
                            <td>{version}</td>
                            <td><a href="{author.url}" target="_blank">{author.name}</a></td>
                            <td>
                                [<a href="?page=admin&module=modules&action=edit&moduleName={id}">Edit</a>] 
                                {#unless moduleCantBeDisabled}
                                [<a href="?page=admin&module=modules&action=delete&moduleName={id}">Deactivate</a>]
                                {/unless}
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $moduleDelete = '
            <form method="post" action="?page=admin&module=modules&action=delete&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this module?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this module</button>
                </div>
            </form>
        
        ';
        public $moduleForm = '
            <form method="post" action="?page=admin&module=moduleManager&action=install">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="pull-left">Module File (Zipped)</label>
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