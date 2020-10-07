<?php
require_once 'configs/app_top.php';
if (!is_admin_logged_in()) {
    redirect(generate_site_link("login"));
}
if ($_GET["mode"] == "add") {
    $title = "Add Admin";
} else {
    $title = "Edit Admin";
    try {
        $sql2 = "select `admin_id`, `adm_full_name`, `adm_username`, `adm_email`, `adm_access_update`, `adm_access_delete` "
                . "FROM " . TBL_ADMIN . " WHERE 1 AND admin_id = :id LIMIT 1";

        $stmt = $DB->prepare($sql2);
        $stmt->bindValue(":id", safe_input($_GET["id"]));
        $stmt->execute();
        $results = $stmt->fetchAll();
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

include 'header.php';
?>

<div class="container mainbody">

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading"><span class="fa fa-cog"></span> <?php echo $title; ?></div>
                <div class="panel-body">
                    <form class="form-horizontal" name="form1" id="form1" action="<?php echo generate_site_link("admin-auth"); ?>" method="post">
                        <input type="hidden" name="mode" value="<?php echo ($_GET["mode"] == "add") ? "add-new" : "update"; ?>">
                        <input type="hidden" name="id" value="<?php echo safe_input($_GET["id"]); ?>">
                        <input type="hidden" name="pagenum" value="<?php echo safe_input($_GET["pagenum"]); ?>">
                        <fieldset>

                            <div class="form-group">
                                <label class="col-sm-3 col-md-2 control-label" for="full_name"><span class="required">*</span>Full Name: </label>
                                <div class="col-sm-8 col-md-6">
                                    <input type="text" placeholder="Full Name" id="full_name" autocomplete="off" name="full_name" class="form-control" value="<?php echo safe_output($results[0]["adm_full_name"]); ?>">

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 col-md-2 control-label" for="uemail"><span class="required">*</span>Email Address: </label>
                                <div class="col-sm-8 col-md-6">
                                    <input type="text" placeholder="Email Address" id="uemail" name="uemail" autocomplete="off" class="form-control" value="<?php echo safe_output($results[0]["adm_email"]); ?>">
                                    <div class="help-block">Must be unique</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 col-md-2 control-label" for="admin_id"><span class="required">*</span>Login ID: </label>
                                <div class="col-sm-8 col-md-6">
                                    <input type="text" placeholder="Admin ID" id="admin_id" autocomplete="off" name="admin_id" class="form-control" value="<?php echo safe_output($results[0]["adm_username"]); ?>">
                                    <div class="help-block">Must be unique, used for login</div>
                                </div>
                            </div>
                            
                             <div class="form-group">
                                <label class="col-sm-3 col-md-2 control-label" for="new_password1">Permission: </label>
                                <div class="col-sm-8 col-md-6">
                                    <label class="control-label"><input type="checkbox"  name="access_update" value="1" <?php echo (intval($results[0]["adm_access_update"]) == 1) ? 'checked' : '' ?> > Add & Update</label> 
                                    <label class="control-label"><input type="checkbox"  name="access_delete" value="1" <?php echo (intval($results[0]["adm_access_delete"]) == 1) ? 'checked' : '' ?>> Delete</label>
                                </div>
                            </div>

                            <?php if ($_GET["mode"] == "edit") { ?>
                                <div class="well well-sm">Leave password blank, if you don't want to update password.</div>
                            <?php } ?>

                            <div class="form-group">
                                <label class="col-sm-3 col-md-2 control-label" for="new_password1">New Password: </label>
                                <div class="col-sm-8 col-md-6">
                                    <input type="password" placeholder="New Password" id="new_password1" name="new_password1" class="form-control" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 col-md-2 control-label" for="new_password2">Confirm Password: </label>
                                <div class="col-sm-8 col-md-6">
                                    <input type="password" placeholder="Confirm Password" id="new_password2" name="new_password2" class="form-control" value="">
                                </div>
                            </div>

                           

                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3 col-md-offset-2">
                                    <button class="btn btn-primary" type="submit">Submit <i class="fa fa-save"></i></button> 
                                    <a class="btn btn-info" href="<?php echo generate_site_link("manage-admins"); ?>"><i class="fa fa-arrow-left"></i> Back to listing</a>
                                </div>
                            </div>

                        </fieldset>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery().ready(function () {

        // validate manage profile form
        jQuery("#form1").validate({
            rules: {
                full_name: {
                    required: true,
                    minlength: 3
                },
                uemail: {
                    required: true,
                    email: true,
                },
                admin_id: {
                    required: true,
                    minlength: 2
                },
                new_password1: {
                    required: <?php echo ($_GET["mode"] == "add") ? 'true' : 'false' ?>,
                    minlength: 6
                },
                new_password2: {
                    minlength: 6,
                    required: <?php echo ($_GET["mode"] == "add") ? 'true' : 'false' ?>,
                    equalTo: "#new_password1"
                }
            },
            errorElement: "span",
            errorClass: "help-inline-error",
            onkeyup: false,
            onfocusout: true,
            onclick: false,
            onfocusin: false,
            highlight: function (element) {
                jQuery(element).parent().parent().removeClass("has-success");
                jQuery(element).closest('div').parent().addClass("has-error");
            },
            unhighlight: function (element) {
                jQuery(element).parent().parent().removeClass("has-error");
                jQuery(element).closest('div').parent().addClass("has-success");
            }
        });
    });
</script>
<?php include 'footer.php'; ?>