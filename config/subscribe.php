<?php

return [
    /**
     * Use uuid as primary key.
     */
    'uuids' => false,

    /*
     * User tables foreign key name.
     */
    'user_foreign_key' => 'user_id',

    /*
     * Table name for subscriptions records.
     */
    'subscriptions_table' => 'subscribes',

    /*
     * Model name for Subscribe record.
     */
    'subscription_model' => \Dapehe94\LaravelSubscribe\Subscribes::class,
];
