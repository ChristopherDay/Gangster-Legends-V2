<?php


    $roleForm = '
        <form method="post" action="?page=admin&module=userRoles&action={editType}&id={id}">
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label class="pull-left">Name</label>
                        <input type="text" class="form-control" name="name" value="{name}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="pull-left">
                            Color <br />
                            <input type="color" name="color" value="{color}">
                        </label> 
                    </div>
                </div>
            </div>

            {#if canAlterModules}
                <h3>Admin Modules</h3>
                <ul class="list-group">
                    {#each modules}
                        {#if admin}
                            <li class="list-group-item col-md-4">
                                <input type="checkbox" name="access[]" value="{id}" {#if selected} checked{/if}> {name}
                            </li>
                        {/if}
                    {/each}
                </ul>
                <div class="clearfix"></div>
            {/if}

            <div class="text-right">
                <button class="btn btn-default" name="submit" type="submit" value="1">
                    Save
                </button>
            </div>
        </form>
    ';


    function parse($html) {
        $html = preg_replace_callback(
            '#\{\#if (.+)\}(((?R)|.+)+)\{\/if}#iUs', 
            function ($a) {
                htmlentities(print_r(parse($a), true));
            }, 
            $html
        );
        return $html;
    }

    echo "<pre>" . parse($roleForm);

?>