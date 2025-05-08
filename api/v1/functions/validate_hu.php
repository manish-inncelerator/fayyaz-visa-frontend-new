<?php
require 'functions/functions.php';

function isHuValid($uuid, $hu): bool
{
    return $hu === sha1($uuid);
}
