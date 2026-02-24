<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Migrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Creates the Heartbeat API user group and service user.
 *
 * The service user is created without a password. Use the shop's
 * "forgot password" feature to set the initial password.
 */
final class Version20251223000001 extends AbstractMigration
{
    /** @throws Exception */
    public function __construct($version)
    {
        parent::__construct($version);

        $this->platform->registerDoctrineTypeMapping('enum', 'string');
    }

    public function getDescription(): string
    {
        return 'Create Heartbeat API user group and service user';
    }

    public function up(Schema $schema): void
    {
        // 1. Create the custom user group for Heartbeat API access
        $this->addSql("
            INSERT INTO oxgroups (OXID, OXACTIVE, OXTITLE, OXTITLE_1)
            VALUES ('oxsheartbeat_api', 1, 'Heartbeat API', 'Heartbeat API')
            ON DUPLICATE KEY UPDATE OXID = OXID
        ");

        // 2. Create the service user (password empty - must use forgot-password feature)
        // Using a deterministic OXID based on the username for idempotency
        $userId = md5('oxsheartbeat_api_user');
        // Note: OXPASSWORD must be non-empty for "forgot password" to work.
        // We generate a random placeholder hash that will never match any password.
        // The user must use "forgot password" to set a real password.
        $placeholderHash = bin2hex(random_bytes(32));

        $quotedUserId = $this->connection->quote($userId);
        $quotedPlaceholderHash = $this->connection->quote($placeholderHash);

        $this->addSql("
            INSERT INTO oxuser (OXID, OXACTIVE, OXRIGHTS, OXSHOPID, OXUSERNAME, OXPASSWORD, OXPASSSALT, OXFNAME, OXLNAME, OXCREATE, OXREGISTER, OXADDINFO)
            VALUES (
                {$quotedUserId},
                1,
                'user',
                1,
                'heartbeat-api@oxid-esales.com',
                {$quotedPlaceholderHash},
                '',
                'Heartbeat',
                'API User',
                NOW(),
                NOW(),
                'Service user for Heartbeat GraphQL API. Created by oxsheartbeat module.'
            )
            ON DUPLICATE KEY UPDATE OXID = OXID
        ");

        // 3. Link the user to the Heartbeat API group
        $linkId = md5($userId . 'oxsheartbeat_api');
        $quotedLinkId = $this->connection->quote($linkId);

        $this->addSql("
            INSERT INTO oxobject2group (OXID, OXSHOPID, OXOBJECTID, OXGROUPSID)
            VALUES ({$quotedLinkId}, 1, {$quotedUserId}, 'oxsheartbeat_api')
            ON DUPLICATE KEY UPDATE OXID = OXID
        ");
    }

    public function down(Schema $schema): void
    {
        $userId = md5('oxsheartbeat_api_user');
        $linkId = md5($userId . 'oxsheartbeat_api');

        $quotedUserId = $this->connection->quote($userId);
        $quotedLinkId = $this->connection->quote($linkId);

        // Remove the user-group link
        $this->addSql("DELETE FROM oxobject2group WHERE OXID = {$quotedLinkId}");

        // Remove the service user
        $this->addSql("DELETE FROM oxuser WHERE OXID = {$quotedUserId}");

        // Remove the user group
        $this->addSql("DELETE FROM oxgroups WHERE OXID = 'oxsheartbeat_api'");
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
