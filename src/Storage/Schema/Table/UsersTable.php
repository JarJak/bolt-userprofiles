<?php

namespace Bolt\Extension\UserProfile\Storage\Schema\Table;

use Bolt\Storage\Database\Schema\Table\Users;

class UsersTable extends Users
{
    protected function addColumns()
    {
        parent::addColumns();

        $this->table->addColumn('avatar', 'string', array('length' => 256, 'default' => null, 'notnull' => false));
        $this->table->addColumn('description', 'text', array('default' => null, 'notnull' => false));
    }
}
