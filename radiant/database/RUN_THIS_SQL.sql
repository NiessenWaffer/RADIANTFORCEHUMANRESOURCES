-- ========================================
-- RUN THIS SQL IN PHPMYADMIN
-- ========================================
-- This adds the location column to track where applicants apply from
-- Time: 30 seconds | Difficulty: Easy
-- ========================================

USE `dbronnie`;

-- Add location column to job_applications table
ALTER TABLE `job_applications` 
ADD COLUMN `location` VARCHAR(255) DEFAULT NULL 
COMMENT 'Location/branch where applicant is applying'
AFTER `job_preferred`;

-- Add index for better performance
ALTER TABLE `job_applications` 
ADD INDEX `idx_location` (`location`);

-- Verify the column was added
SELECT 'SUCCESS! Location column added to job_applications table' AS status;

-- Show the updated table structure
DESCRIBE `job_applications`;

-- ========================================
-- DONE! ✅
-- ========================================
-- Next: Test by submitting an application from a location page
-- Then check your admin panel to see the location displayed
-- ========================================
