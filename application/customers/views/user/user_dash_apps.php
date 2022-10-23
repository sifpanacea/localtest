					<?php $a = 0;?>
					<?php if ($apps): ?>
					<?php foreach ($apps as $app):?>
					<tr id="" class="lists open_only" category="app">
					<td class="inbox-table-icon"><div class="checkbox"><label><input type="checkbox" class=checkbox style-2"><span></span></label></div></td>
					<td class="inbox-data-from hidden-xs hidden-sm"><div><?php echo ucfirst($app['app_name']) ;?></div></td>
					<td class="inbox-data-message"><div><span><span class="label bg-color-orange"></span><?php echo $app['app_description'];?></span> <?php echo $app['app_description'];?></div></td>
					<td><div><button id="app_open" aid="<?php echo $app['app_id'] ?>" url="<?php echo URLC.$app['app_id']."_con/create" ?>" class="btn btn-primary btn-xs replythis a_list"><i class="fa fa-pencil-square-o"></i> Open </button></div><td>
					<td class="inbox-data-date hidden-xs"><div class="_date"><?php echo $app['app_created'];?></div></td>
					</tr>
					<?php $a++;?>
					<?php endforeach;?>
					<?php endif ?>
