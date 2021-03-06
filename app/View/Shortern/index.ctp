<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <!-- form start -->
            <?php echo $this->Form->create('search', array('url' => array('controller' => 'shortern', 'action' => 'index'))); ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <?php echo $this->Form->input('url',array('class'=>'form-control','placeholder'=>'Url','label'=>'Url')); ?>
                    </div>
                </div>
                <div class="row">   
                    <div class="form-group col-lg-4">
                        <?php echo $this->Form->input('alias',array('class'=>'form-control','placeholder'=>'Alias / Key','label'=>__('Alias / Key'))); ?>
                    </div>
                    <div class="form-group col-lg-4">
                        <?php echo $this->Form->input('redirect',array('class'=>'form-control','empty'=>'Select','options'=>$redirect)); ?>
                    </div> 
                    <div class="form-group col-lg-4">
                        <?php echo $this->Form->input('domain',array('class'=>'form-control','empty'=>'Select','options'=>$domains)); ?>
                    </div>
                </div>
                <div class="row">   
                    <div class="form-group col-lg-6">
                        <?php echo $this->Form->input('sdate',array('class'=>'form-control datepicker','placeholder'=>'Start Date','label'=>__('Start Date'),'id'=>'searchStartdate')); ?>
                    </div> 
                    <div class="form-group col-lg-6">
                        <?php echo $this->Form->input('edate',array('class'=>'form-control datepicker','placeholder'=>'End Date','label'=>__('End Date'),'id'=>'searchEnddate')); ?>
                    </div>
                </div>
                <div class="row">   
                    <div class="form-group col-lg-5">
                        <label for="Status">Status</label>&nbsp;&nbsp;
                        <?php echo $this->Form->checkbox('status',array('class'=>'form-control','label'=>false)); ?>

                    </div>
                </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm"><?php echo __('Search') ?></button>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link('Add shortern',array('controller'=>'shortern','action'=>'shorternopr'),array('class'=>'btn btn-success btn-sm')); ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>
<?php //echo "<pre>";print_r($data);echo "</pre>"; ?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><?php echo $this->Paginator->sort('url',__('Url')); ?></th>
                            <th><?php echo $this->Paginator->sort('key',__('Key / Alias')); ?></th>
                            <th><?php echo $this->Paginator->sort('domain',__('Domain')); ?></th>
                            <th><?php echo $this->Paginator->sort('redirect',__('Redirct')); ?></th>
                            <th><?php echo $this->Paginator->sort('password',__('Password')); ?></th>
                            <th>Total Hits</th>
                            <th><?php echo $this->Paginator->sort('status','Status'); ?></th>
                            <th width="22%">&nbsp;</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(count($data) > 0){
                            $true = $this->Html->image('test-pass-icon.png',array('alt'=>'Active'));
                            $false = $this->Html->image('test-fail-icon.png',array('alt'=>'De-active'));
                            $skey = Configure::read('Security.salt');
                            
                            foreach($data as $key=>$val){
                                $title = strlen($val['Shortern']['url']) > Configure::read('title_length') ? substr($val['Shortern']['url'],0,Configure::read('title_length')).'...' : $val['Shortern']['url'];
                                $detail = _encode(array('id'=>$val['Shortern']['_id']));
                                $edit = _encode(array('id'=>$val['Shortern']['_id'],'opr'=>'edit'));
                                $delete = _encode(array('id'=>$val['Shortern']['_id'],'opr'=>'delete'));
                                $viewAnalytics = _encode(array('key'=>$val['Shortern']['key'],'opr'=>'view'));
                                ?>
                                <tr>
                                    <td><?php echo $title; ?></td>
                                    <td><?php echo $val['Shortern']['key']; ?></td>
                                    <td><?php echo $val['Shortern']['domain'] != '' ? sprintf('<a href="%s" target="_blank">%s</a>','http://'.$domains[$val['Shortern']['domain']].'/'.$val['Shortern']['key'],$domains[$val['Shortern']['domain']]) : '--'; ?></td>
                                    <td><?php echo $val['Shortern']['redirect'] != '' ? $val['Shortern']['redirect'] : $false; ?></td>
                                    <td><?php echo $val['Shortern']['password'] != '' ? $true : $false; ?></td>
                                    <td><?php echo isset($val['Shortern']['counter']) ? $val['Shortern']['counter'] : 0; ?></td>
                                    <?php
                                    echo sprintf('<td><a href="javascript:void(0)" id="%d" value="%d" type="site" class="changestatus">%s</a></td>',
                                     $val['Shortern']['_id'],
                                     $val['Shortern']['status'],
                                     $val['Shortern']['status'] != 1 ? $false : $true );
                                     ?>
                                     <td>
                                        <?php
                                        echo $this->Html->link('Edit',array('controller'=>'shortern','action'=>'shorternopr','?'=>array('action'=>$edit)),array('class'=>'btn btn-info btn-sm')); 
                                        echo '&nbsp;';
                                        echo $this->Html->link('Delete',array('controller'=>'shortern','action'=>'shorternopr','?'=>array('action'=>$delete)),array('class'=>'btn btn-danger btn-sm confirm','message'=>'Are you sure to delete'));
                                        echo '&nbsp;';
                                        echo $this->Html->link('View Analytics',array('controller'=>'analytics','action'=>'request','?'=>array('action'=>$viewAnalytics)),array('class'=>'btn btn-warning btn-sm'));
                                        ?>
                                    </td>
                                </tr>
                                <?php }} else { ?>
                                <tr><td colspan="10">No Record Found</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <?php
                        $pagination = $this->Paginator->params();
                        $this->Paginator->options(array('update'=>'#content','evalScripts'=>true,'data'=>http_build_query($this->request->data),'method'=>'POST'));
                        if($pagination['pageCount'] > 1){
                            ?>
                <!-- Pagination start --->
                <div class="row pull-right">
                    <div class="col-xs-12">
                        <div class="dataTables_paginate paging_bootstrap">
                            <ul class="pagination">
                                <?php
                                echo $this->Paginator->prev('← '.__('Previous'),array('tag' => 'li','escape' => false),'<a href="javascript:void(0)">← '.__('Previous').'</a>',array('class' => 'prev disabled','tag' => 'li','escape' => false)); 
                                echo $this->Paginator->numbers(array('currentClass'=>'active','separator'=>false,'currentTag'=>'a','tag' => 'li','escape' => false,'modulus'=>3));
                                echo $this->Paginator->next(__('Next').' →',array('tag' => 'li'),'<a href="javascript:void(0)">'.__('Next').' → </a>',array('class' => 'next disabled','tag' => 'li','escape' => false));
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Pagination end -->
                <?php echo $this->Js->writeBuffer(); ?>
                <?php } ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>