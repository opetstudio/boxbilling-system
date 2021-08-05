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

            $this->insert('prices', [
                [
                    'title' => 'Bayi',
                    'description' => '938 Pengguna Terdaft',
                    'price' => 14900,
                ],
                [
                    'title' => 'Pelajar',
                    'description' => '938 Pengguna Terdaft',
                    'price' => 23450,
                ],
                [
                    'title' => 'Personal',
                    'description' => '2X RESOURCE POWER',
                    'price' => 38900,
                ],
                [
                    'title' => 'Bisnis',
                    'description' => '3552 Pengguna Terdaftar',
                    'price' => 65900,
                ],
            ]);
        
    }

    protected function down(): void
    {
        $this->table('prices')
            ->drop();
    }
}
