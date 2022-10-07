<?php

return [
    /**
     * The max_value is used to indicate that the chair is already sold.
     * This value represents seat status that is "sold/red". Any chair that has
     * "sold/status" cannot be bought or booked by other users.
     * This value will be stored on the is_reserve field on the seat
     */
    'MAX_VALUE' => 1410065407,
    /**
     * form_completion_duration is used to indicate the seat status as "booked/yellow"
     * This status will prevent the seat from being booked by two (or more) users.
     * After the set duration has passed, the chair's status will be back as "available/green" again
     * This value will be stored on the is_reserve field on the seat
     */
    'FORM_COMPLETION_DURATION' => 10, //in minutes
    /**
     * This constants is use for incrementing the is_reserved field on the seat with "booked/yellow" status when the user
     * is completing the transaction via midtrans payment gateway. This needs to be done in order to prevent other user
     * from booking the seat. It is recommended to set the constant value according to maximum payment duration on the midtrans gateway
     */
    'TRANSACTION_COMPLETION_DURATION' => 15,  //in minutes
];
