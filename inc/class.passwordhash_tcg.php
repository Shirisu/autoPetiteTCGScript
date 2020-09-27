<?php
function create_hash_for_tcg($password) {
    /* Set the "cost" parameter to 10. */
    $options = ["cost" => 10];

    /* Create the hash. */
    return password_hash($password, PASSWORD_DEFAULT, $options);
}

function validate_password_for_tcg($password, $correct_hash) {
    return password_verify($password, $correct_hash);
}
?>
