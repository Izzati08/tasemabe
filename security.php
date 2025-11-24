<?php
// Batasi percobaan login: maksimal 5 kali dalam 15 menit
function canAttemptLogin($user) {
  if (!$user) return true;
  if ($user['login_attempts'] >= 5 && strtotime($user['last_attempt_at']) > time() - 15*60) {
    return false;
  }
  return true;
}

function recordLoginAttempt($conn, $username, $success) {
  if ($success) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET login_attempts=0, last_attempt_at=NOW() WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
  } else {
    $stmt = mysqli_prepare($conn, "UPDATE users SET login_attempts=login_attempts+1, last_attempt_at=NOW() WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
  }
}
