<?php 
error_reporting(0);
include("db.php");
session_start();

/* ===============================
   BLOCK UNAUTHORIZED ACCESS
================================ */
if (
    !isset(
        $_SESSION['username'],
        $_SESSION['token'],
        $_SESSION['id']
    )
) {
    header("Location: index.php");
    exit;
}

/* ===============================
   READY-TO-USE VARIABLES
================================ */ 
 
$branch    = $_SESSION['branch']; 
$sid   = $_SESSION['id'];
$ssname    = $_SESSION['name'];
$standard = $_SESSION['standard'];
$username    = $_SESSION['username'];
$branch_name    = $_SESSION['branch_name'];
$session      = $_SESSION['session'];
$user      = $branch;
$cid      = $branch;
