<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use Carbon\Carbon;

use DTApi\Helpers\TeHelper;


class TeHelperTest extends TestCase {
	
	public function setUp()
	{
		$this->createdAt = "2018-01-01 12:00:00";
	}

	/** @test */
	public function due_time_is_3days_and_18hours_later()
	{
		$due_time = "2018-09-4 12:00:00";

		$expected = TeHelper::willExpireAt($due_time, $this->createdAt);
		
		$this->assertEquals($due_time, $expected);
	}

	/** @test */
	public function when_due_time_is_one_day_later_it_should_expire_in_one_hour_and_thirty_minutes()
	{
		$due_time = "2018-01-02 12:00:00";

		$dueTime = Carbon::parse($due_time);
		$difference = $dueTime->diffInHours(Carbon::parse($this->createdAt));

		$expected = Carbon::parse($due_time)->addMinutes(90)->format('Y-m-d H:i:s');

		$willExpireAt = TeHelper::willExpireAt($due_time, $this->createdAt);
		$this->assertEquals($expected, $willExpireAt);
	}

	/** @test */
	public function when_due_time_is_within_three_days_it_should_expire_in_16_hours()
	{
		// greater than 1 day and less than 3 days, add 16 hrs
		$due_time = "2018-01-04 12:00:00";

		$dueTime = Carbon::parse($due_time);
		$difference = $dueTime->diffInHours(Carbon::parse($this->createdAt));

		$this->assertTrue($difference <= 24);

		$expected = Carbon::parse($due_time)->addMinutes(90)->format('Y-m-d H:i:s');

		$willExpireAt = TeHelper::willExpireAt($due_time, $this->createdAt);
		$this->assertEquals($expected, $willExpireAt);
	}

	/** @test */
	public function when_due_time_is_more_than_three_days()
	{
		$due_time = "2018-01-05 12:00:00";
		$dueTime = Carbon::parse($due_time);

		$expected = $dueTime->subHours(48)->format('Y-m-d H:i:s');

		$willExpireAt = TeHelper::willExpireAt($due_time, $this->createdAt);
		$this->assertEquals($expected, $willExpireAt);
	}
}