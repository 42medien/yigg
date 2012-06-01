<?php

include(dirname(__FILE__).'/../bootstrap/Doctrine.php');
$t = new lime_test(4, new lime_output_color());

$t->info("Comment::hasDeletePermissions");
  $comment = new Comment();
  $comment->fromArray(array(
    "user_id" => 2));

  $user = new User();
  $user->id = 1;
  $t->ok(false === $comment->hasDeletePermissions($user), "Comment is not editable from another user.");

  $user = new User();
  $user->id = 2;
  $t->ok($comment->hasDeletePermissions($user), "Comment is editable for the owner.");

  $user = new User();
  $user->id = 2;


  $user = new User();
  $user->id = 2;
  $permission = new UserPermission();
  $permission->permission_level = 1;
  $user->Permissions->add($permission);
  $t->ok($comment->hasDeletePermissions($user), "Own Comment is editable for admin.");

  $user = new User();
  $user->id = 3;
  $permission = new UserPermission();
  $permission->permission_level = 1;
  $user->Permissions->add($permission);
  $t->ok($comment->hasDeletePermissions($user), "Foreign Comment is editable for admin.");