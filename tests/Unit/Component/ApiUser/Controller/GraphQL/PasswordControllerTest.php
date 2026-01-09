<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\ApiUser\Controller\GraphQL;

use OxidSupport\LoggingFramework\Component\ApiUser\Controller\GraphQL\PasswordController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

#[CoversClass(PasswordController::class)]
final class PasswordControllerTest extends TestCase
{
    public function testSetPasswordMethodHasMutationAttribute(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkSetPassword');
        $attributes = $this->getAttributeNames($reflection);

        $this->assertContains(
            'TheCodingMachine\GraphQLite\Annotations\Mutation',
            $attributes,
            "loggingFrameworkSetPassword must have #[Mutation] attribute"
        );
    }

    public function testSetPasswordUsesTokenAuthNotSessionAuth(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkSetPassword');
        $attributes = $this->getAttributeNames($reflection);

        // Should NOT have #[Logged] - uses token-based auth instead
        $this->assertNotContains(
            'TheCodingMachine\GraphQLite\Annotations\Logged',
            $attributes,
            "loggingFrameworkSetPassword must NOT have #[Logged] - uses token auth"
        );
    }

    public function testResetPasswordMethodHasMutationAttribute(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkResetPassword');
        $attributes = $this->getAttributeNames($reflection);

        $this->assertContains(
            'TheCodingMachine\GraphQLite\Annotations\Mutation',
            $attributes,
            "loggingFrameworkResetPassword must have #[Mutation] attribute"
        );
    }

    public function testResetPasswordRequiresAuthentication(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkResetPassword');
        $attributes = $this->getAttributeNames($reflection);

        $this->assertContains(
            'TheCodingMachine\GraphQLite\Annotations\Logged',
            $attributes,
            "loggingFrameworkResetPassword must have #[Logged] attribute"
        );
    }

    public function testResetPasswordRequiresSpecificRight(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkResetPassword');
        $attributes = $this->getAttributeNames($reflection);

        $this->assertContains(
            'TheCodingMachine\GraphQLite\Annotations\Right',
            $attributes,
            "loggingFrameworkResetPassword must have #[Right] attribute"
        );
    }

    public function testSetPasswordMethodIsPublic(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkSetPassword');

        $this->assertTrue($reflection->isPublic());
    }

    public function testResetPasswordMethodIsPublic(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkResetPassword');

        $this->assertTrue($reflection->isPublic());
    }

    public function testSetPasswordHasTokenParameter(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkSetPassword');
        $parameters = $reflection->getParameters();

        $parameterNames = array_map(fn($p) => $p->getName(), $parameters);

        $this->assertContains('token', $parameterNames);
    }

    public function testSetPasswordHasPasswordParameter(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkSetPassword');
        $parameters = $reflection->getParameters();

        $parameterNames = array_map(fn($p) => $p->getName(), $parameters);

        $this->assertContains('password', $parameterNames);
    }

    public function testSetPasswordReturnsBool(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkSetPassword');
        $returnType = $reflection->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('bool', $returnType->getName());
    }

    public function testResetPasswordReturnsString(): void
    {
        $reflection = new ReflectionMethod(PasswordController::class, 'loggingFrameworkResetPassword');
        $returnType = $reflection->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('string', $returnType->getName());
    }

    private function getAttributeNames(ReflectionMethod $reflection): array
    {
        return array_map(
            fn($attr) => $attr->getName(),
            $reflection->getAttributes()
        );
    }
}
