<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160323153320 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', 'integer', [
            'unsigned' => true,
            'autoincrement' => true
        ]);
        $table->addColumn('username', 'string', ['length' => 128]);
        $table->addColumn('email', 'string', ['length' => 128]);
        $table->addColumn('password', 'string', ['length' => 128]);
        $table->addColumn('role', 'string', ['length' => 128]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['username'], 'index_username');
        $table->addUniqueIndex(['email'], 'index_email');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('users');
    }
}