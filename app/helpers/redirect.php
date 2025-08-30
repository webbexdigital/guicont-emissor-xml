<?php

function redirect($to){
    Header("Location: $to", 300);
    die();
}