<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string, string}>
     */
    public static function roleDashboards(): array
    {
        return [
            'student' => ['student', '/student/dashboard'],
            'counselor' => ['counselor', '/counselor/dashboard'],
            'director' => ['director', '/director/dashboard'],
        ];
    }

    private function userWithRole(string $role): User
    {
        return User::factory()->{$role}()->create();
    }

    #[DataProvider('roleDashboards')]
    public function test_login_redirects_to_the_role_dashboard(string $role, string $dashboard): void
    {
        $user = $this->userWithRole($role);

        $response = $this->post('/login', ['username' => $user->username, 'password' => 'password']);

        $response->assertRedirect($dashboard);
    }

    #[DataProvider('roleDashboards')]
    public function test_generic_dashboard_redirects_to_the_role_dashboard(string $role, string $dashboard): void
    {
        $this->actingAs($this->userWithRole($role))
            ->get('/dashboard')
            ->assertRedirect($dashboard);
    }

    #[DataProvider('roleDashboards')]
    public function test_users_see_their_own_dashboard(string $role, string $dashboard): void
    {
        $user = $this->userWithRole($role);

        $this->actingAs($user)
            ->get($dashboard)
            ->assertOk()
            ->assertSee('Welcome, '.$user->name)
            ->assertSee(ucfirst($role));
    }

    #[DataProvider('roleDashboards')]
    public function test_users_cannot_open_other_role_dashboards(string $role, string $ownDashboard): void
    {
        $user = $this->userWithRole($role);

        foreach (self::roleDashboards() as [, $dashboard]) {
            if ($dashboard !== $ownDashboard) {
                $this->actingAs($user)->get($dashboard)->assertForbidden();
            }
        }
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/director/dashboard')->assertRedirect('/login');
    }

    public function test_accessible_college_ids_depend_on_role(): void
    {
        [$cit, $cea, $cba] = College::factory()->count(3)->create();

        $counselor = User::factory()->counselor([$cit, $cea])->create();
        $director = User::factory()->director()->create();
        $student = User::factory()->student()->create();

        $this->assertEqualsCanonicalizing([$cit->id, $cea->id], $counselor->accessibleCollegeIds()->all());
        $this->assertEqualsCanonicalizing(
            College::pluck('id')->all(),
            $director->accessibleCollegeIds()->all(),
        );
        $this->assertContains($cba->id, $director->accessibleCollegeIds());
        $this->assertTrue($student->accessibleCollegeIds()->isEmpty());
    }

    public function test_counselor_only_sees_students_in_assigned_colleges(): void
    {
        $assigned = Program::factory()->create();
        $other = Program::factory()->create();

        $counselor = User::factory()->counselor([$assigned->college])->create();
        $visibleStudent = User::factory()->student($assigned)->create();
        $hiddenStudent = User::factory()->student($other)->create();

        $visibleIds = User::studentsVisibleTo($counselor)->pluck('id');

        $this->assertTrue($visibleIds->contains($visibleStudent->id));
        $this->assertFalse($visibleIds->contains($hiddenStudent->id));
        $this->assertCount(2, User::studentsVisibleTo(User::factory()->director()->create())->get());
    }
}
