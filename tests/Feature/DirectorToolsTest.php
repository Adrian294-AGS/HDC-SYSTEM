<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\College;
use App\Models\CumulativeRecord;
use App\Models\Program;
use App\Models\User;
use App\Notifications\RecordSubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DirectorToolsTest extends TestCase
{
    use RefreshDatabase;

    private User $director;

    private College $cit;

    private College $con;

    protected function setUp(): void
    {
        parent::setUp();

        $this->director = User::factory()->director()->create();
        $this->cit = College::factory()->create(['code' => 'CIT', 'name' => 'College of Information Technology']);
        $this->con = College::factory()->create(['code' => 'CON', 'name' => 'College of Nursing']);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function directorPages(): array
    {
        return [
            'counselors list' => ['GET', 'director.counselors.index'],
            'new counselor' => ['GET', 'director.counselors.create'],
            'store counselor' => ['POST', 'director.counselors.store'],
            'colleges list' => ['GET', 'director.colleges.index'],
            'new college' => ['GET', 'director.colleges.create'],
            'store college' => ['POST', 'director.colleges.store'],
            'store program' => ['POST', 'director.programs.store'],
        ];
    }

    #[DataProvider('directorPages')]
    public function test_only_the_director_can_use_director_pages(string $method, string $route): void
    {
        $counselor = User::factory()->counselor([$this->cit])->create();
        $student = User::factory()->student()->create();

        foreach ([$counselor, $student] as $user) {
            $this->actingAs($user)->call($method, route($route))->assertForbidden();
        }

        if ($method === 'GET') {
            $this->actingAs($this->director)->get(route($route))->assertOk();
        }
    }

    public function test_counselor_and_student_cannot_edit_deactivate_or_delete(): void
    {
        $counselor = User::factory()->counselor([$this->cit])->create();
        $program = Program::factory()->for($this->cit)->create();

        foreach ([$counselor, User::factory()->student()->create()] as $user) {
            $this->actingAs($user)->get(route('director.counselors.edit', $counselor))->assertForbidden();
            $this->actingAs($user)->patch(route('director.counselors.deactivate', $counselor))->assertForbidden();
            $this->actingAs($user)->delete(route('director.colleges.destroy', $this->con))->assertForbidden();
            $this->actingAs($user)->delete(route('director.programs.destroy', $program))->assertForbidden();
        }

        $this->assertTrue($counselor->fresh()->is_active);
        $this->assertNotNull($this->con->fresh());
    }

    public function test_director_creates_a_counselor_who_can_log_in_and_sees_only_assigned_colleges(): void
    {
        $citStudent = User::factory()->student(Program::factory()->for($this->cit)->create())->create(['last_name' => 'Ilagan']);
        $conStudent = User::factory()->student(Program::factory()->for($this->con)->create())->create(['last_name' => 'Navarro']);

        $this->actingAs($this->director)->post(route('director.counselors.store'), [
            'last_name' => 'Reyes',
            'first_name' => 'Rosa',
            'email' => 'rosa@uls.test',
            'password' => 'Temp-pass-123',
            'colleges' => [$this->cit->id],
        ])->assertSessionHasNoErrors()->assertRedirect(route('director.counselors.index'));

        $counselor = User::where('email', 'rosa@uls.test')->firstOrFail();
        $this->assertSame(Role::Counselor, $counselor->role);
        $this->assertTrue($counselor->must_change_password);
        $this->assertSame([$this->cit->id], $counselor->colleges->pluck('id')->all());

        auth()->logout();
        $this->post('/login', ['email' => 'rosa@uls.test', 'password' => 'Temp-pass-123'])->assertRedirect();
        $this->assertAuthenticatedAs($counselor);
        $this->assertNotNull($counselor->fresh()->last_login_at);

        $this->put(route('password.change.update'), [
            'current_password' => 'Temp-pass-123',
            'password' => 'My-own-pass-456',
            'password_confirmation' => 'My-own-pass-456',
        ])->assertRedirect(route('counselor.dashboard'));

        $this->get(route('staff.records.index'))
            ->assertOk()
            ->assertSee($citStudent->last_name)
            ->assertDontSee($conStudent->last_name);
    }

    public function test_a_role_in_the_request_is_ignored(): void
    {
        $this->actingAs($this->director)->post(route('director.counselors.store'), [
            'last_name' => 'Sneaky',
            'first_name' => 'Sam',
            'email' => 'sam@uls.test',
            'password' => 'Temp-pass-123',
            'colleges' => [$this->cit->id],
            'role' => 'director',
            'is_active' => '0',
            'must_change_password' => '0',
        ])->assertSessionHasNoErrors();

        $user = User::where('email', 'sam@uls.test')->firstOrFail();
        $this->assertSame(Role::Counselor, $user->role);
        $this->assertTrue($user->is_active);
        $this->assertTrue($user->must_change_password);

        $counselor = User::factory()->counselor([$this->cit])->create();
        $this->actingAs($this->director)->put(route('director.counselors.update', $counselor), [
            'last_name' => 'Same',
            'first_name' => 'Person',
            'email' => $counselor->email,
            'colleges' => [$this->cit->id],
            'role' => 'director',
        ])->assertSessionHasNoErrors();
        $this->assertSame(Role::Counselor, $counselor->fresh()->role);
    }

    public function test_counselor_needs_at_least_one_college(): void
    {
        $this->actingAs($this->director)->post(route('director.counselors.store'), [
            'last_name' => 'No',
            'first_name' => 'College',
            'email' => 'nocollege@uls.test',
            'password' => 'Temp-pass-123',
            'colleges' => [],
        ])->assertSessionHasErrors('colleges');

        $this->assertDatabaseMissing('users', ['email' => 'nocollege@uls.test']);
    }

    public function test_director_can_edit_assignments_and_reset_the_password(): void
    {
        $counselor = User::factory()->counselor([$this->cit])->create();

        $this->actingAs($this->director)->put(route('director.counselors.update', $counselor), [
            'last_name' => 'Renamed',
            'first_name' => 'Counselor',
            'email' => 'renamed@uls.test',
            'password' => 'New-temp-789',
            'colleges' => [$this->con->id],
        ])->assertSessionHasNoErrors();

        $counselor->refresh();
        $this->assertSame('renamed@uls.test', $counselor->email);
        $this->assertSame('Counselor Renamed', $counselor->name);
        $this->assertSame([$this->con->id], $counselor->colleges->pluck('id')->all());
        $this->assertTrue(Hash::check('New-temp-789', $counselor->password));
        $this->assertTrue($counselor->must_change_password);
    }

    public function test_temporary_password_forces_a_change_before_any_other_page(): void
    {
        $counselor = User::factory()->counselor([$this->cit])->create(['must_change_password' => true]);

        foreach ([route('counselor.dashboard'), route('staff.records.index'), route('profile.edit')] as $url) {
            $this->actingAs($counselor)->get($url)->assertRedirect(route('password.change'));
        }
        $this->actingAs($counselor)->get(route('password.change'))->assertOk()->assertSee('Change your temporary password');

        $this->actingAs($counselor)->put(route('password.change.update'), [
            'current_password' => 'password',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('password');

        $this->actingAs($counselor)->put(route('password.change.update'), [
            'current_password' => 'password',
            'password' => 'Brand-new-pass-1',
            'password_confirmation' => 'Brand-new-pass-1',
        ])->assertRedirect(route('counselor.dashboard'));

        $this->assertFalse($counselor->fresh()->must_change_password);
        $this->actingAs($counselor->fresh())->get(route('counselor.dashboard'))->assertOk();
    }

    public function test_deactivated_counselor_cannot_log_in_and_sees_a_clear_message(): void
    {
        $counselor = User::factory()->counselor([$this->cit])->create();

        $this->actingAs($this->director)->patch(route('director.counselors.deactivate', $counselor))->assertRedirect();
        $this->assertFalse($counselor->fresh()->is_active);
        auth()->logout();

        $this->from('/login')
            ->post('/login', ['email' => $counselor->email, 'password' => 'password'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'This account has been deactivated. Please contact the HDC Director.']);
        $this->assertGuest();

        $this->followingRedirects()
            ->from('/login')
            ->post('/login', ['email' => $counselor->email, 'password' => 'password'])
            ->assertSee('This account has been deactivated');
    }

    public function test_deactivated_counselor_is_signed_out_of_an_existing_session(): void
    {
        $counselor = User::factory()->counselor([$this->cit])->create();
        $counselor->forceFill(['is_active' => false])->save();

        $this->actingAs($counselor)->get(route('counselor.dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_reactivated_counselor_can_log_in_again(): void
    {
        $counselor = User::factory()->counselor([$this->cit])->create();
        $counselor->forceFill(['is_active' => false])->save();

        $this->actingAs($this->director)->patch(route('director.counselors.reactivate', $counselor))->assertRedirect();
        auth()->logout();

        $this->post('/login', ['email' => $counselor->email, 'password' => 'password']);
        $this->assertAuthenticatedAs($counselor->fresh());
    }

    public function test_deactivated_counselor_gets_no_notifications_and_is_hidden_from_counts(): void
    {
        Notification::fake();
        $active = User::factory()->counselor([$this->cit])->create(['first_name' => 'Active']);
        $inactive = User::factory()->counselor([$this->cit])->create(['first_name' => 'Inactivecounselor']);
        $inactive->forceFill(['is_active' => false])->save();

        $student = User::factory()->student(Program::factory()->for($this->cit)->create())->create();
        $record = CumulativeRecord::factory()->for($student, 'student')->completed()->create();

        $this->actingAs($student)
            ->post(route('student.record.submit', $record), ['consent' => '1', 'consent_name' => 'Student'])
            ->assertSessionHasNoErrors();

        Notification::assertSentTo([$this->director, $active], RecordSubmitted::class);
        Notification::assertNotSentTo($inactive, RecordSubmitted::class);

        $this->actingAs($this->director)
            ->get(route('director.colleges.index'))
            ->assertSee($active->name)
            ->assertDontSee('Inactivecounselor');
    }

    public function test_counselor_list_shows_status_colleges_and_last_login(): void
    {
        $counselor = User::factory()->counselor([$this->cit, $this->con])->create(['last_name' => 'Listed']);
        $counselor->forceFill(['is_active' => false, 'last_login_at' => now()->subDay()])->save();

        $this->actingAs($this->director)
            ->get(route('director.counselors.index'))
            ->assertOk()
            ->assertSee('Listed')
            ->assertSee('CIT')
            ->assertSee('CON')
            ->assertSee('Inactive')
            ->assertSee('1 day ago')
            ->assertSee('Reactivate');
    }

    public function test_last_login_is_recorded_for_every_user(): void
    {
        $student = User::factory()->student()->create();
        $this->assertNull($student->last_login_at);

        $this->post('/login', ['email' => $student->email, 'password' => 'password']);

        $this->assertNotNull($student->fresh()->last_login_at);
    }

    public function test_director_creates_and_edits_colleges_with_unique_codes(): void
    {
        $this->actingAs($this->director)
            ->post(route('director.colleges.store'), ['code' => 'CBA', 'name' => 'College of Business'])
            ->assertSessionHasNoErrors();
        $cba = College::where('code', 'CBA')->firstOrFail();

        $this->actingAs($this->director)
            ->post(route('director.colleges.store'), ['code' => 'CIT', 'name' => 'Duplicate'])
            ->assertSessionHasErrors(['code' => 'Another college already uses this code.']);

        $this->actingAs($this->director)
            ->put(route('director.colleges.update', $cba), ['code' => 'CBA', 'name' => 'College of Business and Accountancy'])
            ->assertSessionHasNoErrors();
        $this->assertSame('College of Business and Accountancy', $cba->fresh()->name);
    }

    public function test_program_codes_are_unique(): void
    {
        Program::factory()->for($this->cit)->create(['code' => 'BSIT']);

        $this->actingAs($this->director)
            ->post(route('director.programs.store'), ['college_id' => $this->con->id, 'code' => 'BSIT', 'name' => 'Duplicate'])
            ->assertSessionHasErrorsIn('program', ['code']);

        $this->actingAs($this->director)
            ->post(route('director.programs.store'), ['college_id' => $this->con->id, 'code' => 'BSN', 'name' => 'Bachelor of Science in Nursing'])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('director.colleges.edit', $this->con));
    }

    public function test_deleting_a_college_with_programs_is_blocked(): void
    {
        Program::factory()->for($this->cit)->create(['code' => 'BSIT']);

        $this->actingAs($this->director)
            ->from(route('director.colleges.edit', $this->cit))
            ->delete(route('director.colleges.destroy', $this->cit))
            ->assertRedirect(route('director.colleges.edit', $this->cit))
            ->assertSessionHas('error', 'CIT still has programs. Move or delete its programs first.');

        $this->assertNotNull($this->cit->fresh());

        $this->actingAs($this->director)->delete(route('director.colleges.destroy', $this->con))->assertRedirect(route('director.colleges.index'));
        $this->assertNull($this->con->fresh());
    }

    public function test_deleting_a_program_with_students_is_blocked(): void
    {
        $program = Program::factory()->for($this->cit)->create(['code' => 'BSIT']);
        User::factory()->student($program)->create();
        $empty = Program::factory()->for($this->cit)->create(['code' => 'BSCS']);

        $this->actingAs($this->director)
            ->from(route('director.programs.edit', $program))
            ->delete(route('director.programs.destroy', $program))
            ->assertSessionHas('error', 'BSIT has enrolled students, so it cannot be deleted.');
        $this->assertNotNull($program->fresh());

        $this->actingAs($this->director)->delete(route('director.programs.destroy', $empty))->assertRedirect(route('director.colleges.edit', $this->cit));
        $this->assertNull($empty->fresh());
    }

    public function test_college_pages_show_programs_and_counselors(): void
    {
        Program::factory()->for($this->cit)->create(['code' => 'BSIT', 'name' => 'Bachelor of Science in Information Technology']);
        $counselor = User::factory()->counselor([$this->cit])->create();

        $this->actingAs($this->director)->get(route('director.colleges.index'))
            ->assertSee('BSIT')->assertSee($counselor->name);
        $this->actingAs($this->director)->get(route('director.colleges.edit', $this->cit))
            ->assertOk()->assertSee('BSIT')->assertSee($counselor->name)->assertSee('Add a program');
    }

    public function test_director_nav_has_management_links(): void
    {
        $html = $this->actingAs($this->director)->get(route('director.dashboard'))->getContent();

        $this->assertSame(2, substr_count($html, route('director.counselors.index').'"'));
        $this->assertSame(2, substr_count($html, route('director.colleges.index').'"'));

        $this->actingAs(User::factory()->counselor([$this->cit])->create())
            ->get(route('counselor.dashboard'))
            ->assertDontSee('Colleges &amp; Programs', false);
    }
}
