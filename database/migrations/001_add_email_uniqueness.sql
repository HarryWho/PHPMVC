-- Email Uniqueness Constraint Migration
-- Run this SQL in your database

-- Add UNIQUE constraint to users.user_email if it doesn't exist
-- First, remove any duplicates (if they exist)
-- DELETE u1 FROM users u1
-- INNER JOIN users u2 WHERE
--     u1.user_id > u2.user_id AND
--     u1.user_email = u2.user_email;

-- Then add the constraint:
ALTER TABLE users ADD UNIQUE KEY unique_email (`user_email`);

-- Verify the constraint was added:
-- SHOW CREATE TABLE users;
