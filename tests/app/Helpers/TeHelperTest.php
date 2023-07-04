<?php

use DTApi\Helpers\TeHelper;

class TeHelperTest extends TestCase {
    /** @test */
    function will_expire_at_adds_90_minutes_to_created_time_if_difference_from_due_time_is_less_than_24_hours() {
        // arrange
        $created_at = Carbon::now();
        $due_time = $created_at->copy()->addHours(24);
        // act
        $time = TeHelper::willExpireAt($due_time, $created_at);
        // assert
        $created_at_plus_90_minutes = $created_at->copy()->addMinutes(90);
        $this->assertEquals($created_at_plus_90_minutes->format('Y-m-d H:i:s'), $time);
    }

    /** @test */
    function will_expire_at_adds_16_hours_to_created_time_if_difference_from_due_time_is_greater_than_24_hours_and_less_than_72() {
        // arrange
        $created_at = Carbon::now();
        $due_time = $created_at->copy()->addHours(fake()->numberBetween(25, 72));
        // act
        $time = TeHelper::willExpireAt($due_time, $created_at);
        // assert
        $created_at_plus_16_hours = $created_at->copy()->addHours(16);
        $this->assertEquals($created_at_plus_16_hours->format('Y-m-d H:i:s'), $time);
    }

    /** @test */
    function will_expire_at_gives_due_time_if_the_difference_between_due_dime_and_created_at_is_greater_than_72_but_less_than_90() {
        // arrange
        $created_at = Carbon::now();
        $due_time = $created_at->copy()->addHours(fake()->numberBetween(73, 90));
        // act
        $time = TeHelper::willExpireAt($due_time, $created_at);
        // assert
        $this->assertEquals($due_time->format('Y-m-d H:i:s'), $time);
    }

    /** @test */
    function will_expire_at_subtracts_48_hours_from_due_time_if_difference_from_created_at_is_greater_than_90() {
        // arrange
        $created_at = Carbon::now();
        $due_time = $created_at->copy()->addHours(91);
        // act
        $time = TeHelper::willExpireAt($due_time, $created_at);
        // assert
        $due_time_minus_48_hours = $due_time->copy()->addHours(16);
        $this->assertEquals($due_time_minus_48_hours->format('Y-m-d H:i:s'), $time);
    }
}