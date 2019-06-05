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
                    <div class="box-header">
                        <h3 class="box-title">Options Page</h3>
                    </div>
                    <div class="box-body">
                        <?php echo $message_public; ?>

                        <?php echo form_open(current_url(), array('class' => 'form', 'id' => 'form-admin-options')); ?>
                        <?php foreach ($options_text as $item): ?>

                        <div class="form-group">
                            <label for=""><?php echo $item['title']  ?></label>
                            <input type="text" class="form-control" name="<?php echo $item['name'] ?>"
                                id="<?php echo $item['name'] ?>" value="<?php echo $item['value'] ?>">
                        </div>

                        <?php 
                    
                    
                    endforeach; ?>
                        
                        <div class="box-footer">
                            <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => "Save Options")); ?>
                        </div>
                        <?php echo form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>