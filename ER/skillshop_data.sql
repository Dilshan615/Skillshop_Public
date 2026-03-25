
--
-- Dumping data for table `account_type`
--
LOCK TABLES `account_type` WRITE;

/*!40000 ALTER TABLE `account_type` DISABLE KEYS */;

INSERT INTO
  `account_type`
VALUES
  (1, 'seller'),
  (2, 'buyer');

/*!40000 ALTER TABLE `account_type` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `address`
--
DROP TABLE IF EXISTS `address`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `address` (
    `id` int NOT NULL AUTO_INCREMENT,
    `line_1` varchar(100) NOT NULL,
    `line_2` varchar(100) DEFAULT NULL,
    `city_id` int NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_address_city1_idx` (`city_id`),
    CONSTRAINT `fk_address_city1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 12 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--
LOCK TABLES `address` WRITE;

/*!40000 ALTER TABLE `address` DISABLE KEYS */;

INSERT INTO
  `address`
VALUES
  (
    1,
    '52/4A , st.antony road, muruthana, kochchikade.',
    '',
    1
  ),
  (
    2,
    'No. 23, Temple Road, Kandy',
    'Near Dalada Maligawa',
    2
  );

/*!40000 ALTER TABLE `address` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `cart`
--
DROP TABLE IF EXISTS `cart`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `cart` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `product_id` int NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_cart_user1_idx` (`user_id`),
    KEY `fk_cart_product1_idx` (`product_id`),
    CONSTRAINT `fk_cart_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
    CONSTRAINT `fk_cart_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category`
--
DROP TABLE IF EXISTS `category`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `category` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name_UNIQUE` (`name`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--
LOCK TABLES `category` WRITE;

/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO
  `category`
VALUES
  (1, 'Web Development'),
  (2, 'Branding'),
  (3, 'Content Creation'),
  (4, 'Support Services'),
  (5, 'Web design');

/*!40000 ALTER TABLE `category` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `city`
--
DROP TABLE IF EXISTS `city`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `city` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(45) DEFAULT NULL,
    `country_id` int NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_city_country1_idx` (`country_id`),
    CONSTRAINT `fk_city_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city`
--
LOCK TABLES `city` WRITE;

/*!40000 ALTER TABLE `city` DISABLE KEYS */;

INSERT INTO
  `city`
VALUES
  (1, 'Colombo', 1),
  (2, 'Kandy', 1),
  (3, 'Mumbai', 2),
  (4, 'New York', 3);

/*!40000 ALTER TABLE `city` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `country`
--
DROP TABLE IF EXISTS `country`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `country` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(60) NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 17 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--
LOCK TABLES `country` WRITE;

/*!40000 ALTER TABLE `country` DISABLE KEYS */;

INSERT INTO
  `country`
VALUES
  (1, 'Sri Lanka'),
  (2, 'India'),
  (3, 'USA'),
  (4, 'United Kingdom'),
  (5, 'Australia'),
  (6, 'Canada'),
  (7, 'Japan'),
  (8, 'Germany'),
  (9, 'France'),
  (10, 'Singapore');

/*!40000 ALTER TABLE `country` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `feedback`
--
DROP TABLE IF EXISTS `feedback`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `feedback` (
    `id` int NOT NULL AUTO_INCREMENT,
    `product_id` int NOT NULL,
    `user_id` int NOT NULL,
    `rating` int NOT NULL,
    `messege` text CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
      `is_featured` tinyint DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `fk_feedback_product1_idx` (`product_id`),
      KEY `fk_feedback_user1_idx` (`user_id`),
      CONSTRAINT `fk_feedback_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
      CONSTRAINT `fk_feedback_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 16 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--
LOCK TABLES `feedback` WRITE;

/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;

INSERT INTO
  `feedback`
VALUES
  (
    1,
    3,
    7,
    3,
    'Excellent masterclass, very detailed!',
    1,
    '2026-02-27 18:07:31',
    '2026-03-09 11:03:55'
  ),
  (
    2,
    3,
    2,
    4,
    'Good design basics, easy to follow.',
    0,
    '2026-02-27 18:07:31',
    '2026-03-09 11:04:00'
  ),
  (
    3,
    3,
    2,
    5,
    'Portfolio site was perfect for me.',
    1,
    '2026-02-27 18:07:31',
    '2026-02-27 18:07:31'
  ),
  (
    4,
    3,
    1,
    4,
    'Landing page design is clean.',
    0,
    '2026-02-27 18:07:31',
    '2026-03-09 11:04:03'
  ),
  (
    5,
    5,
    1,
    5,
    'Custom theme matched my brand well.',
    1,
    '2026-02-27 18:07:31',
    '2026-02-27 18:07:31'
  ),
  (
    6,
    6,
    1,
    5,
    'Backend validation flow worked smoothly.',
    1,
    '2026-02-27 18:07:31',
    '2026-02-27 18:07:31'
  ),
  (
    7,
    7,
    2,
    4,
    'Database queries explained clearly.',
    0,
    '2026-02-27 18:07:31',
    '2026-02-27 18:07:31'
  ),
  (
    8,
    8,
    2,
    3,
    'Error handling module was okay.',
    0,
    '2026-02-27 18:07:31',
    '2026-02-27 18:07:31'
  ),
  (
    9,
    9,
    2,
    5,
    'Data science course was advanced and useful.',
    1,
    '2026-02-27 18:07:31',
    '2026-02-27 18:07:31'
  ),
  (
    10,
    10,
    1,
    4,
    'Brand identity ideas were creative.',
    0,
    '2026-02-27 18:07:31',
    '2026-02-27 18:07:31'
  );

/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `gender`
--
DROP TABLE IF EXISTS `gender`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `gender` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(20) NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gender`
--
LOCK TABLES `gender` WRITE;

/*!40000 ALTER TABLE `gender` DISABLE KEYS */;

INSERT INTO
  `gender`
VALUES
  (1, 'male'),
  (2, 'female'),
  (3, 'other');

/*!40000 ALTER TABLE `gender` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `order`
--
DROP TABLE IF EXISTS `order`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `order` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `product_id` int NOT NULL,
    `total_amount` decimal(10, 2) NOT NULL,
    `status` enum (
      'pending',
      'in_progress',
      'completed',
      'cancelled'
    ) DEFAULT 'pending',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_order_user1_idx` (`user_id`),
    KEY `fk_order_product1_idx` (`product_id`),
    CONSTRAINT `fk_order_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
    CONSTRAINT `fk_order_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--
LOCK TABLES `order` WRITE;

/*!40000 ALTER TABLE `order` DISABLE KEYS */;

/*!40000 ALTER TABLE `order` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--
DROP TABLE IF EXISTS `password_reset_tokens`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `password_reset_tokens` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `token_hash` varchar(255) NOT NULL,
    `expiry` datetime NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_password_reset_tokens_user1_idx` (`user_id`),
    CONSTRAINT `fk_password_reset_tokens_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--
LOCK TABLES `password_reset_tokens` WRITE;

/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;

/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `product`
--
DROP TABLE IF EXISTS `product`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `product` (
    `id` int NOT NULL AUTO_INCREMENT,
    `seller_id` int NOT NULL,
    `category_id` int NOT NULL,
    `title` varchar(150) NOT NULL,
    `description` text,
    `price` decimal(10, 2) NOT NULL,
    `level` enum ('Beginner', 'Intermediate', 'Advanced') NOT NULL DEFAULT 'Beginner',
    `image_url` varchar(255) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `status` enum ('active', 'inactive') NOT NULL DEFAULT 'active',
    PRIMARY KEY (`id`),
    KEY `fk_product_user1_idx` (`seller_id`),
    KEY `fk_product_category1_idx` (`category_id`),
    CONSTRAINT `fk_product_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
    CONSTRAINT `fk_product_user1` FOREIGN KEY (`seller_id`) REFERENCES `user` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 35 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--
LOCK TABLES `product` WRITE;

/*!40000 ALTER TABLE `product` DISABLE KEYS */;

INSERT INTO
  `product`
VALUES
  (
    1,
    9,
    2,
    'Web Development Masterclass',
    'Learn HTML, CSS, JavaScript, PHP, and SQL with real projects.',
    50000.00,
    'Advanced',
    'uploads/products/product_9_949dc5c8.jpg',
    '2026-02-25 17:44:09',
    '2026-03-13 16:15:09',
    'active'
  ),
  (
    2,
    9,
    2,
    'Web Design 0.0',
    'Creating responsive websites with branding and modern UI.',
    45000.00,
    'Intermediate',
    'uploads/products/product_1_6486b8a8.jpg',
    '2026-02-27 17:34:14',
    '2026-03-08 18:54:52',
    'active'
  ),
  (
    3,
    9,
    1,
    'Portfolio Site',
    'Personal showcase with projects and contact form.',
    40000.00,
    'Intermediate',
    'uploads/products/product_1_05c5454b.png',
    '2026-02-27 17:35:34',
    '2026-03-08 18:54:56',
    'active'
  ),
  (
    4,
    1,
    1,
    'Landing Page',
    'Single-page promotional design.',
    25000.00,
    'Beginner',
    'uploads/products/product_1_bc277092.jpg',
    '2026-02-27 17:37:05',
    '2026-03-02 18:48:41',
    'active'
  ),
  (
    5,
    1,
    1,
    'Custom Theme',
    'Tailored design theme for websites or apps.',
    45000.00,
    'Advanced',
    'uploads/products/product_1_6bf7728e.webp',
    '2026-02-27 17:38:38',
    '2026-03-04 10:17:13',
    'active'
  ),
  (
    6,
    1,
    2,
    'Backend Development',
    'Secure login, registration, and validation flows with PHP/JS.',
    60000.00,
    'Advanced',
    'uploads/products/product_1_db9255c4.jpg',
    '2026-02-27 17:39:56',
    '2026-03-02 18:48:39',
    'active'
  ),
  (
    7,
    1,
    2,
    'Database Management',
    'DBMS design, queries, and error-free integration.',
    35000.00,
    'Intermediate',
    'uploads/products/product_1_73fac990.jpg',
    '2026-02-27 17:40:50',
    '2026-03-04 10:17:16',
    'active'
  ),
  (
    8,
    1,
    2,
    'Error Handling',
    'Backend validation and error feedback system.',
    35600.00,
    'Intermediate',
    'uploads/products/product_1_248a15ec.png',
    '2026-02-27 17:42:54',
    '2026-03-02 18:48:38',
    'active'
  ),
  (
    9,
    1,
    2,
    'Master Data Scientist',
    'Advanced analytics, machine learning, and predictive modeling',
    90000.00,
    'Advanced',
    'uploads/products/product_1_a59e3454.jpg',
    '2026-02-27 17:43:51',
    '2026-03-02 18:48:33',
    'active'
  ),
  (
    10,
    1,
    3,
    'Brand Identity',
    'Logo, color palette, and theme design.',
    30000.00,
    'Beginner',
    'uploads/products/product_1_d8b4510e.jpg',
    '2026-02-27 17:44:43',
    '2026-03-04 10:17:19',
    'active'
  ),
  (
    11,
    1,
    3,
    'Logo Design',
    'Professional logo tailored to your theme.',
    15000.00,
    'Beginner',
    'uploads/products/product_1_20a3b7ae.webp',
    '2026-02-27 17:47:02',
    '2026-03-02 18:48:34',
    'active'
  ),
  (
    12,
    1,
    3,
    'Social Media Branding',
    'Branded posts, banners, and profile designs.',
    20000.00,
    'Intermediate',
    'uploads/products/product_1_bcec2afd.jpg',
    '2026-02-27 17:47:52',
    '2026-03-02 18:48:32',
    'active'
  ),
  (
    13,
    1,
    4,
    'YouTube Narration',
    'Sinhala-English mixed narration for assignments.',
    25000.00,
    'Beginner',
    'uploads/products/product_1_fb8eafaa.jpg',
    '2026-02-27 17:48:56',
    '2026-03-04 11:16:32',
    'active'
  ),
  (
    14,
    1,
    4,
    'Assignment Video',
    'Narrated video explanation with visuals.',
    30000.00,
    'Intermediate',
    'uploads/products/product_1_246df6ae.jpg',
    '2026-02-27 17:50:28',
    '2026-03-02 18:48:29',
    'active'
  ),
  (
    15,
    1,
    2,
    'UI/UX ',
    'LEARN UX',
    20000.00,
    'Beginner',
    'uploads/products/product_1_4ca00132.png',
    '2026-03-04 10:10:39',
    '2026-03-04 17:30:08',
    'active'
  ),
  (
    16,
    1,
    3,
    'Branding themes',
    'themes',
    35000.00,
    'Advanced',
    'uploads/products/product_1_73fac990.jpg',
    '2026-03-04 17:30:53',
    '2026-03-04 17:31:09',
    'active'
  ),
  (
    31,
    1,
    2,
    'Backend data',
    'Backend',
    26500.00,
    'Beginner',
    'uploads/products/product_1_73fac990.jpg',
    '2026-03-04 17:31:41',
    '2026-03-04 17:31:47',
    'active'
  ),
  (
    32,
    9,
    1,
    'UI/UX Design Kit',
    'hi',
    55000.00,
    'Advanced',
    'uploads/products/product_9_949dc5c8.jpg',
    '2026-03-07 18:57:31',
    '2026-03-08 19:00:21',
    'inactive'
  ),
  (
    33,
    9,
    3,
    'UI/UX Design Kit',
    'pk',
    55000.00,
    'Intermediate',
    'uploads/products/product_9_304c4f94.jpg',
    '2026-03-08 18:53:28',
    '2026-03-08 18:53:28',
    'active'
  ),
  (
    34,
    1,
    1,
    'Hariyata',
    'Future product for advanced learning',
    10000.00,
    'Beginner',
    NULL,
    '2026-04-01 00:00:00',
    '2026-04-01 00:00:00',
    'active'
  );

/*!40000 ALTER TABLE `product` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `remember_tokens`
--
DROP TABLE IF EXISTS `remember_tokens`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `remember_tokens` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `token_hash` varchar(255) NOT NULL,
    `expiry` datetime NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_remember_tokens_user_idx` (`user_id`),
    CONSTRAINT `fk_remember_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 36 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--
DROP TABLE IF EXISTS `user`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `user` (
    `id` int NOT NULL AUTO_INCREMENT,
    `fname` varchar(60) NOT NULL,
    `lname` varchar(60) NOT NULL,
    `email` varchar(150) NOT NULL,
    `password_hash` varchar(255) NOT NULL,
    `active_account_type_id` int NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_user_account_type1_idx` (`active_account_type_id`),
    CONSTRAINT `fk_user_account_type1` FOREIGN KEY (`active_account_type_id`) REFERENCES `account_type` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 10 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--
--
-- Table structure for table `user_has_account_type`
--
DROP TABLE IF EXISTS `user_has_account_type`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `user_has_account_type` (
    `user_id` int NOT NULL,
    `account_type_id` int NOT NULL,
    PRIMARY KEY (`user_id`, `account_type_id`),
    KEY `fk_user_has_account_type_account_type1_idx` (`account_type_id`),
    KEY `fk_user_has_account_type_user1_idx` (`user_id`),
    CONSTRAINT `fk_user_has_account_type_account_type1` FOREIGN KEY (`account_type_id`) REFERENCES `account_type` (`id`),
    CONSTRAINT `fk_user_has_account_type_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

UNLOCK TABLES;

--
-- Table structure for table `user_profile`
--
DROP TABLE IF EXISTS `user_profile`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `user_profile` (
    `user_id` int NOT NULL,
    `avatar_url` longtext,
    `bio` text,
    `mobile` varchar(50) DEFAULT NULL,
    `gender_id` int NOT NULL,
    `address_id` int NOT NULL,
    KEY `fk_user_profile_user1_idx` (`user_id`),
    KEY `fk_user_profile_gender1_idx` (`gender_id`),
    KEY `fk_user_profile_address1_idx` (`address_id`),
    CONSTRAINT `fk_user_profile_address1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`),
    CONSTRAINT `fk_user_profile_gender1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`),
    CONSTRAINT `fk_user_profile_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

UNLOCK TABLES;

--
-- Table structure for table `watchlist`
--
DROP TABLE IF EXISTS `watchlist`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `watchlist` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `product_id` int NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_watchlist_user1_idx` (`user_id`),
    KEY `fk_watchlist_product1_idx` (`product_id`),
    CONSTRAINT `fk_watchlist_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
    CONSTRAINT `fk_watchlist_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 28 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

UNLOCK TABLES;



