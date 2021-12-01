<?php

session_start();

define(
    'BASE_URL',
    'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']. '?'). '/'
);
