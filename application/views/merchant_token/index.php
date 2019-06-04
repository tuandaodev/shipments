<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> <?php echo $pageTitle; ?>
      </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo anchor('merchant_token/create', '<i class="fa fa-plus"></i> Create Token', array('class' => 'btn btn-block btn-primary btn-flat')); ?></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($token_list)) {
                                    foreach ($token_list as $token) { ?> 
                                        <tr>
                                            <td><?php echo $token['id'] ?></td>
                                            <td><?php echo $token['name'] ?></td>
                                            <td><?php
                                                if (strlen($token['value']) > 100) {
                                                    echo substr($token['value'], 0, 100) . "...";
                                                } else {
                                                    echo $token['value'];
                                                }
                                                ?></td>
                                            <td><?php if ($token['status']) echo '<span style="color:green">Enable</span>'; else echo '<span style="color:red">Disable</span>'; ?></td>
                                            <td><?php echo $token['created'] ?></td>
                                            <td>
                                                <?php echo anchor('merchant_token/edit/' . $token['id'], "<i class='fa fa-edit'></i> Edit", array("class" => "btn btn-primary")); ?>
                                                <button type="button" class="btn btn-danger" onclick="delele_token(<?php echo $token['id']; ?>)">Delete</button>
                                            </td>
                                        </tr>
    <?php }
} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
function delele_token(id) {
    var result = confirm("Bạn chắc chắn muốn xóa token này?");
    if (result) {
        var delete_url = "<?php echo site_url('merchant_token/delete/') ?>" + id;
        window.location.href = delete_url;
    }
}
</script>