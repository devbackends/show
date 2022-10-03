<html>

    <body>

        <div class="container environment" id="environment">
            <div class="initial-display">
                <p>Environment Configuration</p>

                <form action="EnvConfig.php" method="POST" id="environment-form">
                    <div class="content">
                        <div class="databse-error" style="text-align: center; padding-top: 5px" id="database_error">
                        </div>
                        <div class="form-container" style="padding: 10%; padding-top: 10px">
                            <div class="control-group" id="app_name">
                                <label for="app_name" class="required">App Name</label>
                                <input type = "text" name = "app_name" class = "control"
                                value = "Bagisto_"
                                data-validation="required length" data-validation-length="max50"
                                >
                            </div>

                            <div class="control-group" id="app_url">
                                <label for="app_url" class="required">App URL</label>
                                <input type="text" name="app_url" class="control" value="https://<?php echo $_SERVER['HTTP_HOST']; ?>"
                                placeholder="http://localhost"
                                data-validation="required length" data-validation-length="max50">
                            </div>

                            <div class="control-group">
                                <label for="database_connection" class="required">
                                    Database Connection
                                </label>
                                <select name="database_connection" id="database_connection" class="control">
                                    <option value="mysql" selected>Mysql</option>
                                    <option value="sqlite">SQlite</option>
                                    <option value="pgsql">pgSQL</option>
                                    <option value="sqlsrv">SQLSRV</option>
                                </select>
                            </div>

                            <div class="control-group" id="port_name">
                                <label for="port_name" class="required">Database Port</label>
                                <input type="text" name="port_name" class="control" value="3306" placeholder="3306"
                                data-validation="required alphanumeric number length" data-validation-length="max5">
                            </div>

                            <div class="control-group" id="host_name">
                                <label for="host_name" class="required">Database Host</label>
                                <input type="text" name="host_name" class="control" value="127.0.0.1"
                                placeholder="127.0.0.1"
                                data-validation="required length" data-validation-length="max50">
                            </div>

                            <div class="control-group" id="database_name">
                                <label for="database_name" class="required">Database Name</label>
                                <input type="text" name="database_name" class="control"
                                placeholder="database name"
                                data-validation="length required" data-validation-length="max50">
                            </div>

                            <div class="control-group" id="user_name">
                                <label for="user_name" class="required">User Name</label>
                                <input type="text" name="user_name" class="control"
                                value = "bagisto_"
                                data-validation="length required" data-validation-length="max50">
                            </div>

                            <div class="control-group" id="user_password">
                                <label for="user_password" class="required">User Password</label>
                                <input type="password" name="user_password" class="control"
                                placeholder="database password">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button  class="prepare-btn" id="environment-check">Save & Continue</button>
                        <div style="cursor: pointer; margin-top:10px">
                            <span id="envronment-back">back</span>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </body>

</html>

<script>
    $.validate({});
</script>

<script>
    $(document).ready(function() {

        // process the form
        $('#environment-form').submit(function(event) {
            $('.control-group').removeClass('has-error'); // remove the error class
            $('.form-error').remove(); // remove the error text

            // get the form data
            var formData = {
                'app_name'            : $('input[name=app_name]').val(),
                'app_url'             : $('input[name=app_url]').val(),
                'host_name'           : $('input[name=host_name]').val(),
                'port_name'           : $('input[name=port_name]').val(),
                'database_name'       : $('input[name=database_name]').val(),
                'user_name'           : $('input[name=user_name]').val(),
                'user_password'       : $('input[name=user_password]').val(),
                'database_connection' : $("#database_connection" ).val(),
            };

            var target = window.location.href.concat('/EnvConfig.php');

            // process the form
            $.ajax({
                type        : 'POST',
                url         : target,
                data        : formData,
                dataType    : 'json',
                encode      : true
            })
            // using the done promise callback
            .done(function(data) {

                if (!data.success) {

                    // handle errors
                    if (data.errors.app_name) {
                        $('#app_name').addClass('has-error');
                        $('#app_name').append('<div class="form-error">' + data.errors.app_name + '</div>');
                    }
                    if (data.errors.app_url) {
                        $('#app_url').addClass('has-error');
                        $('#app_url').append('<div class="form-error">' + data.errors.app_url + '</div>');
                    }
                    if (data.errors.host_name) {
                        $('#host_name').addClass('has-error');
                        $('#host_name').append('<div class="form-error">' + data.errors.host_name + '</div>');
                    }
                    if (data.errors.port_name) {
                        $('#port_name').addClass('has-error');
                        $('#port_name').append('<div class="form-error">' + data.errors.port_name + '</div>');
                    }
                    if (data.errors.user_name) {
                        $('#user_name').addClass('has-error');
                        $('#user_name').append('<div class="form-error">' + data.errors.user_name + '</div>');
                    }
                    if (data.errors.database_name) {
                        $('#database_name').addClass('has-error');
                        $('#database_name').append('<div class="form-error">' + data.errors.database_name + '</div>');
                    }
                    if (data.errors.user_password) {
                        $('#user_password').addClass('has-error');
                        $('#user_password').append('<div class="form-error">' + data.errors.user_password + '</div>');
                    }
                    if (data.errors.app_url_space) {
                        $('#app_url').addClass('has-error');
                        $('#app_url').append('<div class="form-error">' + data.errors.app_url_space + '</div>');
                    }
                    if (data.errors.app_name_space) {
                        $('#app_name').addClass('has-error');
                        $('#app_name').append('<div class="form-error">' + data.errors.app_name_space + '</div>');
                    }
                    if (data.errors.host_name_space) {
                        $('#host_name').addClass('has-error');
                        $('#host_name').append('<div class="form-error">' + data.errors.host_name_space + '</div>');
                    }
                    if (data.errors.port_name_space) {
                        $('#port_name').addClass('has-error');
                        $('#port_name').append('<div class="form-error">' + data.errors.port_name_space + '</div>');
                    }
                    if (data.errors.user_name_space) {
                        $('#user_name').addClass('has-error');
                        $('#user_name').append('<div class="form-error">' + data.errors.user_name_space + '</div>');
                    }
                    if (data.errors.database_name_space) {
                        $('#database_name').addClass('has-error');
                        $('#database_name').append('<div class="form-error">' + data.errors.database_name_space + '</div>');
                    }
                    if (data.errors.user_password_space) {
                        $('#user_password').addClass('has-error');
                        $('#user_password').append('<div class="form-error">' + data.errors.user_password_space + '</div>');
                    }
                    if (data.errors.database_error) {
                        $('#database_error').append('<div class="form-error">' + data.errors.database_error + '</div>');
                    }
                } else {
                    $('#environment').hide();
                    $('#migration').show();
                }
            });

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });

    });
</script>

