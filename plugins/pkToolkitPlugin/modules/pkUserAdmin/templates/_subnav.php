<?php // Regular admins don't get to change which groups and permissions exist, ?>
<?php // that has serious consequences and doesn't make much sense unless you're a ?>
<?php // PHP developer extending the system. To add a user to one of the two standard groups ?>
<?php // (admin and editor) or other groups we already added to the system, just edit that user ?>

<?php if ($sf_user->isSuperAdmin()): ?>
  <ul>
	  <li class="dashboard"><h4><?php echo link_to('User Dashboard', 'pkUserAdmin/index') ?></h4></li>
	  <li><?php echo link_to('Add User', 'pkUserAdmin/new', array('class' => 'pk-btn icon pk-add')) ?></li>

	  <li class="dashboard"><h4><?php echo link_to('Group Dashboard', 'pkGroupAdmin/index') ?></h4></li>
	  <li><?php echo link_to('Add Group', 'pkGroupAdmin/new', array('class' => 'pk-btn icon pk-add')) ?></li>

	  <li class="dashboard"><h4><?php echo link_to('Permissions Dashboard', 'pkPermissionAdmin/index') ?></h4></li>
	  <li><?php echo link_to('Add Permission', 'pkPermissionAdmin/new', array('class' => 'pk-btn icon pk-add')) ?></li>
  </ul>
<?php endif ?>