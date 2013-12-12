<?php

/**
 * User form.
 *
 * @package    yigg
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserForm extends BaseUserForm
{
    public function configure() {
        $this->useFields(array('username', 'email', 'block_post', 'status'));
    }
}
