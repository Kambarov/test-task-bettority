CREATE
DATABASE IF NOT EXISTS reward_service_test;
CREATE
DATABASE IF NOT EXISTS main_service;

GRANT ALL PRIVILEGES ON reward_service_test.* TO
'root'@'%';
GRANT ALL PRIVILEGES ON main_service.* TO
'root'@'%';

-- Apply the privileges
FLUSH
PRIVILEGES;
