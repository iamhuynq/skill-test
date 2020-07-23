<!-- Button New Post -->
<div class="form-group w-100">
    <a id="btn_page_post"><button type="submit" class="btn btn-primary"><?php echo __("002-01-1") ?></button></a>
</div>

<!-- Div Data To From -->
<div class="input-group date" data-provide="datepicker">
    <?php echo __("002-01-2") ?>
    <input type="text" class="form-control datepicker-from" readonly id='from' placeholder="-- / -- / ----"> 
    <?php echo __("002-01-4") ?>
    <input type="text" class="form-control datepicker-to" readonly id='to' placeholder="-- / -- / ----">
</div>      

<!-- Button Download -->
<div class="form-group w-100">
    <button type="submit" onClick="Download('twitter')" class="btn btn-primary"><?php echo __("002-01-6") ?></button>
</div>

<!-- Form Search Data -->
<?php echo $this->Form->create(); ?>
    <div class="form-group w-25">
        <input type="text" class="form-control" name="keyword">
    </div>
    <div class="form-group w-100">
        <button type="submit" class="btn btn-primary"><?php echo __("002-01-8") ?></button>
    </div>
<?php echo $this->form->end(); ?>

<!-- Table show data -->
<table class="table table-bordered">
    <thead>
        <tr class="bg-light">
            <th><?php echo __("002-01-11") ?></th>
            <th><?php echo __("002-01-12") ?></th>
            <th><?php echo __("002-01-13") ?></th>
            <th><?php echo __("002-01-14") ?></th>
            <th><?php echo __("002-01-15") ?></th>
            <th><?php echo __("002-01-16") ?></th>
        </tr>
    </thead>
    <tbody class="body_table_view" <?php if (isset($dataTwitter)) { ?> total_item = '<?php echo count($dataTwitter)?>'<?php }?> >
        <?php if (count($dataTwitter) > 0) { ?>
            <?php foreach($dataTwitter as $twdata): ?>
                <tr>
                    <td><?php echo $twdata['t_twitter_posts']['tweet_id'] ?></td>
                    <td><?php echo $twdata['t_twitter_posts']['message'] ?></td>
                    <td><?php echo $twdata['t_twitter_posts']['retweet'] ?></td>
                    <td><?php echo $twdata['t_twitter_posts']['favorite'] ?></td>
                    <td class='created' value='<?php echo date('m/d/Y', strtotime($twdata['t_twitter_posts']['created_at'])); ?>'><?php echo date($twdata['t_twitter_posts']['created_at']); ?></td>
                    <td class="text-center"><a href="#" onClick="delItem($(this), <?php echo $twdata['t_twitter_posts']['id'] ?>, 'twitter')"><?php echo $this->Html->image("1.png",array("width" => "25")) ?></a></td>
                </tr>
            <?php endforeach; ?>
        <?php } else { ?>
        <!-- if No data -->
            <tr>
                <td colspan="6" class="text-center"><?php echo __("002-01-23") ?></td>
            </tr>

        <?php } ?>
    <!-- // end nodata -->
    </tbody>
</table>
