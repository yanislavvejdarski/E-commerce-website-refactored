DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(45) NOT NULL,
  `subscription` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `street_name` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `a_user_id_fk_idx` (`user_id`),
  CONSTRAINT `a_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE `attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `attributes`
--

LOCK TABLES `attributes` WRITE;
/*!40000 ALTER TABLE `attributes` DISABLE KEYS */;
INSERT INTO `attributes` VALUES (3,'os',1),(4,'ram',1),(5,'storage',1),(8,'screen',3),(9,'size',3),(10,'resolution',3);
/*!40000 ALTER TABLE `attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (1,1,1,'2019-12-26 21:53:17'),(1,2,1,'2020-01-14 21:16:58'),(3,1,1,'2020-01-11 18:16:53'),(1,2,1,'2020-01-14 21:18:40'),(2,2,1,'2020-01-16 11:46:11');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ;
--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Mobile Phones , Laptops and Tablets'),(2,'TVs , Audio , Cameras');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (1,'Sofia'),(2,'Kyustendil'),(3,'Plovdiv'),(4,'Varna'),(5,'Burgas'),(6,'Veliko Turnovo'),(7,'Ruse');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_table`
--

DROP TABLE IF EXISTS `new_table`;
CREATE TABLE `new_table` (
  `product_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `ram` int(11) DEFAULT NULL,
  `storage` float DEFAULT NULL,
  `os` varchar(45) DEFAULT NULL,
  `ice_container` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `new_table`
--

LOCK TABLES `new_table` WRITE;
/*!40000 ALTER TABLE `new_table` DISABLE KEYS */;
INSERT INTO `new_table` VALUES (1,1,4,256,'ios',NULL),(2,1,6,128,'android',NULL);
/*!40000 ALTER TABLE `new_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (64,2,17,'2020-01-15 17:00:19',1300),(65,2,19,'2020-01-15 17:19:13',3900),(66,2,20,'2020-01-15 19:33:37',1500),(67,2,20,'2020-01-15 19:34:29',100),(68,2,20,'2020-01-15 19:35:05',100),(69,2,20,'2020-01-15 19:35:32',700),(70,2,20,'2020-01-15 20:19:12',100),(71,2,20,'2020-01-15 21:15:40',100),(72,2,20,'2020-01-15 21:15:48',100),(73,2,20,'2020-01-15 21:18:03',100),(74,2,20,'2020-01-15 21:18:17',100),(75,2,22,'2020-01-15 22:38:07',6213),(76,2,22,'2020-01-15 22:38:57',1300),(77,2,22,'2020-01-15 22:41:06',1300),(78,2,22,'2020-01-15 22:44:56',1300),(79,2,23,'2020-01-16 00:17:08',100),(80,2,23,'2020-01-16 11:19:06',537),(81,2,23,'2020-01-16 11:45:13',3000);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_have_products`
--

DROP TABLE IF EXISTS `orders_have_products`;
CREATE TABLE `orders_have_products` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `orders_have_products`
--

LOCK TABLES `orders_have_products` WRITE;
/*!40000 ALTER TABLE `orders_have_products` DISABLE KEYS */;
INSERT INTO `orders_have_products` VALUES (64,2,1,1300),(65,2,2,2600),(65,3,1,1300),(66,1,2,200),(66,2,1,1300),(67,1,1,100),(68,1,1,100),(69,10,1,700),(70,1,1,100),(71,1,1,100),(72,1,1,100),(73,1,1,100),(74,1,1,100),(75,2,3,3900),(75,5,1,2313),(76,2,1,1300),(77,2,1,1300),(78,2,1,1300),(79,1,1,100),(80,6,1,537),(81,1,3,3000);
/*!40000 ALTER TABLE `orders_have_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producers`
--

DROP TABLE IF EXISTS `producers`;
CREATE TABLE `producers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `producers`
--

LOCK TABLES `producers` WRITE;
/*!40000 ALTER TABLE `producers` DISABLE KEYS */;
INSERT INTO `producers` VALUES (1,'samsung'),(2,'apple'),(3,'lg');
/*!40000 ALTER TABLE `producers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_attributes`
--

DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE `product_attributes` (
  `product_id` int(11) NOT NULL,
  `attribute_id` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`product_id`,`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `product_attributes`
--

LOCK TABLES `product_attributes` WRITE;
/*!40000 ALTER TABLE `product_attributes` DISABLE KEYS */;
INSERT INTO `product_attributes` VALUES (1,'3','ios'),(1,'4','4'),(1,'5','128 GB'),(2,'3','android'),(2,'4','6'),(2,'5','64 GB'),(3,'3','android'),(3,'4','8'),(3,'5','32 GB'),(4,'3','android'),(4,'4','4'),(4,'5','32 GB'),(5,'3','ios'),(5,'4','6'),(5,'5','32 GB'),(7,'10','1920 x 1080'),(7,'8','oled'),(7,'9','50 inch'),(14,'3','android'),(14,'4','4'),(14,'5','64 GB');
/*!40000 ALTER TABLE `product_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `producer_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `old_price` float DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image_url` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Iphone 11',2,1000,1500,1,16,'images/iphone-png.jpg','2019-12-18 21:37:58'),(2,'samsung galaxy S10',2,1000,1300,1,33,'images/samsung.jpg','2019-12-18 21:38:31'),(3,'sony',2,1300,1500,1,33,'images/sony.jpg','2019-12-18 21:39:03'),(4,'Huawei P20',1,1200,1500,1,33,'images/huawei.jpg','2020-01-08 15:39:02'),(5,'iphone 7',1,2313,2313,1,33,'images/iphone7.jpg','2020-01-12 20:08:42'),(6,'LG A8790',3,537,NULL,3,97,'images/1578921956.jpg','2020-01-13 15:25:56'),(7,'Panasonic FULL HD ',3,1876,NULL,3,65,'images/1578922025.jpg','2020-01-13 15:27:05'),(8,'Panasonic Camera 4K',2,1400,NULL,5,53,'images/1578922084.jpg','2020-01-13 15:28:04'),(9,'Sony 4K',2,654,NULL,5,33,'images/1578922123.jpg','2020-01-13 15:28:43'),(10,'Panasonic Audio System',1,700,NULL,4,63,'images/1578922167.png','2020-01-13 15:29:27'),(11,'Sony Audio',3,640,NULL,4,80,'images/1578922228.jpg','2020-01-13 15:30:28'),(12,'Samsung S920',1,1300,NULL,3,200,'images/1579125502.jpeg','2020-01-15 23:58:22'),(13,'LG 4k Smart TV',3,1450,NULL,3,30,'images/1579125564.jpg','2020-01-15 23:59:24'),(14,'Samsung Galaxy A20',1,600,680,1,50,'images/1579125728.jpg','2020-01-16 00:02:08'),(15,'Samsung Galaxy A20',1,240,NULL,1,20,'images/1579168169.jpg','2020-01-16 11:49:29');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types`
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `types`
--

LOCK TABLES `types` WRITE;
/*!40000 ALTER TABLE `types` DISABLE KEYS */;
INSERT INTO `types` VALUES (1,'Mobile Phones',1),(2,'Laptops',1),(3,'TVs',2),(4,'Audio',2),(5,'Camera',2);
/*!40000 ALTER TABLE `types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_favourite_products`
--

DROP TABLE IF EXISTS `user_favourite_products`;
CREATE TABLE `user_favourite_products` (
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `user_favourite_products`
--

LOCK TABLES `user_favourite_products` WRITE;
/*!40000 ALTER TABLE `user_favourite_products` DISABLE KEYS */;
INSERT INTO `user_favourite_products` VALUES (3,1,'2020-01-11 18:16:57'),(2,2,'2020-01-16 10:01:09');
/*!40000 ALTER TABLE `user_favourite_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_rate_products`
--

DROP TABLE IF EXISTS `user_rate_products`;
CREATE TABLE `user_rate_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stars` int(11) NOT NULL,
  `text` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ;
--
-- Dumping data for table `user_rate_products`
--

LOCK TABLES `user_rate_products` WRITE;
/*!40000 ALTER TABLE `user_rate_products` DISABLE KEYS */;
INSERT INTO `user_rate_products` VALUES (1,2,2,2,'I like it very much .......','2020-01-03 22:17:41'),(2,2,2,4,'Good Samsung','2020-01-03 22:19:23'),(3,2,2,4,'I think it is very good','2020-01-15 00:16:53'),(4,2,2,5,'I love the product . Its Awesome','2020-01-15 22:51:41'),(5,2,1,1,'Recomment 100 %','2020-01-15 22:53:28');
/*!40000 ALTER TABLE `user_rate_products` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'dsfsdf@abv.bg','$2y$10$ElCB9hyFmdV2U./aWM2U2uwDswWmtyDJ4DCAMnEXg1ikiv7n/zW6e','dsfsd','weklfmlwe',0,899853034,'2019-12-19 13:27:23','user','yes'),(2,'yanislav.vejdarski@gmail.com','$2y$10$5.JNUKVS6lTb/fvMahhgNO08Hsg9sWzqmloOzpzbz03sfl79nrFQK','Yanislav','Vejdarski',20,899055967,'2019-12-19 13:30:09','user','yes'),(3,'gyuksel_1995@abv.bg','$2y$10$fRsYtK1aZZB9U4CDqU7FouRsfL0eGe/4ya.4RZE77R.q/x2KETpLy','yanislav','yanislav',0,899055967,'2019-12-19 16:48:56','user','yes'),(4,'yanislav@gmail.com','$2y$10$riVU0zcNB.jvRiwUrO0A6.ya6Sjrw0c440Z6HStNUEGP2Mjnm7fZS','Yanisl','ADasdada',19,899768723,'2020-01-06 15:19:39','admin','no'),(5,'kopach@abv.bg','$2y$10$COgczVYLLakO5tTpZfyS.uMKRFHqi2tE2XaEQ8D1L0RQkIf50.D6C','Kopasd','Asdasd',20,899876543,'2020-01-15 21:38:05','admin','no'),(6,'admin@gmail.com','$2y$10$9nr66i3s23gadP989syufei7UNeaon8q4kBMs4/FfGHx0Ro3qs4Yu','Georgi','Ivanov',19,899655434,'2020-01-16 10:04:11','admin','no');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
