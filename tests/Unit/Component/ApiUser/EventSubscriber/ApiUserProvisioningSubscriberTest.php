<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\ApiUser\EventSubscriber;

use OxidSupport\Heartbeat\Component\ApiUser\Event\ApiUserProvisioningRequestedEvent;
use OxidSupport\Heartbeat\Component\ApiUser\EventSubscriber\ApiUserProvisioningSubscriber;
use OxidSupport\Heartbeat\Component\ApiUser\Service\ApiUserProvisioningServiceInterface;
use OxidSupport\Heartbeat\Component\ApiUser\Service\SetupTokenServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ApiUserProvisioningSubscriberTest extends TestCase
{
    public function testProvisionSeedsTheApiUserAndThenReconcilesTheSetupToken(): void
    {
        $calls = [];

        $provisioning = $this->createMock(ApiUserProvisioningServiceInterface::class);
        $provisioning->method('ensureApiUser')->willReturnCallback(
            static function () use (&$calls): void {
                $calls[] = 'ensureApiUser';
            }
        );

        $setupToken = $this->createMock(SetupTokenServiceInterface::class);
        $setupToken->method('ensureSetupToken')->willReturnCallback(
            static function () use (&$calls): void {
                $calls[] = 'ensureSetupToken';
            }
        );

        $subscriber = new ApiUserProvisioningSubscriber($provisioning, $setupToken);
        $subscriber->provision();

        // The token is reconciled against the service user, so the user has to exist first.
        $this->assertSame(['ensureApiUser', 'ensureSetupToken'], $calls);
    }

    public function testSubscriberIsWiredToTheProvisioningEvent(): void
    {
        // The activation hook dispatches this event and fails when nothing listens, so the
        // event name and the handler have to stay in sync with it. See OXS-3377.
        $this->assertSame(
            [ApiUserProvisioningRequestedEvent::NAME => 'provision'],
            ApiUserProvisioningSubscriber::getSubscribedEvents()
        );

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new ApiUserProvisioningSubscriber(
            $this->createMock(ApiUserProvisioningServiceInterface::class),
            $this->createMock(SetupTokenServiceInterface::class)
        ));

        $this->assertTrue($dispatcher->hasListeners(ApiUserProvisioningRequestedEvent::NAME));
    }

    public function testEventNameIsTheClassName(): void
    {
        $this->assertSame(
            ApiUserProvisioningRequestedEvent::class,
            ApiUserProvisioningRequestedEvent::NAME
        );
    }
}
