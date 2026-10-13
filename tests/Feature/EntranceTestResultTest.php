<?php

namespace Tests\Feature;

use App\Models\AccessLog;
use App\Models\College;
use App\Models\CumulativeRecord;
use App\Models\EntranceTestResult;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Js;
use Tests\TestCase;

class EntranceTestResultTest extends TestCase
{
    use RefreshDatabase;

    private User $counselorA;

    private User $director;

    private User $studentA;

    private User $studentB;

    protected function setUp(): void
    {
        parent::setUp();

        $collegeA = College::factory()->create(['code' => 'CIT']);
        $collegeB = College::factory()->create(['code' => 'CON']);

        $this->counselorA = User::factory()->counselor([$collegeA])->create();
        $this->director = User::factory()->director()->create();
        $this->studentA = User::factory()->student(Program::factory()->for($collegeA)->create())->create(['last_name' => 'Aquino']);
        $this->studentB = User::factory()->student(Program::factory()->for($collegeB)->create())->create(['last_name' => 'Bautista']);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        $payload = ['exam_date' => '2026-06-15', 'quotient' => 104, 'quotient_descriptor' => 'Average', 'remarks' => null];

        foreach (array_keys(EntranceTestResult::subtests()) as $key) {
            $payload["{$key}_score"] = 10;
            $payload["{$key}_descriptor"] = 'Average';
        }

        return array_merge($payload, $overrides);
    }

    public function test_counselor_can_encode_for_students_in_assigned_colleges(): void
    {
        $this->actingAs($this->counselorA)->get(route('staff.sata.create', $this->studentA))->assertOk()->assertSee('Standard Score');

        $this->actingAs($this->counselorA)
            ->post(route('staff.sata.store', $this->studentA), $this->payload())
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('staff.sata.index', $this->studentA));

        $result = $this->studentA->entranceTestResults()->sole();
        $this->assertSame(104, $result->quotient);
        $this->assertSame($this->counselorA->id, $result->encoded_by);
        $this->assertSame($this->counselorA->id, $result->updated_by);
    }

    public function test_counselor_gets_403_for_students_outside_assigned_colleges(): void
    {
        $result = EntranceTestResult::factory()->for($this->studentB, 'student')->create();

        $this->actingAs($this->counselorA)->get(route('staff.sata.index', $this->studentB))->assertForbidden();
        $this->actingAs($this->counselorA)->get(route('staff.sata.create', $this->studentB))->assertForbidden();
        $this->actingAs($this->counselorA)->post(route('staff.sata.store', $this->studentB), $this->payload())->assertForbidden();
        $this->actingAs($this->counselorA)->get(route('staff.sata.show', $result))->assertForbidden();
        $this->actingAs($this->counselorA)->get(route('staff.sata.edit', $result))->assertForbidden();
        $this->actingAs($this->counselorA)->put(route('staff.sata.update', $result), $this->payload(['quotient' => 150]))->assertForbidden();

        $this->assertSame(1, EntranceTestResult::count());
        $this->assertNotSame(150, $result->fresh()->quotient);
    }

    public function test_director_can_encode_and_edit_for_anyone(): void
    {
        foreach ([$this->studentA, $this->studentB] as $student) {
            $this->actingAs($this->director)
                ->post(route('staff.sata.store', $student), $this->payload())
                ->assertSessionHasNoErrors();
        }

        $result = $this->studentB->entranceTestResults()->sole();
        $this->actingAs($this->director)
            ->put(route('staff.sata.update', $result), $this->payload(['quotient' => 125, 'quotient_descriptor' => 'Superior']))
            ->assertRedirect(route('staff.sata.show', $result));

        $this->assertSame(125, $result->fresh()->quotient);
        $this->assertSame($this->director->id, $result->fresh()->updated_by);
    }

    public function test_edit_records_who_last_updated(): void
    {
        $result = EntranceTestResult::factory()->for($this->studentA, 'student')->create(['encoded_by' => $this->director->id, 'updated_by' => $this->director->id]);
        $this->travel(1)->hours();

        $this->actingAs($this->counselorA)->put(route('staff.sata.update', $result), $this->payload())->assertSessionHasNoErrors();

        $result->refresh();
        $this->assertSame($this->director->id, $result->encoded_by);
        $this->assertSame($this->counselorA->id, $result->updated_by);

        $this->actingAs($this->counselorA)->get(route('staff.sata.show', $result))
            ->assertSee('By '.$this->director->name)
            ->assertSee('Updated by '.$this->counselorA->name);
    }

    public function test_students_get_403_on_all_sata_routes(): void
    {
        $result = EntranceTestResult::factory()->for($this->studentA, 'student')->create();

        foreach ([
            ['GET', route('staff.sata.index', $this->studentA)],
            ['GET', route('staff.sata.create', $this->studentA)],
            ['POST', route('staff.sata.store', $this->studentA)],
            ['GET', route('staff.sata.show', $result)],
            ['GET', route('staff.sata.edit', $result)],
            ['PUT', route('staff.sata.update', $result)],
        ] as [$method, $url]) {
            $this->actingAs($this->studentA)->call($method, $url, $this->payload())->assertForbidden();
        }
    }

    public function test_students_never_see_sata_on_their_own_pages(): void
    {
        EntranceTestResult::factory()->for($this->studentA, 'student')->create(['quotient' => 137, 'quotient_descriptor' => 'Very Superior']);
        $record = CumulativeRecord::factory()->for($this->studentA, 'student')->submitted()->create();

        foreach ([route('student.dashboard'), route('student.record.show', $record)] as $url) {
            $this->actingAs($this->studentA->fresh())->get($url)->assertOk()
                ->assertDontSee('SATA')
                ->assertDontSee('Very Superior')
                ->assertDontSee('/sata', false);
        }

        $this->assertFalse(collect(app('router')->getRoutes())->contains(
            fn ($route) => str_contains($route->uri(), 'sata') && in_array('role:student', $route->middleware(), true)
        ));
    }

    public function test_validation_rejects_out_of_range_scores_and_future_dates(): void
    {
        $this->actingAs($this->counselorA)
            ->post(route('staff.sata.store', $this->studentA), $this->payload([
                'vr_score' => 0,
                'nvr_score' => 21,
                'quotient' => 39,
                'exam_date' => now()->addDay()->format('Y-m-d'),
                'wc_descriptor' => 'Genius',
            ]))
            ->assertSessionHasErrors(['vr_score', 'nvr_score', 'quotient', 'exam_date', 'wc_descriptor']);

        $this->actingAs($this->counselorA)
            ->post(route('staff.sata.store', $this->studentA), $this->payload(['quotient' => 161]))
            ->assertSessionHasErrors('quotient');

        $this->assertSame(0, EntranceTestResult::count());

        $this->actingAs($this->counselorA)
            ->post(route('staff.sata.store', $this->studentA), $this->payload(['vr_score' => 1, 'nvr_score' => 20, 'quotient' => 40]))
            ->assertSessionHasNoErrors();
    }

    public function test_descriptor_suggestions_follow_the_config_ranges(): void
    {
        $this->assertSame('Very Superior', EntranceTestResult::suggestDescriptor(17));
        $this->assertSame('Superior', EntranceTestResult::suggestDescriptor(16));
        $this->assertSame('Average', EntranceTestResult::suggestDescriptor(8));
        $this->assertSame('Below Average', EntranceTestResult::suggestDescriptor(7));
        $this->assertSame('Very Poor', EntranceTestResult::suggestDescriptor(3));
        $this->assertSame('Very Superior', EntranceTestResult::suggestDescriptor(131, 'quotient'));
        $this->assertSame('Average', EntranceTestResult::suggestDescriptor(90, 'quotient'));
        $this->assertSame('Very Poor', EntranceTestResult::suggestDescriptor(69, 'quotient'));

        config(['sata.ranges.subtest' => [
            ['min' => 1, 'max' => 9, 'descriptor' => 'Poor'],
            ['min' => 10, 'max' => 20, 'descriptor' => 'Superior'],
        ]]);
        $this->assertSame('Superior', EntranceTestResult::suggestDescriptor(10));

        // The form ships the same configured ranges to the browser for auto-suggest.
        $this->actingAs($this->counselorA)
            ->get(route('staff.sata.create', $this->studentA))
            ->assertSee((string) Js::from(config('sata.ranges')), false);
    }

    public function test_retakes_keep_history_and_latest_by_exam_date_is_current(): void
    {
        $this->actingAs($this->counselorA)->post(route('staff.sata.store', $this->studentA), $this->payload(['exam_date' => '2026-06-15', 'quotient' => 95]));
        $this->actingAs($this->counselorA)->post(route('staff.sata.store', $this->studentA), $this->payload(['exam_date' => '2026-09-01', 'quotient' => 112, 'quotient_descriptor' => 'Above Average']));
        // An older exam encoded last must not become current.
        $this->actingAs($this->counselorA)->post(route('staff.sata.store', $this->studentA), $this->payload(['exam_date' => '2026-01-10', 'quotient' => 88, 'quotient_descriptor' => 'Below Average']));

        $this->assertSame(3, $this->studentA->entranceTestResults()->count());
        $this->assertSame(112, $this->studentA->latestEntranceTestResult()->first()->quotient);

        $html = $this->actingAs($this->counselorA)->get(route('staff.sata.index', $this->studentA))->assertOk()->getContent();
        $this->assertSame(1, substr_count($html, '>Current<'));
        $this->assertSame(2, substr_count($html, '>Earlier result<'));
        $this->assertLessThan(strpos($html, 'June 15, 2026'), strpos($html, 'September 1, 2026'));

        $record = CumulativeRecord::factory()->for($this->studentA, 'student')->submitted()->create();
        $this->actingAs($this->counselorA)->get(route('staff.records.show', $record))
            ->assertSee('Entrance Test Result (SATA)')
            ->assertSee('September 1, 2026')
            ->assertSee('History (3 results)');
    }

    public function test_every_sata_view_is_logged(): void
    {
        $result = EntranceTestResult::factory()->for($this->studentA, 'student')->create();
        $record = CumulativeRecord::factory()->for($this->studentA, 'student')->submitted()->create();

        $this->actingAs($this->counselorA)->get(route('staff.sata.show', $result))->assertOk();
        $this->actingAs($this->counselorA)->get(route('staff.sata.edit', $result))->assertOk();
        $this->actingAs($this->counselorA)->get(route('staff.sata.index', $this->studentA))->assertOk();
        $this->actingAs($this->counselorA)->get(route('staff.records.show', $record))->assertOk();

        $this->assertSame(4, AccessLog::where([
            'user_id' => $this->counselorA->id,
            'entrance_test_result_id' => $result->id,
            'action' => AccessLog::ACTION_VIEW_SATA,
        ])->count());

        // The forbidden attempt is not logged.
        $this->actingAs(User::factory()->counselor()->create())->get(route('staff.sata.show', $result))->assertForbidden();
        $this->assertSame(4, AccessLog::where('action', AccessLog::ACTION_VIEW_SATA)->count());
    }

    public function test_save_and_encode_next_goes_to_the_next_student_without_a_result_in_the_filtered_list(): void
    {
        $program = $this->studentA->program;
        $studentC = User::factory()->student($program)->create(['last_name' => 'Cruz']);
        $studentD = User::factory()->student($program)->create(['last_name' => 'Dizon']);
        EntranceTestResult::factory()->for($studentC, 'student')->create();
        $outsideFilter = User::factory()->student($program)->create(['last_name' => 'Abad', 'year_level' => 5]);
        $this->studentA->update(['year_level' => 1]);
        $studentD->update(['year_level' => 1]);

        $this->actingAs($this->counselorA)
            ->post(route('staff.sata.store', $this->studentA), $this->payload(['next' => '1', 'year_level' => '1']))
            ->assertRedirect(route('staff.sata.create', [$studentD, 'year_level' => '1']));

        $this->actingAs($this->counselorA)
            ->post(route('staff.sata.store', $studentD), $this->payload(['next' => '1', 'year_level' => '1']))
            ->assertRedirect(route('staff.records.index', ['year_level' => '1']))
            ->assertSessionHas('status', fn ($status) => str_contains($status, 'Everyone in this list now has a SATA result'));

        $this->assertFalse($outsideFilter->entranceTestResults()->exists());

        // The end-of-list message is visible on the records page.
        $this->actingAs($this->counselorA)
            ->followingRedirects()
            ->post(route('staff.sata.store', $outsideFilter), $this->payload(['next' => '1', 'year_level' => '5']))
            ->assertSee('Everyone in this list now has a SATA result');
    }

    public function test_records_list_shows_and_filters_sata_and_tracker_counts_missing(): void
    {
        EntranceTestResult::factory()->for($this->studentA, 'student')->create();
        $studentNoSata = User::factory()->student($this->studentA->program)->create(['last_name' => 'Nosata']);

        $this->actingAs($this->counselorA)->get(route('staff.records.index'))
            ->assertSee('Encoded')
            ->assertSee('Not encoded')
            ->assertSee(route('staff.sata.create', $studentNoSata), false);

        $this->actingAs($this->counselorA)->get(route('staff.records.index', ['sata' => 'not_encoded']))
            ->assertSee('Nosata')->assertDontSee('Aquino');
        $this->actingAs($this->counselorA)->get(route('staff.records.index', ['sata' => 'encoded']))
            ->assertSee('Aquino')->assertDontSee('Nosata');

        $tracker = $this->actingAs($this->counselorA)->get(route('staff.tracker'))->assertOk();
        $this->assertSame(1, $tracker->viewData('withoutSata'));
        $tracker->assertSee('student without a SATA result');

        $this->assertSame(2, $this->actingAs($this->director)->get(route('staff.tracker'))->viewData('withoutSata'));
    }
}
