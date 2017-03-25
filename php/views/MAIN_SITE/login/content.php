<?php
global $CONFIG;
?>

<div class="container">
    <div class="container bg-white">
        <div class="row offset-header">
            <div class="col-sm-6 col-sm-offset-3 text-center margin-top-5">
                <?php $App->printMessage(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">       
                <div class="panel panel-default" style="margin-bottom: 50px;">
                    <?php
                    switch ($App->action) {
                        case 'forgot-password':
                            ?>
                            <div class="panel-heading">
                                <h3 class="panel-title">Recover password</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post" action="/login/forgot-password" autocomplete="off">
                                    <fieldset>
                                        <div class="form-group">
                                            <label for="username">Enter username</label>
                                            <input class="form-control" placeholder="username" name="username" type="text" autofocus value="<?php echo $App->postValue("username"); ?>">
                                        </div>                                    
                                        <div class="row">
                                            <div class="col-md-12"> 
                                                <span class="pull-left">
                                                    <label>
                                                        <a href="/login" title="Login">Login</a>
                                                    </label>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="submit" name="submit" class="btn btn-lg btn-success btn-block" value="Recover"/>                            
                                    </fieldset>
                                </form>
                            </div>
                            <?php
                            break;

                        default:
                            ?>
                            <div class="panel-heading">
                                <h3 class="panel-title">Please Sign In</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post" action="" autocomplete="off">
                                    <fieldset>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input class="form-control" placeholder="username" name="username" type="text" autofocus value="<?php echo $App->postValue("username"); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input class="form-control" placeholder="password" name="password" type="password">
                                        </div>
                                        <?php if ($CONFIG['enable_cookies'] == 1) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="remember" type="checkbox" value="1" <?php echo isset($_POST['remember']) && $_POST['remember'] == 1 ? 'checked="checked"' : ''; ?> class="icheck"> Remember Me
                                                </label>
                                            </div>
                                        <?php } ?>
                                        <div class="row">
                                            <div class="col-md-12"> 
                                                <span class="pull-left">
                                                    <label>
                                                        <a href="/login/forgot-password" title="Forgot password">[ ? ]</a>
                                                    </label>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="submit" name="submit" class="btn btn-lg btn-success btn-block" value="Login"/>                            
                                    </fieldset>
                                </form>
                            </div>
                            <?php
                            break;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
