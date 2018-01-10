INSERT INTO `hook` 
( `code`, `type`, `by_module`, `activate`, `created_at`, `updated_at`) 
VALUES ( "brandcarousel.hook", 1, 0, 1, now(), now())
ON DUPLICATE KEY UPDATE id = id;

INSERT INTO `hook` 
( `code`, `type`, `by_module`, `activate`, `created_at`, `updated_at`) 
VALUES ( "brand_carousel.js", 1, 0, 1, now(), now())
ON DUPLICATE KEY UPDATE id = id;
	
