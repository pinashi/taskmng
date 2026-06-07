<?php

declare(strict_types=1);

use App\Validators\TaskValidator;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for TaskValidator class.
 */
class TaskValidationTest extends TestCase {
    /**
     * @var TaskValidator $validator TaskValidator instance
     */
    private TaskValidator $validator;

    /**
     * Set up a fresh TaskValidator instance before each test.
     */
    protected function setUp(): void {
        $this->validator = new TaskValidator();
    }

    /**
     * Test that empty title fails validation.
     */
    public function testEmptyTitleFailsValidation(): void {
        $errors = $this->validator->validate(['title' => '']);
        $this->assertNotEmpty($errors);
    }

    /**
     * Test that title containing only whitespace fails validation.
     */
    public function testWhitespaceTitleFailsValidation(): void {
        $errors = $this->validator->validate(['title' => ' ']);
        $this->assertNotEmpty($errors);
    }

    /**
     * Test that title exceeding 255 characters fails validation.
     */
    public function testTitleTooLongFailsValidation(): void
    {
        $errors = $this->validator->validate(['title' => str_repeat('a', 256)]);
        $this->assertNotEmpty($errors);
    }

    /**
     * Test that valid data passes validation.
     */
    public function testValidDataPassesValidation(): void
    {
        $errors = $this->validator->validate(['title' => 'My task']);
        $this->assertEmpty($errors);
    }

    /**
     * Test that missing title fails validation.
     */
    public function testMissingTitleFailsValidation(): void
    {
        $errors = $this->validator->validate([]);
        $this->assertNotEmpty($errors);
    }

    /**
     * Test that title with exactly 255 characters passes validation.
     */
    public function testTitleExactly255CharsPassesValidation(): void
    {
        $errors = $this->validator->validate(['title' => str_repeat('a', 255)]);
        $this->assertEmpty($errors);
    }
}