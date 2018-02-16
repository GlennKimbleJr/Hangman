<?php

use Illuminate\Database\Seeder;

class PhraseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // https://wheeloffortuneanswer.com/classic-tv/
        DB::table('phrases')->insert(['text' => 'Alice And Sam The Butcher']);
        DB::table('phrases')->insert(['text' => 'Barbara Eden Stars In I Dream Of Jeannie']);
        DB::table('phrases')->insert(['text' => 'Bewitched']);
        DB::table('phrases')->insert(['text' => 'Breaking Bad']);
        DB::table('phrases')->insert(['text' => 'Fat Albert And The Cosby Kids']);
        DB::table('phrases')->insert(['text' => 'Father Knows Best']);
        DB::table('phrases')->insert(['text' => 'Fred Flinstone and Barney Rubble']);
        DB::table('phrases')->insert(['text' => 'Ginger Mary Ann and The Professor']);
        DB::table('phrases')->insert(['text' => 'He-Man And The Masters Of The Universe']);
        DB::table('phrases')->insert(['text' => 'Hugo Weaving As Agent Smith']);
        DB::table('phrases')->insert(['text' => 'I Dream Of Jeannie']);
        DB::table('phrases')->insert(['text' => 'Lost In Space']);
        DB::table('phrases')->insert(['text' => 'Married With Children']);
        DB::table('phrases')->insert(['text' => 'Memorable Episodes Of The Twilight Zone']);
        DB::table('phrases')->insert(['text' => 'Sale Of The Century']);
        DB::table('phrases')->insert(['text' => 'Seinfeld']);
        DB::table('phrases')->insert(['text' => 'The Addams Family']);
        DB::table('phrases')->insert(['text' => 'The Beverly Hillbillies']);
        DB::table('phrases')->insert(['text' => 'The Brady Bunch']);
        DB::table('phrases')->insert(['text' => 'The Facts Of Life']);
        DB::table('phrases')->insert(['text' => 'The Munsters']);
        DB::table('phrases')->insert(['text' => 'The Muppet Show']);
        DB::table('phrases')->insert(['text' => 'This Is Your Life']);
        DB::table('phrases')->insert(['text' => 'Wagon Train']);
        DB::table('phrases')->insert(['text' => 'Welcome To Fantasy Island']);
        DB::table('phrases')->insert(['text' => 'Where Everybody Knows Your Name']);
        DB::table('phrases')->insert(['text' => 'Your Show Of Shows']);

        // https://wheeloffortuneanswer.com/rock-on/
        DB::table('phrases')->insert(['text' => 'Bohemian Rhapsody By Queen']);
        DB::table('phrases')->insert(['text' => 'Dream On By Aerosmith']);
        DB::table('phrases')->insert(['text' => 'Glory Days By Bruce Springsteen']);
        DB::table('phrases')->insert(['text' => 'Sweet Emotion By Aerosmith']);
        DB::table('phrases')->insert(['text' => 'Take It Easy By The Eagles']);
        DB::table('phrases')->insert(['text' => 'The Smashing Pumpkins']);
        DB::table('phrases')->insert(['text' => 'Top Of The Billboard Charts']);
        DB::table('phrases')->insert(['text' => 'Tribute Album']);
        DB::table('phrases')->insert(['text' => 'Werewolves Of London By Warren Zevon']);

        // https://wheeloffortuneanswer.com/fun-and-games/
        DB::table('phrases')->insert(['text' => 'A Brisk Jog']);
        DB::table('phrases')->insert(['text' => 'Adventuresome Boat Excursions']);
        DB::table('phrases')->insert(['text' => 'All That Jazz']);
        DB::table('phrases')->insert(['text' => 'Beer Pong']);
        DB::table('phrases')->insert(['text' => 'Big Wave Surfing']);
        DB::table('phrases')->insert(['text' => 'Bingo Night']);
        DB::table('phrases')->insert(['text' => 'Board Game Night']);
        DB::table('phrases')->insert(['text' => 'Carnival Rides']);
        DB::table('phrases')->insert(['text' => 'Choose Your Own Adventure Stories']);
        DB::table('phrases')->insert(['text' => 'Croquet']);
        DB::table('phrases')->insert(['text' => 'Doing A Cartwheel']);
        DB::table('phrases')->insert(['text' => 'Driving Through Wide Open Spaces']);
        DB::table('phrases')->insert(['text' => 'Eggtoss']);
        DB::table('phrases')->insert(['text' => 'Fantasy Football League']);
        DB::table('phrases')->insert(['text' => 'Final Fantasy']);
        DB::table('phrases')->insert(['text' => 'Flying A Kite On The Beach']);
        DB::table('phrases')->insert(['text' => 'Going Parasailing']);
        DB::table('phrases')->insert(['text' => 'Haunted House']);
        DB::table('phrases')->insert(['text' => 'I Love The Game With All My Heart']);
        DB::table('phrases')->insert(['text' => 'Indoor Kart Races']);
        DB::table('phrases')->insert(['text' => 'Island Dance Music']);
        DB::table('phrases')->insert(['text' => 'Jumping In A Bounce House']);
        DB::table('phrases')->insert(['text' => 'Learning Tai Chi']);
        DB::table('phrases')->insert(['text' => 'Minesweeper']);
        DB::table('phrases')->insert(['text' => 'Mountaineering']);
        DB::table('phrases')->insert(['text' => 'Paint By Numbers']);
        DB::table('phrases')->insert(['text' => 'Playing Beach Volleyball']);
        DB::table('phrases')->insert(['text' => 'Pumpkin Carving']);
        DB::table('phrases')->insert(['text' => 'River Rafting']);
        DB::table('phrases')->insert(['text' => 'Round Of Golf']);
        DB::table('phrases')->insert(['text' => 'Shadow Tag']);
        DB::table('phrases')->insert(['text' => 'Snowmobiling']);
        DB::table('phrases')->insert(['text' => 'Taking A Dip In The Pool']);
        DB::table('phrases')->insert(['text' => 'Ultimate Frisbee']);
        DB::table('phrases')->insert(['text' => 'Walking Safari']);
        DB::table('phrases')->insert(['text' => 'Why Did The Chicken Cross The Road']);
        DB::table('phrases')->insert(['text' => 'Winter Olympics']);
        DB::table('phrases')->insert(['text' => 'Yahtzee']);
        DB::table('phrases')->insert(['text' => 'Yellow Jacket']);
        DB::table('phrases')->insert(['text' => 'Zinneke Parade']);
    }
}
