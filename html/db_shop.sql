-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.24-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for db_shop
CREATE DATABASE IF NOT EXISTS `db_shop` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `db_shop`;

-- Dumping structure for table db_shop.tbl_admin
CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `adminId` int(11) NOT NULL AUTO_INCREMENT,
  `adminName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adminUser` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adminEmail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adminPass` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `level` tinyint(4) NOT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adminId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table db_shop.tbl_admin: ~1 rows (approximately)
/*!40000 ALTER TABLE `tbl_admin` DISABLE KEYS */;
INSERT INTO `tbl_admin` (`adminId`, `adminName`, `adminUser`, `adminEmail`, `adminPass`, `level`, `del_flg`) VALUES
	(1, 'Linh Kieu', 'admin', 'mr.linh1090@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 0, 0);
/*!40000 ALTER TABLE `tbl_admin` ENABLE KEYS */;

-- Dumping structure for table db_shop.tbl_brand
CREATE TABLE IF NOT EXISTS `tbl_brand` (
  `brandId` int(11) NOT NULL AUTO_INCREMENT,
  `brandName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`brandId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table db_shop.tbl_brand: ~4 rows (approximately)
/*!40000 ALTER TABLE `tbl_brand` DISABLE KEYS */;
INSERT INTO `tbl_brand` (`brandId`, `brandName`, `del_flg`) VALUES
	(1, 'Apple', 0),
	(2, 'Samsung', 0),
	(3, 'Acer', 0),
	(4, 'Canon', 0);
/*!40000 ALTER TABLE `tbl_brand` ENABLE KEYS */;

-- Dumping structure for table db_shop.tbl_cart
CREATE TABLE IF NOT EXISTS `tbl_cart` (
  `cartId` int(11) NOT NULL AUTO_INCREMENT,
  `sId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `productId` int(11) NOT NULL,
  `productName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` float(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `del_flg` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cartId`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table db_shop.tbl_cart: ~4 rows (approximately)
/*!40000 ALTER TABLE `tbl_cart` DISABLE KEYS */;
INSERT INTO `tbl_cart` (`cartId`, `sId`, `productId`, `productName`, `price`, `quantity`, `image`, `del_flg`) VALUES
	(33, '5lemut9unnpphv763b2i53b2b2', 5, 'Macbook Air 13 128GB MQD32SA/A (2017)', 24.00, 3, 'upload/54dcc9c9f0.jpg', 0),
	(34, '5lemut9unnpphv763b2i53b2b2', 1, 'Lorem Ipsum is simply test', 505.22, 3, 'upload/feature-pic1.png', 0),
	(35, '48be8jadul8f0uqr1h2so1ies0', 2, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 500.00, 2, 'upload/feature-pic2.jpg', 0),
	(36, '48be8jadul8f0uqr1h2so1ies0', 5, 'Macbook Air 13 128GB MQD32SA/A (2017)', 24.00, 2, 'upload/54dcc9c9f0.jpg', 0);
/*!40000 ALTER TABLE `tbl_cart` ENABLE KEYS */;

-- Dumping structure for table db_shop.tbl_category
CREATE TABLE IF NOT EXISTS `tbl_category` (
  `catId` int(11) NOT NULL AUTO_INCREMENT,
  `catName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `del_flg` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`catId`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table db_shop.tbl_category: ~12 rows (approximately)
/*!40000 ALTER TABLE `tbl_category` DISABLE KEYS */;
INSERT INTO `tbl_category` (`catId`, `catName`, `del_flg`) VALUES
	(1, 'Laptop', 0),
	(2, 'Mobile Phones', 0),
	(3, 'Desktop', 0),
	(4, 'Accessories', 0),
	(5, 'Software', 0),
	(6, 'Sports, Fitness', 0),
	(7, 'Footwear', 0),
	(8, 'Jewellery', 0),
	(9, 'Clothing', 0),
	(10, 'Home Decor Kitchen', 0),
	(11, 'Beauty Healthcare', 0),
	(12, 'Toys, Kids Babies', 0);
/*!40000 ALTER TABLE `tbl_category` ENABLE KEYS */;

-- Dumping structure for table db_shop.tbl_customer
CREATE TABLE IF NOT EXISTS `tbl_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `phone` int(15) NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table db_shop.tbl_customer: ~3 rows (approximately)
/*!40000 ALTER TABLE `tbl_customer` DISABLE KEYS */;
INSERT INTO `tbl_customer` (`id`, `name`, `address`, `city`, `country`, `zip`, `phone`, `email`, `pass`, `del_flg`) VALUES
	(1, 'Kieu Hoa Linh', 'Hcm', 'HCM', 'DZ', '', 968146460, 'linhkhpk00213@gmail.com', 'd41d8cd98f00b204e9800998ecf8427e', 0),
	(2, 'Kieu Hoa Linh', 'da nang', 'HCM', 'AW', '', 968146460, 'mr.linh1090@gmail.com', '202cb962ac59075b964b07152d234b70', 0),
	(3, 'Kieu Hoa Linh', '123', 'HCM', 'AR', '123', 968146460, 'l_kieu@vision-net.co.jp', '202cb962ac59075b964b07152d234b70', 0);
/*!40000 ALTER TABLE `tbl_customer` ENABLE KEYS */;

-- Dumping structure for table db_shop.tbl_product
CREATE TABLE IF NOT EXISTS `tbl_product` (
  `productId` int(11) NOT NULL AUTO_INCREMENT,
  `productName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `catId` int(11) NOT NULL,
  `brandId` int(11) NOT NULL,
  `body` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `feature` tinyint(1) NOT NULL DEFAULT '0',
  `del_flg` int(1) DEFAULT '0',
  PRIMARY KEY (`productId`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table db_shop.tbl_product: ~17 rows (approximately)
/*!40000 ALTER TABLE `tbl_product` DISABLE KEYS */;
INSERT INTO `tbl_product` (`productId`, `productName`, `catId`, `brandId`, `body`, `price`, `image`, `feature`, `del_flg`) VALUES
	(1, 'Lorem Ipsum is simply test', 12, 1, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit test', 505.22, 'feature-pic1.png', 1, 0),
	(2, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 1, 2, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic2.jpg', 1, 0),
	(3, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 1, 3, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic3.jpg', 1, 0),
	(4, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 4, 4, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic4.png', 1, 0),
	(5, 'Macbook Air 13 128GB MQD32SA/A (2017)', 1, 1, 'Với thiết kế truyền thống của dòng MacBook Air, phiên bản 2017 này cũng không có thay đổi khi được trang bị lớp vỏ nhôm nguyên khối Unibody sang trọng, chỉ mỏng 1.7cm và trọng lượng là 1.35kg, rất tiện lợi và dễ dàng để bạn luôn mang theo bên mình.  Logo quả táo Apple phát sáng tạo nên đặc trưng riêng khác biệt.', 23.999, 'feature-pic1.png', 1, 0),
	(6, 'Lorem Ipsum is simply test', 12, 1, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit test', 505.22, 'feature-pic1.png', 1, 0),
	(7, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 4, 4, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic4.png', 1, 0),
	(8, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 1, 3, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic3.jpg', 1, 0),
	(9, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 1, 2, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic2.jpg', 1, 0),
	(10, 'Lorem Ipsum is simply test', 12, 1, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit test', 505.22, 'feature-pic1.png', 0, 0),
	(11, 'Macbook Air 13 128GB MQD32SA/A (2017)', 1, 1, 'Với thiết kế truyền thống của dòng MacBook Air, phiên bản 2017 này cũng không có thay đổi khi được trang bị lớp vỏ nhôm nguyên khối Unibody sang trọng, chỉ mỏng 1.7cm và trọng lượng là 1.35kg, rất tiện lợi và dễ dàng để bạn luôn mang theo bên mình.  Logo quả táo Apple phát sáng tạo nên đặc trưng riêng khác biệt.', 23.999, 'feature-pic1.png', 0, 0),
	(12, 'Lorem Ipsum is simply test', 12, 1, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit test', 505.22, 'feature-pic1.png', 0, 0),
	(13, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 1, 3, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic3.jpg', 0, 0),
	(14, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 1, 2, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic2.jpg', 0, 0),
	(15, 'Lorem Ipsum is simply test', 12, 1, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit test', 505.22, 'feature-pic1.png', 0, 0),
	(16, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 4, 4, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic4.png', 0, 0),
	(17, 'LOREM IPSUM IS SIMPLY DUMMY TEXT', 1, 3, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 500, 'feature-pic3.jpg', 0, 0);
/*!40000 ALTER TABLE `tbl_product` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
