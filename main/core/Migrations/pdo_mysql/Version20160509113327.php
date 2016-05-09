<?php

namespace Claroline\CoreBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2016/05/09 11:33:28
 */
class Version20160509113327 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE claro_panel_facet_role (
                id INT AUTO_INCREMENT NOT NULL, 
                role_id INT NOT NULL, 
                canOpen TINYINT(1) NOT NULL, 
                canEdit TINYINT(1) NOT NULL, 
                panelFacet_id INT NOT NULL, 
                INDEX IDX_A66BF654D60322AC (role_id), 
                INDEX IDX_A66BF654E99038C0 (panelFacet_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE claro_panel_facet_role 
            ADD CONSTRAINT FK_A66BF654D60322AC FOREIGN KEY (role_id) 
            REFERENCES claro_role (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE claro_panel_facet_role 
            ADD CONSTRAINT FK_A66BF654E99038C0 FOREIGN KEY (panelFacet_id) 
            REFERENCES claro_panel_facet (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE claro_field_facet 
            DROP isVisibleByOwner, 
            DROP isEditableByOwner
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE claro_panel_facet_role
        ");
        $this->addSql("
            ALTER TABLE claro_field_facet 
            ADD isVisibleByOwner TINYINT(1) NOT NULL, 
            ADD isEditableByOwner TINYINT(1) NOT NULL
        ");
    }
}