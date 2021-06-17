<?php
    class moduleManagerTemplate extends template {

        public $continueWithError = '
            <div class="alert alert-danger">
                {error.text}
            </div>
            <h3>Error Output</h3>
            {error.output}
            <a href="?page=admin&module=moduleManager&action=install&view={id}" class="btn btn-default">
                Go back to module overview
            </a>
            <div class="pull-right">
                <em>This may cause other issues </em>
                <a href="?page=admin&module=moduleManager&action=install&installModule={id}&force=true" class="btn btn-danger">
                    Continue with install
                </a>
            </div>
        ';
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

        public $moduleOverview = '
            <div class="pull-right">
                {#if _installing}
                    <a href="?page=admin&module=moduleManager&action=install&installModule={id}" class="btn btn-default">Install Module</a>
                {/if}
                {#if _activated}
                    <a href="?page=admin&module=moduleManager&action=deactivate&moduleName={id}" class="btn btn-danger">Deactivate Module</a>
                {/if}
                {#if _deactivated}
                    <a href="?page=admin&module=moduleManager&action=reactivate&moduleName={id}" class="btn btn-success">Reactivate Module</a>
                {/if}
            </div>
            <h2>{name} <small>{version}</small></h2>
            {#if author.name}
                <p>
                    Developed by <a href="{author.url}" target="_blank">{author.name}</a>
                    {#if notes} <small> - <{notes}></small>{/if}
                </p>
            {else}
                <p>
                    Developed by: 
                </p>
                <ul>
                    {#each author}
                        <li>
                            <a href="{url}" target="_blank">{name}</a>
                            {#if notes} <small> - <{notes}></small>{/if}
                        </li>
                    {/each}
                </ul>
            {/if}
            <div class="well">
                {description}
            </div>

            <div class="row">
                <div class="col-md-6">

                    {#if bundle}
                        <table class="table table-condensed table-striped table-bordered no-dt">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th class="text-center" width="100px">Extracted</th>
                                    <th class="text-center" width="100px">Installed</th>
                                    <th class="text-center" width="100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <a href="?page=admin&module=moduleManager&action=install&view={id}&extract=*" class="btn btn-xs btn-block btn-success">
                                                Extract All
                                            </a>
                                        </td>
                                        <td>
                                            <a href="?page=admin&module=moduleManager&action=install&view={id}&installBundleModule=*" class="btn btn-xs btn-block btn-warning">
                                                Install All
                                            </a>
                                        </td>
                                        <td></td>
                                    </tr>

                                    <{bundleInfo}>
                            </tbody>
                        </table>
                    {/if}
                    {#unless bundle}
                        <table class="table table-condensed table-striped table-bordered no-dt">
                            <thead>
                                <tr>
                                    <th>Option</th>
                                    <th width="60px" class="text-center">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Module accessible within jail</td>
                                    <td class="text-center">
                                        {#if allowedInJail}
                                            <i class="glyphicon glyphicon-ok"></i>
                                        {/if}
                                        {#unless allowedInJail}
                                            <i class="glyphicon glyphicon-remove"></i>
                                        {/unless}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Module requires user to be logged in</td>
                                    <td class="text-center">
                                        {#if requireLogin}
                                            <i class="glyphicon glyphicon-ok"></i>
                                        {/if}
                                        {#unless requireLogin}
                                            <i class="glyphicon glyphicon-remove"></i>
                                        {/unless}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Has admin panel</td>
                                    <td class="text-center">
                                        {#if admin}
                                            <i class="glyphicon glyphicon-ok"></i>
                                        {/if}
                                        {#unless admin}
                                            <i class="glyphicon glyphicon-remove"></i>
                                        {/unless}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    {/unless}
                </div>
                <div class="col-md-6">
                    {#if admin}
                        <h4>Admin Actions</h4>
                        <ul>
                            {#each admin}
                                <li>{text}</li>
                            {/each}
                        </ul>
                    {/if}
                </div>
            </div>
        ';

        public $mInfo = '
            <tr>
                <td>{name}</td>
                <td class="text-center">
                    {>mInfoExtractedIcon}
                </td>
                <td class="text-center">
                    {>mInfoInstalledIcon}
                </td>
                <td class="text-center">
                    {>mInfoActions}
                </td>
            </tr>
        ';

        public $mInfoActions = '
            {#unless installed}
                {#unless extracted}
                    <a href="?page=admin&module=moduleManager&action=install&view={id}&extract={name}">
                        Extract
                    </a>
                {/unless}
            {/unless}
            {#if extracted}
                {#unless installed}
                    <a href="?page=admin&module=moduleManager&action=install&view={id}&installBundleModule={name}">
                        Install
                    </a>
                {/unless}
            {/if}
            {#if installed}
                <a href="?page=admin&module=moduleManager&action=install&view={id}&deactivateBundleModule={name}">
                    De-Activate
                </a>
            {/if}
        ';

        public $mInfoInstalledIcon = '
            {#if installed}
                <i class="glyphicon text-success glyphicon-ok"></i>
            {/if}
            {#unless installed}
                <i class="glyphicon text-danger glyphicon-remove"></i>
            {/unless}
        ';

        public $mInfoExtractedIcon = '
            {#unless installed}
                {#if extracted}
                    <i class="glyphicon text-success glyphicon-ok"></i>
                {/if}
                {#unless extracted}
                    <i class="glyphicon text-danger glyphicon-remove"></i>
                {/unless}
            {/unless}
        ';

        public $moduleList = '

            <table class="table table-condensed table-responsive table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="190px">Name</th>
                        <th>Description</th>
                        <th width="70px">Version</th>
                        <th width="90px">Author</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each modules}
                        <tr>
                            <td>
                                {name}
                            </td>
                            <td>{description}</td>
                            <td>{version}</td>
                            <td><a href="{author.url}" target="_blank">{author.name}</a></td>
                            <td>
                                [<a href="?page=admin&module=moduleManager&action=view&moduleName={id}">View</a>] 
                                {#unless moduleCantBeDisabled}
                                [<a href="?page=admin&module=moduleManager&action=deactivate&moduleName={id}">Deactivate</a>]
                                {/unless}
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $deactivatedModuleList = '

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
                                [<a href="?page=admin&module=moduleManager&action=deactivated&moduleName={id}">View</a>] 
                                {#unless moduleCantBeDisabled}
                                [<a href="?page=admin&module=moduleManager&action=reactivate&moduleName={id}">Reactivate</a>]
                                [<a href="?page=admin&module=moduleManager&action=remove&moduleName={id}">Remove</a>]
                                {/unless}
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $alterModuleConfirm = '
            <div class="text-center">
                <p> 
                    Please confirm that you want to {type} this module?
                </p>
                <p> 
                    <em>"{module.name}"</em>
                </p>
                <a href="?page=admin&module=moduleManager&action={type}&moduleName={module.id}&do=true" class="btn btn-danger">
                    I confirm that i want to {type} this module 
                </a>
            </div>

        ';

        public $moduleForm = '
            <form method="post" action="?page=admin&module=moduleManager&action=install" enctype="multipart/form-data">
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

            {#if modules}

                <hr />

                <h3>Continue Installation</h3>

                <table class="table table-responsive table-bordered table-striped table-condensed">
                    <thead>
                        <th width="90px">Name</th>
                        <th>Description</th>
                        <th width="70px">Version</th>
                        <th width="90px">Author</th>
                        <th width="115px">Actions</th>
                    </thead>
                    <tbody>
                        {#each modules}
                            <tr>
                                <td>{name}</td>
                                <td>{description}</td>
                                <td>{version}</td>
                                <td><a href="{author.url}" target="_blank">{author.name}</a></td>
                                <td>
                                    [<a href="?page=admin&module=moduleManager&action=install&view={id}">Continue</a>] 
                                    [<a href="?page=admin&module=moduleManager&action=install&remove={id}">Remove</a>] 
                                </td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            {/if}
        ';
    }

