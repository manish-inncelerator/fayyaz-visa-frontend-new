<?php

$docs = 0;
$declarations = 0;

if ($countryName['country_name'] != 'Singapore') {
    $docs = $database->get('documents', 'is_finished', [
        'order_id' => $order_id
    ]) ?? 0;
} else {
    $declarations = $database->get('declaration_terms', 'is_finished', [
        'order_id' => $order_id
    ]) ?? 0;
}

// Set to 1 if any of them is truthy (not empty, not 0)
$ifAllStepsDone = ($docs || $declarations) ? 1 : 0;

if ($ifAllStepsDone === 0) {
    header('Location: persona');
    exit; // Ensure script execution stops after redirection
} else {
    header('Location: https://fayyaztravels.com/visa/payment/pay?order_id=' . $order_id);
    exit; // Ensure script execution stops after redirection
}
