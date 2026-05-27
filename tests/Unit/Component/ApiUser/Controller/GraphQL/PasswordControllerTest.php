<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\ApiUser\Controller\GraphQL;

use OxidSupport\Heartbeat\Component\ApiUser\Controller\GraphQL\PasswordController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

#[CoversClass(PasswordController::class)]
final class PasswordControllerTest extends TestCase
{
    public function testSetPasswordMethodHasMutationAnnotation(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatSetPassword');

        $this->assertStringContainsString(
            '@Mutation',
            $reflection->getDocComment(),
            "heartbeatSetPassword must have @Mutation annotation"
        );
    }

    public function testSetPasswordUsesTokenAuthNotSessionAuth(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatSetPassword');

        // Should NOT have @Logged - uses token-based auth instead
        $this->assertStringNotContainsString(
            '@Logged',
            $reflection->getDocComment(),
            "heartbeatSetPassword must NOT have @Logged - uses token auth"
        );
    }

    public function testResetPasswordMethodHasMutationAnnotation(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatResetPassword');

        $this->assertStringContainsString(
            '@Mutation',
            $reflection->getDocComment(),
            "heartbeatResetPassword must have @Mutation annotation"
        );
    }

    public function testResetPasswordRequiresAuthentication(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatResetPassword');

        $this->assertStringContainsString(
            '@Logged',
            $reflection->getDocComment(),
            "heartbeatResetPassword must have @Logged annotation"
        );
    }

    public function testResetPasswordRequiresSpecificRight(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatResetPassword');

        $this->assertStringContainsString(
            '@Right',
            $reflection->getDocComment(),
            "heartbeatResetPassword must have @Right annotation"
        );
    }

    public function testSetPasswordMethodIsPublic(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatSetPassword');

        $this->assertTrue($reflection->isPublic());
    }

    public function testResetPasswordMethodIsPublic(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatResetPassword');

        $this->assertTrue($reflection->isPublic());
    }

    public function testSetPasswordHasTokenParameter(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatSetPassword');
        $parameters = $reflection->getParameters();

        $parameterNames = array_map(fn($p) => $p->getName(), $parameters);

        $this->assertContains('token', $parameterNames);
    }

    public function testSetPasswordHasPasswordParameter(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatSetPassword');
        $parameters = $reflection->getParameters();

        $parameterNames = array_map(fn($p) => $p->getName(), $parameters);

        $this->assertContains('password', $parameterNames);
    }

    public function testSetPasswordReturnsBool(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatSetPassword');
        $returnType = $reflection->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('bool', $returnType->getName());
    }

    public function testResetPasswordReturnsString(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'heartbeatResetPassword');
        $returnType = $reflection->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('string', $returnType->getName());
    }
}
