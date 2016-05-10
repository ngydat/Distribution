<?php

namespace Innova\MediaResourceBundle\Migrations\ibm_db2;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/05/27 09:11:37
 */
class Version20150527091131 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL, 
                media_resource_id INTEGER NOT NULL, 
                \"start\" DOUBLE PRECISION NOT NULL, 
                \"end\" DOUBLE PRECISION NOT NULL, 
                note CLOB(1M) DEFAULT NULL, 
                uuid VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_BB5F60D07E5AEFB6 ON innova_media_resource_region (media_resource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL, 
                media_resource_id INTEGER NOT NULL, 
                url VARCHAR(255) NOT NULL, 
                \"type\" VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_5C0330F07E5AEFB6 ON innova_media_resource_media (media_resource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region_config (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL, 
                region_id INTEGER NOT NULL, 
                has_loop SMALLINT NOT NULL, 
                has_backward SMALLINT NOT NULL, 
                has_rate SMALLINT NOT NULL, 
                help_text VARCHAR(255) NOT NULL, 
                help_region_uuid VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198260155 ON innova_media_resource_region_config (region_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                published SMALLINT NOT NULL, 
                modified SMALLINT NOT NULL, 
                resourceNode_id INTEGER DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_AC4F9D03B87FAB32 ON innova_media_resource (resourceNode_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_playlist_region (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL, 
                region_id INTEGER DEFAULT NULL, 
                playlist_id INTEGER NOT NULL, 
                ordering INTEGER NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD98260155 ON innova_media_resource_playlist_region (region_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD6BBD148 ON innova_media_resource_playlist_region (playlist_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_playlist (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL, 
                media_resource_id INTEGER DEFAULT NULL, 
                name VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_A8D71DAD7E5AEFB6 ON innova_media_resource_playlist (media_resource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_context (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL, 
                media_resource_id INTEGER NOT NULL, 
                playlist_id INTEGER DEFAULT NULL, 
                hasActiveListening SMALLINT DEFAULT '0' NOT NULL, 
                hasAutoPause SMALLINT DEFAULT '0' NOT NULL, 
                hasLiveListening SMALLINT DEFAULT '0' NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_DD5CBE327E5AEFB6 ON innova_media_resource_context (media_resource_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_DD5CBE326BBD148 ON innova_media_resource_context (playlist_id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD CONSTRAINT FK_BB5F60D07E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD CONSTRAINT FK_5C0330F07E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            ADD CONSTRAINT FK_FEBE556198260155 FOREIGN KEY (region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource 
            ADD CONSTRAINT FK_AC4F9D03B87FAB32 FOREIGN KEY (resourceNode_id) 
            REFERENCES claro_resource_node (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            ADD CONSTRAINT FK_DC6F27BD98260155 FOREIGN KEY (region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            ADD CONSTRAINT FK_DC6F27BD6BBD148 FOREIGN KEY (playlist_id) 
            REFERENCES innova_media_resource_playlist (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            ADD CONSTRAINT FK_A8D71DAD7E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_context 
            ADD CONSTRAINT FK_DD5CBE327E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_context 
            ADD CONSTRAINT FK_DD5CBE326BBD148 FOREIGN KEY (playlist_id) 
            REFERENCES innova_media_resource_playlist (id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            DROP FOREIGN KEY FK_FEBE556198260155
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            DROP FOREIGN KEY FK_DC6F27BD98260155
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            DROP FOREIGN KEY FK_BB5F60D07E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            DROP FOREIGN KEY FK_5C0330F07E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            DROP FOREIGN KEY FK_A8D71DAD7E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_context 
            DROP FOREIGN KEY FK_DD5CBE327E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            DROP FOREIGN KEY FK_DC6F27BD6BBD148
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_context 
            DROP FOREIGN KEY FK_DD5CBE326BBD148
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region_config
        ");
        $this->addSql("
            DROP TABLE innova_media_resource
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_playlist_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_playlist
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_context
        ");
    }
}