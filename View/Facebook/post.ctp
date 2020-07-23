<div></div>
<div class="row div_first_post">
    <textarea name="" id="cap" onkeyup="countChar(this)" maxlength="999999" cols="100" rows="10"></textarea>
    <div id="charNum" style="padding-left: 30px;"></div>/999.999
</div>
<div class="row div_input_post">
   <label class=newbtn>
        <span class="btn btn-primary btn-file"> + <?php echo __("004-01-3"); ?>
            <input style="display: none;" type='file' id='file' name="file" class="file-img-uploader" accept='image/*' onchange='openFile(event)'>
        </span><br>
        <img id='output'>
    </label>
</div>

<div class="col-md-6 text-center"> 
    <button class="btn btn-secondary button_calcel" ><?php echo __("004-01-7"); ?></button>
    <button class="btn btn-primary button_post" onClick="postItem('face')"><?php echo __("004-01-8"); ?></button>
</div>

<div id="shortModal" class="modal modal-wide fade"><div></div>
    <div class="modal-dialog"><div></div>
        <div class="modal-content"><div></div>
            <div class="modal-body">
                <p><?php echo __("004-01-9"); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("004-01-10"); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="hightModal" class="modal modal-wide fade"><div></div>
    <div class="modal-dialog"><div></div>
        <div class="modal-content"><div></div>
            <div class="modal-body">
                <p>Have Error!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("004-01-10"); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
