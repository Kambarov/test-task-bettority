CREATE
DATABASE IF NOT EXISTS rewards;
CREATE
DATABASE IF NOT EXISTS main_service;

GRANT ALL PRIVILEGES ON rewards.* TO
'laravel'@'%';
GRANT ALL PRIVILEGES ON main_service.* TO
'laravel'@'%';

-- Apply the privileges
FLUSH
PRIVILEGES;
