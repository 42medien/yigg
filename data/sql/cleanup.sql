# Clean up sessions
DELETE LOW_PRIORITY FROM session_prod  WHERE sess_time < (UNIX_TIMESTAMP() - 3600);
