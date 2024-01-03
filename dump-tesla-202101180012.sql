-- MySQL dump 10.18  Distrib 10.3.27-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: tesla
-- ------------------------------------------------------
-- Server version	10.3.27-MariaDB-0+deb10u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `department_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department_name` text NOT NULL,
  `department_position` bigint(20) unsigned NOT NULL,
  `department_hidden` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (5,'Финансовый отдел',1,0),(6,'Дирекция',0,0),(9,'Отдел продаж',2,0);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `employee_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_name` text NOT NULL,
  `employee_position` bigint(20) unsigned NOT NULL,
  `employee_photo_location` varbinary(128) NOT NULL DEFAULT 'img/default.png',
  `employee_hidden` tinyint(1) NOT NULL DEFAULT 0,
  `department_id` bigint(20) unsigned NOT NULL,
  `employee_post` text NOT NULL,
  PRIMARY KEY (`employee_id`),
  KEY `employees_FK` (`department_id`),
  CONSTRAINT `employees_FK` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (23,'Алексей Еремчук',0,'img/team/620202333_b1ee5d8599017971ea5c59e44d555351.jpg',0,6,'Основатель и генеральный директор'),(27,'Ольга Нечаева',0,'img/team/1439174780_9fea83f29cf8c2acd66a97188ebe3541.jpg',0,5,'Финансовый директор'),(30,'Марина Дидук',0,'img/team/1885477151_241651494311316820e94ce26655a887.jpg',0,9,'Менеджер по продажам'),(31,'Юлия Вассерман',0,'img/team/1869956266_e1de5906264c27b056075a202991fea1.jpg',0,9,'Менеджер по продажам');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_groups`
--

DROP TABLE IF EXISTS `faq_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq_groups` (
  `faq_group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `faq_group_title` text NOT NULL,
  `faq_group_position` bigint(20) unsigned NOT NULL,
  `faq_group_hidden` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`faq_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_groups`
--

LOCK TABLES `faq_groups` WRITE;
/*!40000 ALTER TABLE `faq_groups` DISABLE KEYS */;
INSERT INTO `faq_groups` VALUES (40,'Электромобили',0,0),(41,'Tesla',1,0);
/*!40000 ALTER TABLE `faq_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `faq_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `faq_title` text NOT NULL,
  `faq_description` text NOT NULL,
  `faq_group_id` bigint(20) unsigned NOT NULL,
  `faq_position` bigint(20) unsigned NOT NULL,
  `faq_hidden` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`faq_id`),
  KEY `faqs_FK` (`faq_group_id`),
  CONSTRAINT `faqs_FK` FOREIGN KEY (`faq_group_id`) REFERENCES `faq_groups` (`faq_group_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (22,'Как управлять электромобилем?','В сущности электромобиль — такой же автомобиль, просто с электрическим движком вместо ДВС и с батареей вместо бензобака. Управление не отличается принципиально. Электромобили — это следующий шаг в сторону удобства. Передач просто нет, переключать нечего, даже автоматике.\nК этому придется немного привыкнуть — но к хорошему привыкаешь быстро.\nВодителю это дает новую динамику разгона — электромобиль готов сразу выдавать всю свою мощь, он крайне удобен при обгоне на трассе.\nВ электромобилях используется две системы торможения. Обычное, такое же, как у бензиновых — для резкой остановки. И дополнительная система рекуперативного торможения.\nЭто рекуперативное торможение позволяет собирать переданную колесным осям энергию обратно в батарею. Разумеется, с определенными потерями — но и такой сбор позволяет использовать энергию эффективнее и дольше ездить на одной зарядке.',40,2,0),(23,'Сколько электричества потребляет электромобиль при зарядке?','Сколько электричества потребляет электромобиль при зарядке? На самом деле, узнать это просто — достаточно посмотреть на цифру емкости аккумулятора. Она измеряется в тех же самых единицах, что и электричество в счетах коммунальных услуг — в киловатт‑часах. Вот именно столько электроэнергии уйдет на полную зарядку электромобиля. Плюс возможны небольшие потери энергии при зарядке, но они сравнительно невелики, при беглом подсчете можно не учитывать.\nУ большинства электромобилей в «полном баке» умещается от 40 до 80 кВт‑ч, хотя бывают исключения в обе стороны.',40,1,0),(24,'Много ли Tesla уже есть на наших дорогах?',' С каждым годом в России появляется все больше и больше электромобилей Tesla. По официальным данным аналитического агентства «Автостат», динамика продаж марки в России растет отличными темпами — 8 машин было продано в 2013 году, а в 2014 году это число выросло до 82.\nПо состоянию уже на 1 июля 2015 года, на дорогах нашей страны числилось 122 официально зарегистрированных Tesla',41,3,0),(25,'Каков реальный пробег на одной зарядке? Сильно ли он уменьшается зимой? Вообще, едет ли она зимой? ','Реальный пробег Tesla Model S с 85-киловаттной батареей в теплое время года, при усредненной городской эксплуатации, составляет 350-400 км на одном полном заряде. \n Знаете, в какой стране Европы регулярно бьются рекорды по продажам Tesla? В холодной Норвегии, где зимой температура может опускаться и до -40 по Цельсию, что вполне сопоставимо даже с самыми суровыми зимами в Сибири.\nТем не менее, по дорогам Норвегии колесит более 6 тысяч Tesla Model S. Судя по отзывам владельцев, в зимнее время запас хода снижается примерно на 20-30% — это означает, что его в любом случае хватит среднестатистическому городскому жителю на каждый день.\nПомимо вопроса пробега, зимой Tesla попросту значительно удобнее бензиновых или дизельных авто — нет сложностей с запуском мотора в сильный мороз, нет необходимости ждать прогрева. В Tesla очень быстро греется салон (так как отопитель полностью электрический) и сиденья, а кроме того, можно установить комфортную температуру в салоне при помощи мобильного приложения, еще до выхода из дома. А температура самой батареи постоянно поддерживается бортовым компьютером автоматически на оптимальном уровне. ',41,0,0),(26,'Безопасна ли Tesla на наших дорогах? ','Естественно, что для допуска на дороги общего пользования, Tesla Model S прошла все необходимые испытания.Не просто прошла, а стала одним из немногих транспортных средств, получивших максимальный общий рейтинг в 5 звезд по обеим методикам во всех видах краш-тестов.\nОдним из элементов, обеспечивающих такой высокий уровень безопасности, является батарея. И этот факт является одним из предметов опасений потенциальных владельцев Tesla — бытует мнение, что неизвестно, как поведет себя батарея при механическом повреждении. ',41,0,0);
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_name` text NOT NULL,
  `order_surname` text NOT NULL,
  `order_email` text NOT NULL,
  `order_address` text NOT NULL,
  `order_comment` text DEFAULT NULL,
  `order_paid` tinyint(1) NOT NULL DEFAULT 0,
  `order_date` datetime NOT NULL,
  `order_sum` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (20,'Viacheslav','Davydov','dvo@criptext.com','checkhov street','comment',1,'2021-01-13 17:21:17',5102),(21,'4','4','h@e4','4','',1,'2021-01-17 14:18:38',20),(22,'f','f','d@v','f','',0,'2021-01-17 23:08:36',35509),(23,'ff','f','f@f','f','',1,'2021-01-17 23:09:02',4900);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_products`
--

DROP TABLE IF EXISTS `orders_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders_products` (
  `order_product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_count` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_product_id`),
  KEY `orders_produts_FK` (`product_id`),
  KEY `orders_products_FK` (`order_id`),
  CONSTRAINT `orders_products_FK` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orders_products_FK_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders_products`
--

LOCK TABLES `orders_products` WRITE;
/*!40000 ALTER TABLE `orders_products` DISABLE KEYS */;
INSERT INTO `orders_products` VALUES (12,20,31,2),(13,20,26,1),(14,21,32,1),(15,22,30,3),(16,22,31,2),(17,23,26,1);
/*!40000 ALTER TABLE `orders_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_price` bigint(20) unsigned NOT NULL,
  `product_title` text NOT NULL,
  `product_description` text NOT NULL,
  `product_type` tinyint(1) NOT NULL,
  `product_photo` text NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (26,4900,'Tesla Model S P85D (700 л.с. / электро)','<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" class=\"w-100\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"text-align:center\">Год выпуска</td>\r\n			<td style=\"text-align:center\">2015</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Состояние</td>\r\n			<td style=\"text-align:center\">С пробегом (50 000 км)</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Кузов</td>\r\n			<td style=\"text-align:center\">Лифтбэк</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Привод</td>\r\n			<td style=\"text-align:center\">Полный (AWD)</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Коробка</td>\r\n			<td style=\"text-align:center\">Автоматическая</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Руль</td>\r\n			<td style=\"text-align:center\">Левый</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Габариты (мм)</td>\r\n			<td style=\"text-align:center\">4971 х 1963 х 1445</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Колесная база (мм)</td>\r\n			<td style=\"text-align:center\">2959</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p><u><em><strong><span style=\"color:#e74c3c\">Дополнительные характерисики:</span></strong></em></u></p>\r\n\r\n<ul>\r\n	<li>Премиум салон</li>\r\n	<li>Элементы декора &ndash; карбон</li>\r\n	<li>Отделка крыши из алькантары</li>\r\n	<li>Премиальный пакет (автоматические передние двери, воздушный фильтр HEPA, 2 угольных воздушных фильтра)</li>\r\n	<li>Зимний пакет (подогрев всех сидений, подогрев руля, подогрев зоны щеток стеклоочистителей и форсунок омывателей ветрового стекла)</li>\r\n	<li>Кожаные подлокотники и отдела руля</li>\r\n	<li>Редкая четырехместная комплектация - задний диван с консолью</li>\r\n	<li>Усовершенствованный автопилот v1.0</li>\r\n	<li>Умная пневмоподвеска</li>\r\n	<li>Противоугонная система</li>\r\n	<li>В комплекте зарядное устройство mobile connector кабель 220 и 380v, кабель Mennekes</li>\r\n	<li>Мягкая LED подсветка интерьера</li>\r\n	<li>Улучшенная музыкальная система</li>\r\n</ul>\r\n\r\n<p><img alt=\"\" class=\"img img-fluid\" src=\"/img/products/description/627132817_19b847f2b9e2b4772f4d12b7cc6635a3.JPG\" /></p>\r\n\r\n<p><img alt=\"\" class=\"img img-fluid\" src=\"/img/products/description/605862491_09dcd73fd19d240b65963b446c74dc2e.JPG\" /></p>\r\n\r\n<p><img alt=\"\" class=\"img img-fluid\" src=\"/img/products/description/185100766_8984ec562e92271e28901254b0a11ca5.JPG\" /></p>\r\n',0,'img/products/1531871448_fc35a730e6c4ba58ad03ade73c9a0d4a.JPG'),(30,11769,'Tesla Model X Performance Ludicrous','<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" class=\"w-100\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"text-align:center\">Год выпуска</td>\r\n			<td style=\"text-align:center\">2020</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Состояние</td>\r\n			<td style=\"text-align:center\">Новый</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Кузов</td>\r\n			<td style=\"text-align:center\">SUV</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Привод</td>\r\n			<td style=\"text-align:center\">Полный (AWD)</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Коробка</td>\r\n			<td style=\"text-align:center\">Автоматическая</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Руль</td>\r\n			<td style=\"text-align:center\">Левый</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Габариты (мм)</td>\r\n			<td style=\"text-align:center\">5037 х 2070 х 1626</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center\">Колесная база (мм)</td>\r\n			<td style=\"text-align:center\">\r\n			<p>2964</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<h1 style=\"text-align:center\">Дополнительные функции:</h1>\r\n\r\n<ol>\r\n	<li>Цвет салона: черный Premium</li>\r\n	<li>Отделка карбон</li>\r\n	<li>Подогрев руля</li>\r\n	<li>Обогрев сидений</li>\r\n	<li>Парктроники (передние и задние)</li>\r\n	<li>Подушки безопасности: передние, задние, боковые</li>\r\n	<li>Обивка салона: алькантара</li>\r\n	<li>Сигнализация &mdash; штатная</li>\r\n	<li>Электропривод зеркал</li>\r\n	<li>Стеклоподъемники: электро все</li>\r\n	<li>Регулировка руля: электро</li>\r\n	<li>Сиденье водителя: с памятью положения</li>\r\n	<li>Сиденье пассажира: с памятью положения</li>\r\n	<li>Шестиместная комплектация</li>\r\n</ol>\r\n\r\n<p><img alt=\"\" class=\"img img-fluid\" src=\"/img/products/description/1509385279_cae33878719b7de4ec44c411acaa64e7.JPG\" /></p>\r\n\r\n<p><img alt=\"\" class=\"img img-fluid\" src=\"/img/products/description/303618664_2a6acd9edb559718fb3c54b9750278ca.JPG\" /></p>\r\n\r\n<p><img alt=\"\" class=\"img img-fluid\" src=\"/img/products/description/1223614852_1b2686dd41f3d0a9cb5eff6a8df6ef6f.JPG\" /></p>\r\n',0,'img/products/882975615_8756ddd57fa7bb31b8be36bd43fc4fa6.JPG'),(31,101,'Tesla Wall Connector','<p><strong>Tesla Wall Connector </strong>&mdash; удобная зарядная станция для источников тока 208-250 вольт питания. Рабочий ток (до 80 ампер) будет настроен электриком при установке. Такой диапазон тока позволяет настроить зарядную станцию почти для любого источника питания.<br />\r\nДля быстрой скорости зарядки, необходимо подвести электрическую линию и установить предохранители на 80 ампер или 100 ампер. Устройство также способно на 15, 20, 30, 40, или 50 ампер, в зависимости от возможностей входной линии. Обратите внимание, что Model S должны быть оснащены опцией Dual Chargers что бы заряжаться током выше 40 ампер (50 А цепи).</p>\r\n\r\n<p><img alt=\"\" class=\"img img-fluid\" src=\"/img/products/description/1404442983_cfdff9eadafa9b6f39a048a09ac246fa.jpg\" /></p>\r\n\r\n<p>Tesla Wall Connector существенно упрощает процесс зарядки электромобилей &laquo;Тесла&raquo; Model X и Model S любой модификации. Коннектор устанавливается на стену гаража или на парковке и не требует использования удлинителей и каких-либо переходников. Время полной зарядки составляет всего 5-6 часов. Однако прежде чем приобрести коннектор, следует убедиться, что ваш электромобиль Tesla поддерживает ток 80А.</p>\r\n\r\n<p>Зарядное устройство имеет ряд особенностей. Поскольку оно предназначено для использования на улице, то разъемы и кабели защищены специальным покрытием от воздействия влаги, пыли и твердых предметов степени IP44. Кроме того, устройство может работать от однофазного дизель-генератора, что особенно оценят владельцы электромобилей Tesla, живущие за городом. Установка устройства довольно проста &mdash; с ней справится любой квалифицированный электрик.</p>\r\n\r\n<p><img alt=\"\" class=\"img img-fluid\" src=\"/img/products/description/309141140_f09a845da74dea35a884411ec2064900.jpg\" /></p>\r\n',1,'img/products/802484828_08dcc24114052bdc813d371d3bf6516b.jpg'),(32,20,'Органайзер для переднего багажника','<p>Подходит только для заднеприводных Tesla Model S</p>\r\n\r\n<p><img alt=\"\" class=\"img img-fluid\" src=\"/img/products/description/612019733_0f291974859de1763c958708bf9f0c9a.jpg\" /></p>\r\n',1,'img/products/797865148_4c874f6c18dd4ee40db15121708c2394.jpg'),(33,750,'Оригинальные новые колеса R22 для Tesla Model X','<p>Резина Good Year Eagle F1 R22 для Tesla Model X</p>\r\n',1,'img/products/1440282538_fb84aafb2eec8fe57148e96a3362f253.jpg');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(256) NOT NULL,
  `user_role` varchar(256) NOT NULL,
  `user_password` varchar(256) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin','$2y$10$cElXNCtZSjdXZzhZSDJJQe9KuS06jizL5tpkokCQNNpDYfdRTVpaK');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'tesla'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-18  0:12:44
