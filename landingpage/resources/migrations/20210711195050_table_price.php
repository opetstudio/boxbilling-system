<?php

use Phoenix\Migration\AbstractMigration;

class TablePrice extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('prices', 'id')
            ->setCharset('utf8mb4')
            ->setCollation('utf8mb4_unicode_ci')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('title', 'string', ['null' => true])
            ->addColumn('description', 'string', ['null' => true])
            ->addColumn('price', 'integer', ['null' => true])
            ->create();
    }

    protected function down(): void
    {
        $this->table('prices')
            ->drop();
    }
}
