<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo trans('app.'.'new_client'); ?> </h4>
        </div>
        <?php echo Form::open(['route' => 'clients.api.save', 'class' => 'ajaxifyForm validator', 'novalidate' => '', 'files' => true]); ?>


        <div class="modal-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#tab-client-general" data-toggle="tab"><?php echo trans('app.'.'general'); ?> </a></li>
                <li><a href="#tab-client-contact" data-toggle="tab"><?php echo trans('app.'.'contact'); ?> </a></li>
                <li><a href="#tab-client-web" data-toggle="tab"><?php echo trans('app.'.'web'); ?> </a></li>
                <li><a href="#tab-client-custom" data-toggle="tab"><?php echo trans('app.'.'custom_fields'); ?> </a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab-client-general">

                    <div class="form-group">
                        <label><?php echo trans('app.'.'company_name'); ?>  / <?php echo trans('app.'.'fullname'); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="" class="input-sm form-control" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo trans('app.'.'email'); ?>  <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="" class="input-sm form-control" required>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                        <label><?php echo trans('app.'.'contact_person'); ?> </label>
                        <input type="text" name="contact_name" class="input-sm form-control">
                        </div>

                        <div class="form-group col-md-6">
                        <label><?php echo trans('app.'.'contact_email'); ?></label>
                        <input type="text" name="contact_email" class="input-sm form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo trans('app.'.'tax_number'); ?>  </label>
                        <input type="text" value="" name="tax_number" class="input-sm form-control">
                    </div>
                    

                    <div class="form-group">
                        <label><?php echo trans('app.'.'notes'); ?> </label>
                        <textarea name="notes" class="form-control ta" placeholder="<?php echo trans('app.'.'notes'); ?> "></textarea>
                    </div>

                    <div class="form-group">
                <label><?php echo trans('app.'.'tags'); ?>  </label>
                    <select class="select2-tags form-control" name="tags[]" multiple="multiple">
                        <?php $__currentLoopData = App\Entities\Tag::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tag->name); ?>"><?php echo e($tag->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
            </div>

                </div>
                <div class="tab-pane fade in" id="tab-client-contact">
                    <div class="form-group col-md-4 no-gutter-left">
                        <label><?php echo trans('app.'.'phone'); ?>  </label>
                        <input type="text" value="" name="phone" class="input-sm form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label><?php echo trans('app.'.'mobile'); ?>  </label>
                        <input type="text" value="" name="mobile" class="input-sm form-control">
                    </div>
                    <div class="form-group col-md-4 no-gutter-right">
                        <label><?php echo trans('app.'.'fax'); ?>  </label>
                        <input type="text" value="" name="fax" class="input-sm form-control">
                    </div>
                    <div class="clearfix"></div>

                    <div class="form-group col-md-6 no-gutter-left">
                        <label><?php echo trans('app.'.'address'); ?> 1</label>
                        <textarea name="address1" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 no-gutter-right">
                        <label><?php echo trans('app.'.'address'); ?> 2</label>
                        <textarea name="address2" class="form-control"></textarea>
                    </div>

                    

                    <div class="form-group col-md-6 no-gutter-left">
                        <label><?php echo trans('app.'.'city'); ?>  </label>
                        <input type="text" value="" name="city" class="input-sm form-control">
                    </div>
                    <div class="form-group col-md-6 no-gutter-right">
                        <label><?php echo trans('app.'.'zipcode'); ?>  </label>
                        <input type="text" value="" name="zip_code" class="input-sm form-control">
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                        <label><?php echo trans('app.'.'locale'); ?> </label>
                        <select name="locale" class="form-control">
                            <?php $__currentLoopData = languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($language['code']); ?>" <?php echo e(get_option('locale') == $language['code'] ? ' selected' : ''); ?>><?php echo e(ucfirst($language['name'])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label><?php echo trans('app.'.'currency'); ?> </label>
                        <select name="currency" class="form-control select2-option">
                            <?php $__currentLoopData = currencies(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cur['code']); ?>" <?php echo e(get_option('default_currency') == $cur['code'] ? ' selected' : ''); ?>><?php echo e($cur['title']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>


                    <div class="row">
                        <div class="form-group col-md-6">
                            <label><?php echo trans('app.'.'state'); ?>  </label>
                            <input type="text" value="" name="state" class="input-sm form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label><?php echo trans('app.'.'country'); ?>  </label>
                            <select class="form-control select2-option" name="country">
                                    <?php $__currentLoopData = countries(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country['name']); ?>" <?php echo e($country['name'] == get_option('company_country') ? 'selected' : ''); ?>><?php echo e($country['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        

                    </div>

                    <div class="form-group">
                                
                                    <label><?php echo trans('app.'.'logo'); ?> </label>
                                        <input type="file" name="logo">
                                    
                                </div>
                                
                </div>
                <div class="tab-pane fade in" id="tab-client-web">
                    <div class="form-group">
                        <label><?php echo trans('app.'.'website'); ?>  </label>
                        <input type="text" value="" name="website" class="input-sm form-control">
                    </div>
                    <div class="form-group">
                        <label>Skype</label>
                        <input type="text" value="" name="skype" class="input-sm form-control">
                    </div>
                    <div class="form-group">
                        <label>LinkedIn</label>
                        <input type="text" value="https://linkedin.com/in/" name="linkedin" class="input-sm form-control">
                    </div>
                    <div class="form-group">
                        <label>Facebook</label>
                        <input type="text" value="https://facebook.com/" name="facebook" class="input-sm form-control">
                    </div>
                    <div class="form-group">
                        <label>Twitter</label>
                        <input type="text" value="https://twitter.com/" name="twitter" class="input-sm form-control">
                    </div>
                </div>

                

                <div class="tab-pane fade in" id="tab-client-custom">

                <?php 
                $data['fields'] = App\Entities\CustomField::whereModule('clients')->orderBy('order', 'desc')->get();
                ?>
                <?php echo $__env->make('partial.customfields.createNoCol', $data, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                </div>
        </div>

        <?php echo $__env->make('partial.privacy_consent', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        
        <div class="modal-footer">
            <?php echo closeModalButton(); ?>

            <?php echo renderAjaxButton(); ?>

        </div>
        <?php echo Form::close(); ?>

    </div>
</div>
<?php $__env->startPush('pagestyle'); ?>
<?php echo $__env->make('stacks.css.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('stacks.js.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('partial.ajaxify', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->yieldPushContent('pagestyle'); ?>
<?php echo $__env->yieldPushContent('pagescript'); ?>