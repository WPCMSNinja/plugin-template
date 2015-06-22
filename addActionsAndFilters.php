<?php
function is_email_in_db($email) {
    $email = preg_quote($email);

    $found = false;

    // check the "Paypal" form
    if (!$found) {
        $exp = new CFDBFormIterator();
        $exp->export('Paypal', array('filter' => 'EMAIL~~/^' . $email . '$/i', 'show' => 'EMAIL', 'unbuffered' => 'true'));
        while ($row = $exp->nextRow()) {
            $found = true;
        }
    }

    // check the "Purchases" form
    if (!$found) {
        $exp = new CFDBFormIterator();
        $exp->export('Purchases', array('filter' => 'email~~/^' . $email . '$/i', 'show' => 'email', 'unbuffered' => 'true'));
        while ($row = $exp->nextRow()) {
            $found = true;
        }
    }

    return $found;
}