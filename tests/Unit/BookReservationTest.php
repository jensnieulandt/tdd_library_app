<?php

namespace Tests\Unit;

use App\Book;
use App\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    public function testBookCanBeCheckedOut()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $reservation = Reservation::first();

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertEquals(now(), $reservation->checked_out_at);
    }

    public function testBookCanBeReturned()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $book->checkin($user);

        $reservation = Reservation::first();

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertNotNull($reservation->checked_in_at);
        $this->assertEquals(now(), $reservation->checked_in_at);
    }

    public function testExceptionThrownOnBookReturnWhenNotCheckedOut()
    {
        $this->expectException(\Exception::class);

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkin($user);
    }

    public function testUserCanCheckoutABookTwice()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);
        $book->checkin($user);
        $book->checkout($user);

        $reservation = Reservation::latest('id')->first();

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertNull($reservation->checked_in_at);
        $this->assertEquals(now(), $reservation->checked_out_at);

        $book->checkin($user);

        $reservation = $reservation->fresh();

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertNotNull($reservation->checked_in_at);
        $this->assertEquals(now(), $reservation->checked_in_at);
    }
}
