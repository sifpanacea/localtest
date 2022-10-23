
					<?php $a = 0;?>
					<?php if ($search_data): ?>
					<tr>
						<th><?php echo lang('user_web_app_name');?></th>
						<th><?php echo lang('user_web_app_des');?></th>
						<th><?php echo lang('user_web_app_created');?></th>
						<th><?php echo lang('user_web_docs_search');?></th>
					</tr>
					<?php foreach ($search_data as $app):?>
					<tr class="lists">
						<td><?php echo ucfirst($app['app_name']) ;?></td>
						<td><?php echo $app['app_description'];?></td>
						<td><?php echo $app['app_created'];?></td>
						<td><?php echo anchor(URLC.$app['app_id']."_con",lang('user_web_docs_search_link'), array('class' => 'open_doc btn btn-xs btn-primary') ) ;?></td>
					</tr>
					<?php $a++;?>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo lang('webview_no_apps');?>
        			</p>
        			<?php endif ?>
							<!-- end widget content -->
		 