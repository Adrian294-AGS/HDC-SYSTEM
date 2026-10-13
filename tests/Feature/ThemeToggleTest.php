<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ThemeToggleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string}>
     */
    public static function roles(): array
    {
        return [
            'student' => ['student'],
            'counselor' => ['counselor'],
            'director' => ['director'],
        ];
    }

    #[DataProvider('roles')]
    public function test_theme_toggle_renders_in_the_nav_for_every_role(string $role): void
    {
        $user = User::factory()->{$role}()->create();

        $html = $this->actingAs($user)->get(route($user->dashboardRoute()))->assertOk()->getContent();

        // Desktop nav + mobile menu
        $this->assertSame(2, substr_count($html, 'data-theme-toggle'));
        $this->assertStringContainsString("localStorage.getItem('theme')", $html);
        $this->assertStringContainsString('Switch to dark mode', $html);
        $this->assertStringContainsString('Switch to light mode', $html);
    }

    public function test_theme_toggle_and_no_flash_script_are_on_guest_pages(): void
    {
        foreach (['/', route('login'), route('register')] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringContainsString('data-theme-toggle', $html);
            $this->assertStringContainsString('prefers-color-scheme: dark', $html);
            $this->assertLessThan(
                strpos($html, '</head>'),
                strpos($html, "localStorage.getItem('theme')"),
                'Theme script must run inside <head>, before the page renders.',
            );
        }
    }
}
