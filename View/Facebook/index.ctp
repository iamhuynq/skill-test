<!-- Button New Post -->
<div class="form-group w-100">
    <a id="btn_page_post_face"><button type="submit" class="btn btn-primary"><?php echo __("002-02-1") ?></button></a>
</div>

<!-- Div Data To From -->
<div class="input-group date" data-provide="datepicker">
    <?php echo __("002-02-2") ?>
    <input type="text" class="form-control datepicker-from" readonly id='from' placeholder="-- / -- / ----"> 
    <?php echo __("002-02-4") ?>
    <input type="text" class="form-control datepicker-to" readonly id='to' placeholder="-- / -- / ----">
</div>      

<!-- Button Download -->
<div class="form-group w-100">
    <button type="submit" onClick="Download('face')" class="btn btn-primary"><?php echo __("002-02-6") ?></button>
</div>

<!-- Form Search Data -->
<?php echo $this->Form->create(); ?>
    <div class="form-group w-25">
        <input type="text" class="form-control" name="keyword">
    </div>
    <div class="form-group w-100">
        <button type="submit" class="btn btn-primary"><?php echo __("002-02-8") ?></button>
    </div>
<?php echo $this->form->end(); ?>

<!-- Table show data -->
<table class="table table-bordered">
    <thead>
        <tr class="bg-light">
            <th><?php echo __("002-02-11") ?></th>
            <th><?php echo __("002-02-12") ?></th>
            <th><?php echo __("002-02-13") ?></th>
            <th><?php echo __("002-02-14") ?></th>
            <th><?php echo __("002-02-15") ?></th>
            <th><?php echo __("002-02-16") ?></th>
        </tr>
    </thead>
    <tbody class="body_table_view" <?php if (isset($dataFacebook)) { ?> total_item = '<?php echo count($dataFacebook)?>'<?php }?> >
        <?php if (count($dataFacebook) > 0) { ?>
            <?php foreach($dataFacebook as $fbdata): ?>
                <tr>
                    <td><?php echo $fbdata['t_facebook_posts']['feed_id'] ?></td>
                    <td><?php echo $fbdata['t_facebook_posts']['message'] ?></td>
                    <td><?php echo $fbdata['t_facebook_posts']['like'] ?></td>
                    <td><?php echo $fbdata['t_facebook_posts']['comment'] ?></td>
                    <td class='created' value='<?php echo date('m/d/Y',strtotime($fbdata['t_facebook_posts']['created_at'])); ?>'><?php echo date($fbdata['t_facebook_posts']['created_at']); ?></td>
                    <td class="text-center"><a href="#" onClick="delItem($(this), <?php echo $fbdata['t_facebook_posts']['id'] ?>, 'face')"><?php echo $this->Html->image("1.png",array("width" => "25")) ?></a></td>
                </tr>
            <?php endforeach; ?>
        <?php } else { ?>
        <!-- if No data -->
            <tr>
                <td colspan="6" class="text-center"><?php echo __("002-02-23") ?></td>
            </tr>

        <?php } ?>
    <!-- // end nodata -->
    </tbody>
</table>
