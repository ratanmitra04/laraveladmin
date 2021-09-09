<?php

use App\User;
use TaylorNetwork\UsernameGenerator\Drivers\EmailDriver;
use TaylorNetwork\UsernameGenerator\Drivers\NameDriver;

return [

    /*
     * Should the username generated be unique?
     */
    'unique' => true,

    /*
     * The minimum length of the username.
     *
     * Set to 0 to not enforce
     */
    'min_length' => 8,

    /*
     * Want to throw a UsernameTooShort exception when too short?
     */
    'throw_exception_on_too_short' => false,

    /*
     * Convert the case of the generated username
     *
     * One of 'lower', 'upper', or 'mixed'
     */
    'case' => 'upper',

    /*
     * Convert spaces in username to a separator
     */
    'separator' => '',

    /*
     * Model to check if the username is unique to.
     *
     * This is only used if unique is true
     */
    'model' => User::class,

    /*
     * Database field to check and store username
     */
    'column' => 'email',

    /*
     * Allowed characters from the original unconverted text
     */
    //'allowed_characters' => '0-9',
    'allowed_characters' => '0-9a-zA-Z ',

    /*
     * Loaded drivers for converting to a username
     */
    'drivers' => [
        'name'  => NameDriver::class,
        'email' => EmailDriver::class,
    ],

    /*
     * Used if you pass null to the generate function, will generate a random username
     */
    'dictionary' => [
        'adjectives' => ['other', 'new', 'good', 'old', 'little', 'great', 'small', 'young', 'long', 'black', 'high', 'only', 'big', 'white', 'political', 'right', 'large', 'real', 'sure', 'different', 'important', 'public', 'possible', 'full', 'whole', 'certain', 'human', 'major', 'military', 'bad', 'social', 'dead', 'true', 'economic', 'open', 'early', 'free', 'national', 'strong', 'hard', 'special', 'clear', 'local', 'private', 'wrong', 'late', 'short', 'poor', 'recent', 'dark', 'fine', 'foreign', 'ready', 'red', 'cold', 'low', 'heavy', 'serious', 'single', 'personal', 'difficult', 'left', 'blue', 'federal', 'necessary', 'general', 'easy', 'likely', 'beautiful', 'happy', 'past', 'hot', 'close', 'common', 'afraid', 'simple', 'natural', 'main', 'various', 'available', 'nice', 'present', 'final', 'sorry'],

        'nouns' => ['man', 'world', 'hand', 'room', 'face', 'thing', 'place', 'door', 'woman', 'house', 'money', 'father', 'government', 'country', 'mother', 'water', 'state', 'family', 'voice', 'fact', 'moment', 'power', 'city', 'business', 'war', 'school', 'system', 'car', 'number', 'office', 'point', 'body', 'wife', 'air', 'mind', 'girl', 'home', 'company', 'table', 'group', 'boy', 'problem', 'bed', 'death', 'hair', 'child', 'sense', 'job', 'light', 'question', 'idea', 'law', 'word', 'party', 'food', 'floor', 'book', 'reason', 'story', 'son', 'heart', 'friend', 'interest', 'right', 'town', 'history', 'land', 'program', 'game', 'control', 'matter', 'policy', 'oil', 'window', 'nation'],
    ],

];
