<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221206212431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create User table with ulid, username, email, password, roles, registered_at';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE "user" (
                ulid UUID NOT NULL,
                roles JSON NOT NULL,
                username VARCHAR(24) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(1024) NOT NULL,
                registered_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(ulid)
            )
        SQL);
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".ulid IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN "user".registered_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "user"');
    }
}
