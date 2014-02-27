<?php

namespace Claroline\CoreBundle\Migrations\sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2014/02/26 02:51:00
 */
class Version20140226145055 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE claro_badge 
            ADD is_expiring BIT NOT NULL
        ");
        $this->addSql("
            ALTER TABLE claro_badge 
            ADD CONSTRAINT DF_74F39F0F_869FAB69 DEFAULT '0' FOR is_expiring
        ");
        $this->addSql("
            ALTER TABLE claro_badge 
            ADD expire_duration INT
        ");
        $this->addSql("
            ALTER TABLE claro_badge 
            ADD expire_period SMALLINT
        ");
        $this->addSql("
            ALTER TABLE claro_badge 
            DROP COLUMN expired_at
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE claro_badge 
            ADD expired_at DATETIME2(6)
        ");
        $this->addSql("
            ALTER TABLE claro_badge 
            DROP COLUMN is_expiring
        ");
        $this->addSql("
            ALTER TABLE claro_badge 
            DROP COLUMN expire_duration
        ");
        $this->addSql("
            ALTER TABLE claro_badge 
            DROP COLUMN expire_period
        ");
    }
}