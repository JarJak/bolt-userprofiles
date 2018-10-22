<?php

declare(strict_types=1);

namespace Bolt\Extension\JarJak\UserProfiles\Storage\Schema\Table;

use Bolt\Storage\Database\Schema\Table\Users;

class UsersTable extends Users
{
    protected function addColumns(): void
    {
        parent::addColumns();

        $this->table->addColumn('avatar', 'string', [
            'length' => 256,
            'default' => null,
            'notnull' => false,
        ]);
        $this->table->addColumn('description', 'text', [
            'default' => null,
            'notnull' => false,
        ]);
    }
}
