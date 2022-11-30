<?php
require 'AuthHelper.php';
if(!isset($_SESSION)) session_start();
AuthHelper::signOut();