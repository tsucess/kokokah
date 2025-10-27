<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\View\View;

class ComponentsTest extends TestCase
{
    /**
     * Test Button Component - Primary Variant
     */
    public function test_button_component_primary_variant()
    {
        $this->assertTrue(file_exists(resource_path('views/components/button.blade.php')));
    }

    /**
     * Test Button Component - Secondary Variant
     */
    public function test_button_component_secondary_variant()
    {
        $content = file_get_contents(resource_path('views/components/button.blade.php'));
        $this->assertStringContainsString('secondaryButton', $content);
    }

    /**
     * Test Button Component - Disabled State
     */
    public function test_button_component_disabled()
    {
        $content = file_get_contents(resource_path('views/components/button.blade.php'));
        $this->assertStringContainsString('@disabled', $content);
    }

    /**
     * Test Card Component
     */
    public function test_card_component()
    {
        $this->assertTrue(file_exists(resource_path('views/components/card.blade.php')));
        $content = file_get_contents(resource_path('views/components/card.blade.php'));
        $this->assertStringContainsString('card-header', $content);
        $this->assertStringContainsString('card-body', $content);
    }

    /**
     * Test Alert Component - Success
     */
    public function test_alert_component_success()
    {
        $this->assertTrue(file_exists(resource_path('views/components/alert.blade.php')));
        $content = file_get_contents(resource_path('views/components/alert.blade.php'));
        $this->assertStringContainsString('alert-success', $content);
    }

    /**
     * Test Alert Component - Dismissible
     */
    public function test_alert_component_dismissible()
    {
        $content = file_get_contents(resource_path('views/components/alert.blade.php'));
        $this->assertStringContainsString('alert-dismissible', $content);
        $this->assertStringContainsString('btn-close', $content);
    }

    /**
     * Test Form Input Component
     */
    public function test_form_input_component()
    {
        $this->assertTrue(file_exists(resource_path('views/components/form-input.blade.php')));
        $content = file_get_contents(resource_path('views/components/form-input.blade.php'));
        $this->assertStringContainsString('form-control', $content);
        $this->assertStringContainsString('form-label', $content);
    }

    /**
     * Test Form Select Component
     */
    public function test_form_select_component()
    {
        $this->assertTrue(file_exists(resource_path('views/components/form-select.blade.php')));
        $content = file_get_contents(resource_path('views/components/form-select.blade.php'));
        $this->assertStringContainsString('form-select', $content);
    }

    /**
     * Test Badge Component
     */
    public function test_badge_component()
    {
        $this->assertTrue(file_exists(resource_path('views/components/badge.blade.php')));
        $content = file_get_contents(resource_path('views/components/badge.blade.php'));
        $this->assertStringContainsString('badge', $content);
    }

    /**
     * Test Badge Component - Pill Style
     */
    public function test_badge_component_pill()
    {
        $content = file_get_contents(resource_path('views/components/badge.blade.php'));
        $this->assertStringContainsString('rounded-pill', $content);
    }

    /**
     * Test Layout Files
     */
    public function test_dashboard_layout_exists()
    {
        $this->assertTrue(file_exists(resource_path('views/layouts/dashboard.blade.php')));
    }

    public function test_app_layout_exists()
    {
        $this->assertTrue(file_exists(resource_path('views/layouts/app.blade.php')));
    }

    /**
     * Test Tailwind Configuration
     */
    public function test_tailwind_config_exists()
    {
        $this->assertTrue(file_exists(base_path('tailwind.config.js')));
    }
}

