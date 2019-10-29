<?php

    class adminTemplate extends template {
        public $dashboard = '
            <div class="row">
                <div class="col-md-6">
                    <h3>Statistics</h3>
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Statistic</th>
                                <th width="50px">#</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Users</td>
                                <td>{users}</td>
                                <td>
                                    <a href="?page=admin&module=users&action=view">
                                        View
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Active Modules</td>
                                <td>{modules}</td>
                                <td>
                                    <a href="?page=admin&module=moduleManager&action=view">
                                        View
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                </div>
            </div>
        ';

    }

?>