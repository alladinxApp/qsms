/*
SQLyog Ultimate v8.55 
MySQL - 5.1.41 : Database - sqms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sqms` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `sqms`;

/*Table structure for table `tbl_accessory` */

DROP TABLE IF EXISTS `tbl_accessory`;

CREATE TABLE `tbl_accessory` (
  `accessory_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `accessory` varchar(60) COLLATE ascii_bin NOT NULL,
  `access_disc` decimal(20,2) NOT NULL,
  `access_srp` decimal(20,2) NOT NULL,
  `access_low` double(10,0) NOT NULL,
  `access_onhand` decimal(10,0) NOT NULL,
  `access_status` varchar(10) COLLATE ascii_bin NOT NULL,
  `access_created` datetime NOT NULL,
  PRIMARY KEY (`accessory_id`),
  UNIQUE KEY `accessory_id` (`accessory_id`,`accessory`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_accessory` */

insert  into `tbl_accessory`(`accessory_id`,`accessory`,`access_disc`,`access_srp`,`access_low`,`access_onhand`,`access_status`,`access_created`) values ('ACC00000001','SPOILER','1500.00','2000.00',10,'51','Active','2014-06-11 19:20:42'),('ACC00000002','LED SIDELIGHT','1000.00','1500.00',10,'30','Active','2014-06-11 19:22:13'),('ACC00000003','SIDE SKIRT','200.00','2500.00',5,'10','Active','2014-06-12 16:12:17');

/*Table structure for table `tbl_account` */

DROP TABLE IF EXISTS `tbl_account`;

CREATE TABLE `tbl_account` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(40) NOT NULL,
  `user_type` varchar(150) NOT NULL,
  `user_lname` varchar(255) NOT NULL,
  `user_fname` varchar(255) NOT NULL,
  `user_photo` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_edited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_account` */

insert  into `tbl_account`(`user_id`,`user_name`,`user_password`,`user_type`,`user_lname`,`user_fname`,`user_photo`,`date_created`,`date_edited`) values (1,'admin','admin','Admin','Admin','User','','2014-06-23 05:50:53','0000-00-00 00:00:00'),(2,'test','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','Admin','test','test','','2014-05-20 08:35:42','0000-00-00 00:00:00');

/*Table structure for table `tbl_billing` */

DROP TABLE IF EXISTS `tbl_billing`;

CREATE TABLE `tbl_billing` (
  `wo_refno` varchar(20) NOT NULL,
  `billing_refno` varchar(20) NOT NULL,
  `billing_date` datetime DEFAULT NULL,
  `total_amount` decimal(20,2) DEFAULT NULL,
  `billing_status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`wo_refno`,`billing_refno`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_billing` */

insert  into `tbl_billing`(`wo_refno`,`billing_refno`,`billing_date`,`total_amount`,`billing_status`) values ('WO00000001','00000003','2014-12-07 23:25:35','672.00','1'),('WO00000003','00000004','2014-12-12 11:17:18','1120.00','1'),('WO00000002','00000002','2014-12-07 23:17:27','621.60','1'),('WO00000004','00000005','2015-02-11 03:19:35','672.00','0'),('WO00000006','00000006','2015-02-11 03:19:59','616.00','0'),('WO00000005','00000007','2015-02-11 03:20:25','621.60','0'),('WO00000010','00000011','2015-03-18 15:16:52','840.00','0'),('WO00000011','00000012','2015-03-18 15:44:11','2744.00','1'),('WO00000012','00000013','2015-05-26 08:16:52','571.20','1'),('WO00000010','00000014','2015-05-26 08:40:43','840.00','1'),('WO00000017','00000017','2015-06-09 10:00:09','2632.00','1'),('WO00000018','00000016','2015-06-09 10:04:08','224.00','1'),('WO00000016','00000017','2015-08-03 15:28:08','728.00','0');

/*Table structure for table `tbl_color` */

DROP TABLE IF EXISTS `tbl_color`;

CREATE TABLE `tbl_color` (
  `color_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `color` varchar(20) COLLATE ascii_bin NOT NULL,
  `color_created` datetime NOT NULL,
  PRIMARY KEY (`color_id`),
  UNIQUE KEY `color_id` (`color_id`),
  UNIQUE KEY `color` (`color`),
  KEY `color_2` (`color`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_color` */

insert  into `tbl_color`(`color_id`,`color`,`color_created`) values ('COL00000001','WHITER','2014-06-11 19:14:15'),('COL00000002','YELLOWISH','2014-06-11 19:14:21'),('COL00000003','BLUEISH','2014-06-11 19:14:25');

/*Table structure for table `tbl_configuration` */

DROP TABLE IF EXISTS `tbl_configuration`;

CREATE TABLE `tbl_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_type` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `value` varchar(20) DEFAULT NULL,
  `remarks` longtext,
  `status` int(11) DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_by` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_configuration` */

insert  into `tbl_configuration`(`id`,`config_type`,`description`,`value`,`remarks`,`status`,`created_date`,`created_by`,`modified_date`,`modified_by`) values (1,'vat_value','VAT VALUE','15',NULL,1,'2015-09-28 11:52:35','SYSTEM',NULL,NULL);

/*Table structure for table `tbl_controlno` */

DROP TABLE IF EXISTS `tbl_controlno`;

CREATE TABLE `tbl_controlno` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `control_type` varchar(20) DEFAULT NULL,
  `digit` int(5) DEFAULT NULL,
  `lastseqno` int(20) DEFAULT NULL,
  `control_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_controlno` */

insert  into `tbl_controlno`(`id`,`control_type`,`digit`,`lastseqno`,`control_code`) values (1,'CUSTOMER',8,13,'CUST'),(2,'VEHICLE',8,10,'VEH'),(3,'COLOR',8,3,'COL'),(4,'EMPLOYEE',8,7,'EMP'),(11,'JOB',8,3,'JOB'),(5,'MAKE',8,3,'MAK'),(6,'MODEL',8,2,'MDL'),(7,'YEAR',8,14,'YR'),(8,'PARTS',8,7,'PAR'),(9,'MATERIAL',8,2,'MAT'),(10,'ACCESSORY',8,3,'ACC'),(12,'WOCATEGORY',8,3,'WOC'),(13,'IDLE',8,5,'IDL'),(14,'PAYMENT',8,4,'PAY'),(15,'ESTIMATEREFNO',8,29,'ER'),(16,'WORKORDER',8,19,'WO'),(17,'PURCHASEORDER',8,0,'PO'),(18,'BILLING',8,17,''),(19,'ONLINE_ESTIMATE',8,8,'OE'),(20,'PACKAGE',8,3,'PAC');

/*Table structure for table `tbl_customer` */

DROP TABLE IF EXISTS `tbl_customer`;

CREATE TABLE `tbl_customer` (
  `cust_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `salutation` varchar(60) COLLATE ascii_bin NOT NULL,
  `lastname` varchar(60) COLLATE ascii_bin NOT NULL,
  `firstname` varchar(60) COLLATE ascii_bin NOT NULL,
  `middlename` varchar(60) COLLATE ascii_bin NOT NULL,
  `address` varchar(200) COLLATE ascii_bin NOT NULL,
  `city` varchar(60) COLLATE ascii_bin NOT NULL,
  `province` varchar(60) COLLATE ascii_bin NOT NULL,
  `zipcode` bigint(10) NOT NULL,
  `birthday` varchar(30) COLLATE ascii_bin NOT NULL,
  `gender` varchar(60) COLLATE ascii_bin NOT NULL,
  `tin` varchar(20) COLLATE ascii_bin NOT NULL,
  `company` varchar(100) COLLATE ascii_bin NOT NULL,
  `source` varchar(60) COLLATE ascii_bin NOT NULL,
  `email` varchar(60) COLLATE ascii_bin NOT NULL,
  `landline` varchar(20) COLLATE ascii_bin NOT NULL,
  `fax` varchar(20) COLLATE ascii_bin NOT NULL,
  `mobile` varchar(20) COLLATE ascii_bin NOT NULL,
  `cust_created` datetime NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `cust_id` (`cust_id`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_customer` */

insert  into `tbl_customer`(`cust_id`,`salutation`,`lastname`,`firstname`,`middlename`,`address`,`city`,`province`,`zipcode`,`birthday`,`gender`,`tin`,`company`,`source`,`email`,`landline`,`fax`,`mobile`,`cust_created`) values ('CUST00000001','Ms.','RAMIREZ','ELLEN','MORALETA','Tambo','Paranaque','NCR',1701,'May 22, 1975','Female','123-456-790','The Giant','walkin','njdhhd@yahoo.com','786-5678','765-9876','0918-987654','2014-06-17 13:09:21'),('CUST00000013','Mr.','a','a','a','a','a','a',0,'06/04/2015','Male','a','a','a','a','a','a','a','2015-06-25 17:10:49');

/*Table structure for table `tbl_drop_data` */

DROP TABLE IF EXISTS `tbl_drop_data`;

CREATE TABLE `tbl_drop_data` (
  `drop_id` varchar(20) DEFAULT NULL,
  `drop_data` varchar(65) DEFAULT NULL,
  `drop_display` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_drop_data` */

insert  into `tbl_drop_data`(`drop_id`,`drop_data`,`drop_display`) values ('00:00','00:00','idle_time'),('00:30','00:30','idle_time'),('01:00','01:00','idle_time'),('01:30','01:30','idle_time'),('02:00','02:00','idle_time'),('02:30','02:30','idle_time'),('03:00','03:00','idle_time'),('03:30','03:30','idle_time'),('04:00','04:00','idle_time'),('04:30','04:30','idle_time'),('05:00','05:00','idle_time'),('05:30','05:30','idle_time'),('06:00','06:00','idle_time'),('06:30','06:30','idle_time'),('07:00','07:00','idle_time'),('07:30','07:30','idle_time'),('08:00','08:00','idle_time'),('08:30','08:30','idle_time'),('09:00','09:00','idle_time'),('09:30','09:30','idle_time'),('10:00','10:00','idle_time'),('10:30','10:30','idle_time'),('11:00','11:00','idle_time'),('11:30','11:30','idle_time');

/*Table structure for table `tbl_employee` */

DROP TABLE IF EXISTS `tbl_employee`;

CREATE TABLE `tbl_employee` (
  `emp_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `employee` varchar(60) COLLATE ascii_bin NOT NULL,
  `position` varchar(50) COLLATE ascii_bin DEFAULT NULL,
  `emp_status` enum('0','1','2') COLLATE ascii_bin NOT NULL DEFAULT '1',
  `emp_image` varchar(100) COLLATE ascii_bin NOT NULL,
  `emp_created` datetime NOT NULL,
  `contactno` varchar(50) COLLATE ascii_bin DEFAULT NULL,
  `emailaddress` varchar(100) COLLATE ascii_bin DEFAULT NULL,
  PRIMARY KEY (`emp_id`),
  UNIQUE KEY `emp_id` (`emp_id`,`employee`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_employee` */

insert  into `tbl_employee`(`emp_id`,`employee`,`position`,`emp_status`,`emp_image`,`emp_created`,`contactno`,`emailaddress`) values ('EMP00000001','ROBERT CUBAR',NULL,'1','','2014-06-17 01:18:19',NULL,NULL),('EMP00000002','DENNIS PADILLA',NULL,'1','EMP00000002.png','2014-06-17 01:18:40',NULL,NULL);

/*Table structure for table `tbl_estimate` */

DROP TABLE IF EXISTS `tbl_estimate`;

CREATE TABLE `tbl_estimate` (
  `estimate_ref` varchar(20) NOT NULL,
  `cust_id` varchar(20) DEFAULT NULL,
  `vehicle_id` varchar(20) DEFAULT NULL,
  `subtotal` decimal(20,2) DEFAULT NULL,
  `discount` decimal(20,2) DEFAULT NULL,
  `discounted_price` decimal(20,2) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `total_amount` decimal(20,2) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`estimate_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_estimate` */

/*Table structure for table `tbl_estimate_cost` */

DROP TABLE IF EXISTS `tbl_estimate_cost`;

CREATE TABLE `tbl_estimate_cost` (
  `estimate_refno` varchar(20) NOT NULL,
  `wo_refno` varchar(20) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `customer_id` varchar(20) DEFAULT NULL,
  `vehicle_id` varchar(20) DEFAULT NULL,
  `plate_no` varchar(20) DEFAULT NULL,
  `year` varchar(20) DEFAULT NULL,
  `make` varchar(20) DEFAULT NULL,
  `model` varchar(20) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `engine_no` varchar(100) DEFAULT NULL,
  `chassis_no` varchar(100) DEFAULT NULL,
  `total_amount` decimal(20,2) DEFAULT NULL,
  `trans_status` enum('0','1','2','3') DEFAULT '0',
  PRIMARY KEY (`estimate_refno`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_estimate_cost` */

/*Table structure for table `tbl_idle` */

DROP TABLE IF EXISTS `tbl_idle`;

CREATE TABLE `tbl_idle` (
  `idle_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `idle_name` varchar(60) COLLATE ascii_bin NOT NULL,
  `idle_created` datetime NOT NULL,
  PRIMARY KEY (`idle_id`),
  UNIQUE KEY `idle_id` (`idle_id`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_idle` */

insert  into `tbl_idle`(`idle_id`,`idle_name`,`idle_created`) values ('IDL00000001','BREAK TIME','2014-06-11 19:26:16'),('IDL00000002','TEST DRIVE - 1HR','2014-06-11 19:26:36'),('IDL00000003','TIME-IN','2014-07-26 05:51:44'),('IDL00000004','TIEM-OUT','2014-07-26 05:51:55'),('IDL00000005','LUNCH TIME','2014-07-26 05:53:06');

/*Table structure for table `tbl_job` */

DROP TABLE IF EXISTS `tbl_job`;

CREATE TABLE `tbl_job` (
  `job_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `job` varchar(30) COLLATE ascii_bin NOT NULL,
  `wocat_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `stdhr` int(10) NOT NULL,
  `stdrate` decimal(30,2) NOT NULL,
  `job_created` datetime NOT NULL,
  `flagrate` decimal(30,2) DEFAULT NULL,
  PRIMARY KEY (`job_id`),
  UNIQUE KEY `job_id` (`job_id`,`job`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_job` */

insert  into `tbl_job`(`job_id`,`job`,`wocat_id`,`stdhr`,`stdrate`,`job_created`,`flagrate`) values ('JOB00000001','AIRCON REPAIR','WOC00000002',10,'450.00','2014-06-11 20:00:02','100.00');

/*Table structure for table `tbl_jobclock_checkin_checkout` */

DROP TABLE IF EXISTS `tbl_jobclock_checkin_checkout`;

CREATE TABLE `tbl_jobclock_checkin_checkout` (
  `wo_refno` varchar(20) DEFAULT NULL,
  `check_date` datetime DEFAULT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_jobclock_checkin_checkout` */

insert  into `tbl_jobclock_checkin_checkout`(`wo_refno`,`check_date`,`check_in`,`check_out`) values ('WO00000001','2014-12-07 22:56:00','2014-12-07 22:56:00','2014-12-07 22:57:00'),('WO00000002','2014-12-07 23:14:00','2014-12-07 23:14:00','2014-12-07 23:14:00'),('WO00000003','2014-12-12 11:16:00','2014-12-12 11:16:00','2014-12-12 11:17:00'),('WO00000004','2014-12-17 18:01:00','2014-12-17 18:11:02','2014-12-17 18:11:00'),('WO00000005','2014-12-17 18:11:00','2014-12-17 18:11:00','2014-12-17 18:11:00'),('WO00000006','2014-12-17 18:11:00','2014-12-17 18:11:00','2014-12-17 18:11:00'),('WO00000007','2015-03-03 23:48:00','2015-03-03 23:48:00','2015-03-03 23:49:00'),('WO00000010','2015-03-18 05:47:00','2015-03-18 05:47:00','2015-03-18 15:14:00'),('WO00000011','2015-03-18 15:43:00','2015-03-18 15:43:00','2015-03-18 15:43:00'),('WO00000012','2015-03-18 15:58:00','2015-03-18 15:58:45','2015-03-18 15:59:00'),('WO00000017','2015-06-09 09:58:00','2015-06-09 09:58:00','2015-06-09 09:58:00'),('WO00000018','2015-06-09 10:01:00','2015-06-09 10:01:00','2015-06-09 10:01:00'),('WO00000016','2015-06-15 15:33:00','2015-06-15 15:33:00','2015-08-03 15:27:00'),('WO00000013','2015-09-10 00:17:00','2015-09-10 00:17:00','2015-09-10 00:20:20');

/*Table structure for table `tbl_jobclock_detail` */

DROP TABLE IF EXISTS `tbl_jobclock_detail`;

CREATE TABLE `tbl_jobclock_detail` (
  `wo_refno` varchar(20) DEFAULT NULL,
  `seqno` int(11) DEFAULT NULL,
  `idle_id` varchar(20) DEFAULT NULL,
  `time_start` timestamp NULL DEFAULT NULL,
  `time_end` timestamp NULL DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_jobclock_detail` */

insert  into `tbl_jobclock_detail`(`wo_refno`,`seqno`,`idle_id`,`time_start`,`time_end`,`remarks`) values ('WO00000017',1,'IDL00000001','2014-12-17 00:00:59','2014-12-17 00:30:59',''),('WO00000017',1,'IDL00000001','2015-03-03 00:00:18','2015-03-03 00:30:18','test'),('WO00000018',1,'IDL00000001','2015-03-18 00:30:56','2015-03-18 01:00:56','test');

/*Table structure for table `tbl_jobclock_master` */

DROP TABLE IF EXISTS `tbl_jobclock_master`;

CREATE TABLE `tbl_jobclock_master` (
  `wo_refno` varchar(20) NOT NULL,
  `job_start` datetime DEFAULT NULL,
  `job_end` datetime DEFAULT NULL,
  `std_working_hrs` varchar(20) DEFAULT NULL,
  `total_idle_hrs` varchar(20) DEFAULT NULL,
  `total_working_hrs` varchar(20) DEFAULT NULL,
  `actual_working_hrs` varchar(20) DEFAULT NULL,
  `variance` varchar(20) DEFAULT NULL,
  `job_status` enum('0','1','2') DEFAULT '1',
  PRIMARY KEY (`wo_refno`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_jobclock_master` */

insert  into `tbl_jobclock_master`(`wo_refno`,`job_start`,`job_end`,`std_working_hrs`,`total_idle_hrs`,`total_working_hrs`,`actual_working_hrs`,`variance`,`job_status`) values ('WO00000001','2014-12-07 22:56:58','2014-12-07 22:57:33','',NULL,NULL,NULL,NULL,'2'),('WO00000002','2014-12-07 23:14:09','2014-12-07 23:14:12','',NULL,NULL,NULL,NULL,'2'),('WO00000003','2014-12-12 11:16:55','2014-12-12 11:17:11','10',NULL,NULL,NULL,NULL,'2'),('WO00000004','2014-12-17 18:01:43','2014-12-17 18:11:27','',NULL,NULL,NULL,NULL,'2'),('WO00000005','2014-12-17 18:11:41','2014-12-17 18:11:43','',NULL,NULL,NULL,NULL,'2'),('WO00000006','2014-12-17 18:11:52','2014-12-17 18:11:53','',NULL,NULL,NULL,NULL,'2'),('WO00000007','2015-03-03 23:48:54','2015-03-03 23:49:25','10',NULL,NULL,NULL,NULL,'2'),('WO00000010','2015-03-18 05:47:03','2015-03-18 15:14:30','10',NULL,NULL,NULL,NULL,'2'),('WO00000011','2015-03-18 15:43:58','2015-03-18 15:43:59','10',NULL,NULL,NULL,NULL,'2'),('WO00000012','2015-03-18 15:58:37','2015-03-18 15:59:05','10',NULL,NULL,NULL,NULL,'2'),('WO00000017','2015-06-08 08:20:52','2015-06-09 09:58:54','10',NULL,NULL,NULL,NULL,'2'),('WO00000018','2015-06-08 10:01:40','2015-06-09 10:01:41','',NULL,NULL,NULL,NULL,'2'),('WO00000016','2015-07-15 15:33:41','2015-08-03 15:27:16','10',NULL,NULL,NULL,NULL,'2'),('WO00000013','2015-09-10 00:17:59',NULL,'10',NULL,NULL,NULL,NULL,'1');

/*Table structure for table `tbl_make` */

DROP TABLE IF EXISTS `tbl_make`;

CREATE TABLE `tbl_make` (
  `make_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `make` varchar(20) COLLATE ascii_bin NOT NULL,
  `make_rate` decimal(20,2) NOT NULL,
  `make_created` datetime NOT NULL,
  PRIMARY KEY (`make_id`),
  UNIQUE KEY `make_id` (`make_id`,`make`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_make` */

insert  into `tbl_make`(`make_id`,`make`,`make_rate`,`make_created`) values ('MAK00000001','HYUNDAI','600.00','2014-06-11 19:14:57'),('MAK00000002','TOYOTA','555.00','2014-06-11 19:15:04'),('MAK00000003','MITSUBISHI','550.00','2014-06-11 19:15:12');

/*Table structure for table `tbl_material` */

DROP TABLE IF EXISTS `tbl_material`;

CREATE TABLE `tbl_material` (
  `material_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `material` varchar(60) COLLATE ascii_bin NOT NULL,
  `material_disc` decimal(20,2) NOT NULL,
  `material_srp` decimal(20,2) NOT NULL,
  `material_lowstock` decimal(30,0) NOT NULL,
  `material_onhand` decimal(30,0) NOT NULL,
  `material_status` varchar(30) COLLATE ascii_bin NOT NULL,
  `material_created` datetime NOT NULL,
  PRIMARY KEY (`material_id`),
  UNIQUE KEY `material_id` (`material_id`,`material`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_material` */

insert  into `tbl_material`(`material_id`,`material`,`material_disc`,`material_srp`,`material_lowstock`,`material_onhand`,`material_status`,`material_created`) values ('MAT00000001','1/2 BOLTS','50.00','60.00','50','90','Active','2014-06-11 19:18:49'),('MAT00000002','1/2 NUTS','50.00','60.00','50','100','Active','2014-06-11 19:19:07');

/*Table structure for table `tbl_menus` */

DROP TABLE IF EXISTS `tbl_menus`;

CREATE TABLE `tbl_menus` (
  `id` varchar(100) NOT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `is_parent` enum('0','1') DEFAULT '0',
  `menu_line` longtext,
  `status` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_menus` */

insert  into `tbl_menus`(`id`,`menu`,`is_parent`,`menu_line`,`status`) values ('estimate_add','Estimate Add','1',NULL,'1'),('estimate_for_approval','Estimate For Approval','1',NULL,'1'),('po_receiving_main','PO Receiving','1',NULL,'1'),('workorder_main','Work Orders','1',NULL,'1'),('jobclock_main','Job Clock Main','1',NULL,'1'),('billing_main','Billing','1',NULL,'1'),('estimate_main','Estimate Main','1',NULL,'1'),('cash_billing_list','Cash Billing List','1',NULL,'1'),('card_billing_list','Card Billing List','1',NULL,'1'),('billing_list','Billing List','1',NULL,'1'),('settings_main','Settings','1',NULL,'1'),('customer_list','Customer List','1',NULL,'1'),('customer_add','Customer Add','1',NULL,'1'),('customer_edit','Customer Edit','1',NULL,'1'),('customer_delete','Customer Delete','1',NULL,'1'),('vehicle_list','Vehicle List','1',NULL,'1'),('vehicle_add','Vehicle Add','1',NULL,'1'),('vehicle_edit','Vehicle Edit','1',NULL,'1'),('vehicle_delete','Vehicle Delete','1',NULL,'1'),('employee_list','Employee List','1',NULL,'1'),('employee_add','Employee Add','1',NULL,'1'),('employee_edit','Employee Edit','1',NULL,'1'),('employee_delete','Employee Delete','1',NULL,'1'),('color_list','Color List','1',NULL,'1'),('color_add','Color Add','1',NULL,'1'),('color_edit','Color Edit','1',NULL,'1'),('color_delete','Color Delete','1',NULL,'1'),('make_list','Make List','1',NULL,'1'),('make_add','Make Add','1',NULL,'1'),('make_edit','Make Edit','1',NULL,'1'),('make_delete','Make Delete','1',NULL,'1'),('model_list','Model List','1',NULL,'1'),('model_add','Model Add','1',NULL,'1'),('model_edit','Model Edit','1',NULL,'1'),('model_delete','Model Delete','1',NULL,'1'),('year_list','Year List','1',NULL,'1'),('year_add','Year Add','1',NULL,'1'),('year_edit','Year Edit','1',NULL,'1'),('year_delete','Year Delete','1',NULL,'1'),('parts_list','Parts List','1',NULL,'1'),('parts_add','Parts Add','1',NULL,'1'),('parts_edit','Parts Edit','1',NULL,'1'),('parts_delete','Parts Delete','1',NULL,'1'),('material_list','Material List','1',NULL,'1'),('material_add','Material Add','1',NULL,'1'),('material_edit','Material Edit','1',NULL,'1'),('material_delete','Material Delete','1',NULL,'1'),('accessory_list','Accessory List','1',NULL,'1'),('accessory_add','Accessory Add','1',NULL,'1'),('accessory_edit','Accessory Edit','1',NULL,'1'),('accessory_delete','Accessory Delete','1',NULL,'1'),('wocategory_list','Work Order Category List','1',NULL,'1'),('wocategory_add','Work Order Category Add','1',NULL,'1'),('wocategory_edit','Work Order Category Edit','1',NULL,'1'),('wocategory_delete','Work ORder Category Delete','1',NULL,'1'),('job_list','Job List','1',NULL,'1'),('job_add','Job Add','1',NULL,'1'),('job_edit','Job Edit','1',NULL,'1'),('job_delete','Job Delete','1',NULL,'1'),('idle_list','Idle List','1',NULL,'1'),('idle_add','Idle Add','1',NULL,'1'),('idle_edit','Idle Edit','1',NULL,'1'),('idle_delete','Idle Delete','1',NULL,'1'),('payment_list','Payment List','1',NULL,'1'),('payment_add','Payment Add','1',NULL,'1'),('payment_edit','Payment Edit','1',NULL,'1'),('payment_delete','Payment Delete','1',NULL,'1'),('users_list','Users List','1',NULL,'1'),('user_add','User Add','1',NULL,'1'),('user_edit','User Edit','1',NULL,'1'),('user_delete','User Delete','1',NULL,'1'),('user_access','User Access','1',NULL,'1'),('user_menu_add','User Menu Add','1',NULL,'1'),('user_menu_delete','User Menu Delete','1',NULL,'1'),('estimate_report','Estimate Report','1',NULL,'1'),('work_order_report','Work Order Report','1',NULL,'1'),('work_order_summary_report','Work Order Summary Report','1',NULL,'1'),('purchase_order_report','Purchase Order Report','1',NULL,'1'),('jobclock_report','Job Clock Report','1',NULL,'1'),('technician_performance_report','Technician Performance Report','1',NULL,'1'),('idle_time_report','Idle Time Report','1',NULL,'1'),('payment_summary_report','Payment Summary Report','1',NULL,'1'),('customer_payment_report','Customer Payment Report','1',NULL,'1'),('sales_per_customer_report','Sales Per Customer Report','1',NULL,'1'),('sales_per_paymode_report','Sales Per Paymode Report','1',NULL,'1'),('sales_summary_report','Sales Summary Report','1',NULL,'1'),('po_receiving_add','Purchase Order Receiving Add','1',NULL,'1'),('workorder_approval','Work Order Approval','1',NULL,'1'),('jobclock','Job Clock','1',NULL,'1'),('card_billing','Card Billing List','1',NULL,'1'),('cash_billing','Cash Billing List','1',NULL,'1'),('configuration','Configuration','1',NULL,'1');

/*Table structure for table `tbl_model` */

DROP TABLE IF EXISTS `tbl_model`;

CREATE TABLE `tbl_model` (
  `model_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `model` varchar(30) COLLATE ascii_bin NOT NULL,
  `variant` varchar(50) COLLATE ascii_bin NOT NULL,
  `variantdesc` varchar(100) COLLATE ascii_bin NOT NULL,
  `model_created` datetime NOT NULL,
  PRIMARY KEY (`model_id`),
  UNIQUE KEY `model_id` (`model_id`,`model`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_model` */

insert  into `tbl_model`(`model_id`,`model`,`variant`,`variantdesc`,`model_created`) values ('MDL00000001','TUCSON THETA II','Tucson Theta II Diesel CRDI','Tucson CRDI 1.5','2014-06-11 19:15:56'),('MDL00000002','SANTA FE BLUE','Santa Fe Blue Gasoline','Santa Fe V6.2','2014-06-11 19:16:44');

/*Table structure for table `tbl_online_estimate_detail` */

DROP TABLE IF EXISTS `tbl_online_estimate_detail`;

CREATE TABLE `tbl_online_estimate_detail` (
  `oe_id` varchar(20) NOT NULL,
  `oed_id` int(20) NOT NULL AUTO_INCREMENT,
  `id` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT NULL,
  `qty` int(12) DEFAULT '1',
  PRIMARY KEY (`oe_id`,`oed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_online_estimate_detail` */

insert  into `tbl_online_estimate_detail`(`oe_id`,`oed_id`,`id`,`type`,`amount`,`qty`) values ('OE00000001',1,'JOB00000001','job','450.00',1),('OE00000002',1,'JOB00000001','job','450.00',1),('OE00000003',1,'PAR00000001','parts','300.00',1),('OE00000003',2,'JOB00000001','job','450.00',1),('OE00000003',3,'MAT00000001','material','60.00',1),('OE00000003',4,'ACC00000001','accessory','2000.00',1),('OE00000013',1,'JOB00000001','job','450.00',1),('OE00000004',1,'PAR00000001','parts','300.00',1),('OE00000005',1,'MAT00000001','material','60.00',1),('OE00000006',1,'MAT00000001','material','60.00',1),('OE00000006',2,'JOB00000001','job','450.00',1),('OE00000007',1,'ACC00000003','accessory','300.00',1),('OE00000007',2,'JOB00000001','job','450.00',1),('OE00000008',1,'ACC00000003','accessory','300.00',1),('OE00000008',2,'JOB00000001','job','450.00',1);

/*Table structure for table `tbl_online_estimate_master` */

DROP TABLE IF EXISTS `tbl_online_estimate_master`;

CREATE TABLE `tbl_online_estimate_master` (
  `oe_id` varchar(20) NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `customer` varchar(20) DEFAULT NULL,
  `address` longtext,
  `contactno` varchar(50) DEFAULT NULL,
  `emailaddress` varchar(100) DEFAULT NULL,
  `plateno` varchar(20) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `make` varchar(20) DEFAULT NULL,
  `model` varchar(20) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `variant` varchar(100) DEFAULT NULL,
  `engineno` varchar(20) DEFAULT NULL,
  `chassisno` varchar(20) DEFAULT NULL,
  `serialno` varchar(20) DEFAULT NULL,
  `remarks` longtext,
  `recommendation` longtext,
  `status` enum('0','1','2') DEFAULT '0',
  `payment_id` varchar(20) DEFAULT NULL,
  `subtotal_amount` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `discounted_price` decimal(10,2) DEFAULT NULL,
  `vat` varchar(5) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`oe_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_online_estimate_master` */

insert  into `tbl_online_estimate_master`(`oe_id`,`transaction_date`,`customer`,`address`,`contactno`,`emailaddress`,`plateno`,`year`,`make`,`model`,`color`,`variant`,`engineno`,`chassisno`,`serialno`,`remarks`,`recommendation`,`status`,`payment_id`,`subtotal_amount`,`discount`,`discounted_price`,`vat`,`total_amount`) values ('OE00000002','2015-06-08 09:21:24','aa','aa',NULL,NULL,'aa','aa','aa','aa','aa','aa','aa','aa','aa','','','1','PAY00000002','450.00','100.00','350.00','12%','392.00'),('OE00000003','2015-06-09 17:26:57','bb','bb',NULL,NULL,'bb','bb','bb','bb','bb','bb','bb','bb','bb','','','1','PAY00000002','2810.00','10.00','2800.00','12%','3136.00'),('OE00000006','2015-06-15 14:06:02','d','d',NULL,NULL,'d','d','d','d','d','d','d','d','d','','','0','PAY00000003','510.00','50.00','460.00','12%','515.20'),('OE00000005','2015-06-15 14:03:11','c','c',NULL,NULL,'c','c','c','c','c','c','c','c','c','','','0','PAY00000001','60.00','10.00','50.00','12%','56.00'),('OE00000004','2015-06-15 14:02:21','b','b',NULL,NULL,'b','b','b','b','b','b','b','b','b','','','0','PAY00000001','300.00','100.00','200.00','12%','224.00'),('OE00000007','2015-09-21 08:57:07','','',NULL,NULL,'','','','','','','','','','','','0','','750.00','0.00','0.00','12%','840.00'),('OE00000008','2015-09-22 08:00:36','a','a','a','a','a','a','a','a','a','a','a','a','a','','','0','','750.00','0.00','0.00','12%','840.00');

/*Table structure for table `tbl_package_detail` */

DROP TABLE IF EXISTS `tbl_package_detail`;

CREATE TABLE `tbl_package_detail` (
  `package_id` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `id` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_package_detail` */

insert  into `tbl_package_detail`(`package_id`,`type`,`id`) values ('PAC00000003','accessory','ACC00000003'),('PAC00000003','job','JOB00000001');

/*Table structure for table `tbl_package_master` */

DROP TABLE IF EXISTS `tbl_package_master`;

CREATE TABLE `tbl_package_master` (
  `package_id` varchar(20) NOT NULL,
  `package_name` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `created_by` varchar(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(20) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`package_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_package_master` */

insert  into `tbl_package_master`(`package_id`,`package_name`,`status`,`created_by`,`created_date`,`modified_by`,`modified_date`) values ('PAC00000003','package1',1,'alladinx','2015-06-24 17:35:43',NULL,NULL);

/*Table structure for table `tbl_parts` */

DROP TABLE IF EXISTS `tbl_parts`;

CREATE TABLE `tbl_parts` (
  `parts_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `parts` varchar(60) COLLATE ascii_bin NOT NULL,
  `parts_discount` decimal(20,2) NOT NULL,
  `part_srp` decimal(20,2) NOT NULL,
  `parts_lowstock` decimal(30,0) NOT NULL,
  `part_onhand` decimal(10,0) NOT NULL,
  `partstatus` varchar(30) COLLATE ascii_bin NOT NULL,
  `part_created` datetime NOT NULL,
  `parts_old_price` decimal(20,2) DEFAULT NULL,
  `old_price_date` datetime DEFAULT NULL,
  `new_price_date` datetime DEFAULT NULL,
  PRIMARY KEY (`parts_id`),
  UNIQUE KEY `parts_id` (`parts_id`,`parts`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_parts` */

insert  into `tbl_parts`(`parts_id`,`parts`,`parts_discount`,`part_srp`,`parts_lowstock`,`part_onhand`,`partstatus`,`part_created`,`parts_old_price`,`old_price_date`,`new_price_date`) values ('PAR00000001','SPARK PLUG','250.00','300.00','10','20','Active','2014-06-11 19:17:35',NULL,NULL,NULL),('PAR00000002','HEAD LAMP','200.00','340.00','10','9','Active','2014-06-11 19:18:14','320.00','2015-09-10 10:49:43','2015-09-10 10:51:20');

/*Table structure for table `tbl_payment` */

DROP TABLE IF EXISTS `tbl_payment`;

CREATE TABLE `tbl_payment` (
  `payment_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `payment` varchar(60) COLLATE ascii_bin NOT NULL,
  `payment_created` datetime NOT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `payment_id` (`payment_id`,`payment`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_payment` */

insert  into `tbl_payment`(`payment_id`,`payment`,`payment_created`) values ('PAY00000001','CHARGE','2014-06-11 19:26:56'),('PAY00000002','WARRANTY','2014-06-11 19:27:05'),('PAY00000003','SALES - CASH','2014-06-11 19:27:14'),('PAY00000004','SALES - CREDIT CARD','2014-06-18 02:10:41');

/*Table structure for table `tbl_po_detail` */

DROP TABLE IF EXISTS `tbl_po_detail`;

CREATE TABLE `tbl_po_detail` (
  `po_refno` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT NULL,
  `qty` int(12) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_po_detail` */

/*Table structure for table `tbl_po_master` */

DROP TABLE IF EXISTS `tbl_po_master`;

CREATE TABLE `tbl_po_master` (
  `estimate_refno` varchar(20) DEFAULT '0',
  `wo_refno` varchar(20) DEFAULT '0',
  `po_refno` varchar(20) NOT NULL,
  `attachment` longtext,
  `transaction_date` datetime DEFAULT NULL,
  `payment_id` varchar(20) DEFAULT NULL,
  `subtotal_amount` decimal(20,2) DEFAULT NULL,
  `discount` decimal(20,2) DEFAULT NULL,
  `discounted_price` decimal(20,2) DEFAULT NULL,
  `vat` int(3) DEFAULT NULL,
  `total_amount` decimal(20,2) DEFAULT NULL,
  `remarks` longtext,
  `created_by` varchar(20) DEFAULT NULL,
  `trans_status` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`po_refno`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_po_master` */

/*Table structure for table `tbl_service_detail` */

DROP TABLE IF EXISTS `tbl_service_detail`;

CREATE TABLE `tbl_service_detail` (
  `estimate_refno` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `id` varchar(20) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT NULL,
  `qty` int(12) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_service_detail` */

insert  into `tbl_service_detail`(`estimate_refno`,`type`,`id`,`amount`,`qty`) values ('ER00000001','make','MAK00000001','600.00',1),('ER00000002','make','MAK00000002','555.00',1),('ER00000003','job','JOB00000001','450.00',1),('ER00000003','make','MAK00000003','550.00',1),('ER00000004','make','MAK00000001','600.00',1),('ER00000005','make','MAK00000002','555.00',1),('ER00000006','make','MAK00000003','550.00',1),('ER00000007','accessory','ACC00000002','1500.00',1),('ER00000007','job','JOB00000001','450.00',1),('ER00000008','material','MAT00000001','60.00',1),('ER00000008','accessory','ACC00000001','2000.00',1),('ER00000008','job','JOB00000001','450.00',1),('ER00000008','parts','PAR00000001','300.00',1),('ER00000009','job','JOB00000001','450.00',1),('ER00000009','accessory','ACC00000002','1500.00',1),('ER00000010','job','JOB00000001','450.00',1),('ER00000011','job','JOB00000001','450.00',1),('ER00000011','parts','PAR00000001','300.00',1),('ER00000011','material','MAT00000001','120.00',2),('ER00000011','accessory','ACC00000002','1500.00',1),('ER00000011','parts','PAR00000002','350.00',1),('ER00000013','job','JOB00000001','450.00',0),('ER00000013','parts','PAR00000001','300.00',0),('ER00000013','material','MAT00000001','120.00',0),('ER00000013','accessory','ACC00000003','600.00',0),('ER00000014','accessory','ACC00000001','2000.00',0),('ER00000014','material','MAT00000001','60.00',0),('ER00000014','parts','PAR00000001','300.00',0),('ER00000014','job','JOB00000001','450.00',0),('ER00000015','parts','PAR00000001','300.00',1),('ER00000015','job','JOB00000001','450.00',1),('ER00000016','job','JOB00000001','450.00',1),('ER00000016','accessory','ACC00000001','2000.00',1),('ER00000017','material','MAT00000001','60.00',1),('ER00000017','job','JOB00000001','450.00',1),('ER00000018','job','JOB00000001','450.00',1),('ER00000019','job','JOB00000001','450.00',1),('ER00000020','job','JOB00000001','450.00',1),('ER00000021','parts','PAR00000001','300.00',1),('ER00000021','job','JOB00000001','450.00',1),('ER00000022','job','JOB00000001','450.00',1),('ER00000022','accessory','ACC00000001','2000.00',1),('ER00000023','parts','PAR00000001','300.00',1),('','parts','PAR00000001','300.00',1),('ER00000028','accessory','ACC00000003','600.00',2),('','accessory','ACC00000001','2000.00',1),('','job','JOB00000001','450.00',1),('ER00000028','parts','PAR00000001','600.00',2),('ER00000028','job','JOB00000001','900.00',2),('ER00000029','parts','PAR00000002','350.00',1);

/*Table structure for table `tbl_service_master` */

DROP TABLE IF EXISTS `tbl_service_master`;

CREATE TABLE `tbl_service_master` (
  `estimate_refno` varchar(20) NOT NULL,
  `wo_refno` varchar(20) DEFAULT '0',
  `wo_trans_date` datetime DEFAULT NULL,
  `po_refno` varchar(20) DEFAULT '0',
  `transaction_date` datetime DEFAULT NULL,
  `customer_id` varchar(20) DEFAULT NULL,
  `vehicle_id` varchar(20) DEFAULT NULL,
  `odometer` decimal(10,0) DEFAULT NULL,
  `payment_id` varchar(20) DEFAULT NULL,
  `subtotal_amount` decimal(20,2) DEFAULT NULL,
  `discount` decimal(20,2) DEFAULT NULL,
  `discounted_price` decimal(20,2) DEFAULT NULL,
  `vat` int(3) DEFAULT NULL,
  `total_amount` decimal(20,2) DEFAULT NULL,
  `recommendation` longtext,
  `remarks` longtext,
  `created_by` varchar(20) DEFAULT NULL,
  `trans_status` enum('0','1','2','3','4','5','6','7','8','9','10') DEFAULT '0',
  `technician` varchar(20) DEFAULT NULL,
  `promise_date` date DEFAULT NULL,
  `promise_time` time DEFAULT NULL,
  PRIMARY KEY (`estimate_refno`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_service_master` */

insert  into `tbl_service_master`(`estimate_refno`,`wo_refno`,`wo_trans_date`,`po_refno`,`transaction_date`,`customer_id`,`vehicle_id`,`odometer`,`payment_id`,`subtotal_amount`,`discount`,`discounted_price`,`vat`,`total_amount`,`recommendation`,`remarks`,`created_by`,`trans_status`,`technician`,`promise_date`,`promise_time`) values ('ER00000016','WO00000011','2015-03-19 02:08:58','0','2015-03-18 15:20:16','CUST00000001','VEH00000004','436','PAY00000001','2450.00','0.00','0.00',12,'2744.00','','test','admin','7','EMP00000002',NULL,'00:00:06'),('ER00000017','WO00000012',NULL,'0','2015-03-18 15:25:06','CUST00000001','VEH00000001','546','PAY00000001','510.00','0.00','0.00',12,'571.20','testasdfasdfas dfasdfasdfasd fasdfasdfas dfasdfasdfas dfasdfas dfasdf','testasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdf','admin','7','EMP00000001',NULL,'00:00:05'),('ER00000015','WO00000010',NULL,'0','2015-03-12 23:46:32','CUST00000001','VEH00000001','1234','PAY00000003','750.00','0.00','0.00',12,'840.00','test','test','admin','7','EMP00000002',NULL,'00:00:10'),('ER00000018','WO00000013',NULL,'0','2015-03-19 14:03:25','CUST00000001','VEH00000001','23','PAY00000001','450.00','0.00','0.00',12,'504.00','','tset','admin','8','EMP00000001','2015-09-15','12:00:00'),('ER00000019','WO00000014',NULL,'0','2015-03-19 14:05:31','CUST00000001','VEH00000004','213524','PAY00000001','450.00','0.00','0.00',12,'504.00','','test','admin','4',NULL,NULL,NULL),('ER00000020','WO00000015','2015-03-19 02:08:58','0','2015-03-19 14:07:31','CUST00000001','VEH00000004','6578','PAY00000001','450.00','0.00','0.00',12,'504.00','','test','admin','4',NULL,NULL,NULL),('ER00000021','WO00000016','2015-05-10 09:24:07','0','2015-05-10 21:22:00','CUST00000001','VEH00000001','123','PAY00000001','750.00','100.00','650.00',12,'728.00','asdf','asdf','admin','6','EMP00000002','2015-06-05','00:00:12'),('ER00000022','WO00000017','2015-06-09 09:57:48','0','2015-06-09 09:57:27','CUST00000001','VEH00000001','123','PAY00000001','2450.00','100.00','2350.00',12,'2632.00','','test','admin','7','EMP00000002','2015-06-10','10:00:00'),('ER00000023','WO00000018','2015-06-09 10:01:10','0','2015-06-09 10:00:52','CUST00000001','VEH00000001','123','PAY00000001','300.00','100.00','200.00',12,'224.00','test','test','admin','7','EMP00000002','2015-06-06','00:00:11'),('ER00000029','WO00000019','2015-08-28 05:25:58','0','2015-08-28 17:24:43','CUST00000001','VEH00000001','123','','350.00','100.00','250.00',12,'280.00','test','test','alladinx','4',NULL,NULL,NULL),('ER00000028','0',NULL,'0','2015-07-28 14:45:28','CUST00000001','VEH00000001','34','','2100.00','100.00','2000.00',12,'2240.00','test','test','alladinx','0',NULL,NULL,NULL);

/*Table structure for table `tbl_temp_estimate` */

DROP TABLE IF EXISTS `tbl_temp_estimate`;

CREATE TABLE `tbl_temp_estimate` (
  `ses_id` longtext,
  `estimate_id` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `id` varchar(20) DEFAULT NULL,
  `rate` decimal(20,2) DEFAULT NULL,
  `qty` int(12) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_temp_estimate` */

insert  into `tbl_temp_estimate`(`ses_id`,`estimate_id`,`type`,`id`,`rate`,`qty`) values ('c7hmo2lcc6nrjn5d28q2jjls34','91357468','job','JOB00000001','900.00',2);

/*Table structure for table `tbl_temp_po_detail` */

DROP TABLE IF EXISTS `tbl_temp_po_detail`;

CREATE TABLE `tbl_temp_po_detail` (
  `ses_id` longtext,
  `type` varchar(20) DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT NULL,
  `qty` int(12) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_temp_po_detail` */

/*Table structure for table `tbl_user_access` */

DROP TABLE IF EXISTS `tbl_user_access`;

CREATE TABLE `tbl_user_access` (
  `menu_id` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_user_access` */

insert  into `tbl_user_access`(`menu_id`,`user_id`) values ('users_list','alladinx'),('user_add','alladinx'),('user_access','alladinx'),('user_edit','alladinx'),('user_delete','alladinx'),('user_menu_add','alladinx'),('user_menu_delete','alladinx'),('accessory_add','alladinx'),('settings_main','alladinx'),('user_access','noel'),('user_menu_add','noel'),('user_menu_delete','noel'),('workorder_approval','alladinx'),('estimate_add','alladinx'),('estimate_for_approval','alladinx'),('jobclock','alladinx'),('po_receiving_add','alladinx'),('card_billing','alladinx'),('cash_billing','alladinx'),('accessory_delete','alladinx'),('accessory_edit','alladinx'),('accessory_list','alladinx'),('configuration','alladinx');

/*Table structure for table `tbl_users` */

DROP TABLE IF EXISTS `tbl_users`;

CREATE TABLE `tbl_users` (
  `username` varchar(20) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image` text,
  `user_created` datetime DEFAULT NULL,
  `user_status` enum('0','1') DEFAULT '1',
  `user_access` enum('1','2') DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_users` */

insert  into `tbl_users`(`username`,`password`,`name`,`image`,`user_created`,`user_status`,`user_access`) values ('alladinx','d5965620eefcdea938bdca34b65d8dcf ','REY CASTANARES','alladinx.png','2014-08-03 03:53:29','1','1'),('noel','2ecac8a146503bdca59645d716d3e02e','NOEL RODRIGUEZ','noel.jpg','2014-08-08 06:24:04','1','1');

/*Table structure for table `tbl_vehicleinfo` */

DROP TABLE IF EXISTS `tbl_vehicleinfo`;

CREATE TABLE `tbl_vehicleinfo` (
  `vehicle_id` varchar(30) NOT NULL,
  `customer_id` varchar(20) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `plate_no` varchar(60) NOT NULL,
  `conduction_sticker` varchar(20) DEFAULT NULL,
  `make` varchar(60) NOT NULL,
  `year` varchar(60) NOT NULL,
  `model` varchar(60) NOT NULL,
  `color` varchar(60) NOT NULL,
  `variant` varchar(60) NOT NULL,
  `description` varchar(100) NOT NULL,
  `engine_no` varchar(60) NOT NULL,
  `chassis_no` varchar(60) NOT NULL,
  `serial_no` varchar(60) NOT NULL,
  PRIMARY KEY (`vehicle_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tbl_vehicleinfo` */

insert  into `tbl_vehicleinfo`(`vehicle_id`,`customer_id`,`customer_name`,`address`,`plate_no`,`conduction_sticker`,`make`,`year`,`model`,`color`,`variant`,`description`,`engine_no`,`chassis_no`,`serial_no`) values ('VEH00000001','CUST00000001','','Tambo','123-asd','','MAK00000001','YR00000001','MDL00000002','COL00000003','Santa Fe Blue Gasoline','','123456','1234578','123456789'),('VEH00000004','CUST00000001','','Tambo','7T-132456','7T-132456','MAK00000003','YR00000014','MDL00000001','COL00000003','Tucson Theta II Diesel CRDI','','123456798','132456789','132465789'),('VEH00000010','CUST00000013','','a a a','asdf','asdf','MAK00000001','YR00000014','MDL00000002','COL00000003','Santa Fe Blue Gasoline','','asdf','asdf','asdf');

/*Table structure for table `tbl_wocat` */

DROP TABLE IF EXISTS `tbl_wocat`;

CREATE TABLE `tbl_wocat` (
  `wocat_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `wocat` varchar(100) COLLATE ascii_bin NOT NULL,
  `wocat_created` datetime NOT NULL,
  PRIMARY KEY (`wocat_id`),
  UNIQUE KEY `wocat_id` (`wocat_id`,`wocat`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_wocat` */

insert  into `tbl_wocat`(`wocat_id`,`wocat`,`wocat_created`) values ('WOC00000001','GENERAL','2014-06-11 19:25:21'),('WOC00000002','SCHEDULED PM','2014-06-11 19:25:40'),('WOC00000003','BODY REPAIRS','2014-06-11 19:25:53');

/*Table structure for table `tbl_year` */

DROP TABLE IF EXISTS `tbl_year`;

CREATE TABLE `tbl_year` (
  `year_id` varchar(20) COLLATE ascii_bin NOT NULL,
  `year` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`year_id`),
  UNIQUE KEY `year_id` (`year_id`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

/*Data for the table `tbl_year` */

insert  into `tbl_year`(`year_id`,`year`,`created`) values ('YR00000001',2001,'2014-06-11 19:09:46'),('YR00000002',2002,'2014-06-11 19:09:50'),('YR00000003',2003,'2014-06-18 00:33:04'),('YR00000004',2004,'2014-06-08 00:33:50'),('YR00000005',2005,'0000-00-00 00:00:00'),('YR00000006',2006,'0000-00-00 00:00:00'),('YR00000007',2007,'0000-00-00 00:00:00'),('YR00000008',2008,'0000-00-00 00:00:00'),('YR00000009',2009,'0000-00-00 00:00:00'),('YR00000010',2010,'0000-00-00 00:00:00'),('YR00000011',2011,'0000-00-00 00:00:00'),('YR00000012',2012,'0000-00-00 00:00:00'),('YR00000013',2013,'0000-00-00 00:00:00'),('YR00000014',2014,'0000-00-00 00:00:00'),('YR00000015',2015,'0000-00-00 00:00:00');

/*Table structure for table `v_accessory` */

DROP TABLE IF EXISTS `v_accessory`;

/*!50001 DROP VIEW IF EXISTS `v_accessory` */;
/*!50001 DROP TABLE IF EXISTS `v_accessory` */;

/*!50001 CREATE TABLE  `v_accessory`(
 `accessory_id` varchar(20) ,
 `accessory` varchar(60) ,
 `access_disc` decimal(20,2) ,
 `access_srp` decimal(20,2) ,
 `access_low` double(10,0) ,
 `access_onhand` decimal(10,0) ,
 `access_status` varchar(10) ,
 `access_created` datetime 
)*/;

/*Table structure for table `v_billing` */

DROP TABLE IF EXISTS `v_billing`;

/*!50001 DROP VIEW IF EXISTS `v_billing` */;
/*!50001 DROP TABLE IF EXISTS `v_billing` */;

/*!50001 CREATE TABLE  `v_billing`(
 `wo_refno` varchar(20) ,
 `wo_trans_date` datetime ,
 `billing_refno` varchar(20) ,
 `billing_date` datetime ,
 `customername` varchar(182) ,
 `total_amount` decimal(20,2) ,
 `billing_status` enum('0','1') 
)*/;

/*Table structure for table `v_billing_detail` */

DROP TABLE IF EXISTS `v_billing_detail`;

/*!50001 DROP VIEW IF EXISTS `v_billing_detail` */;
/*!50001 DROP TABLE IF EXISTS `v_billing_detail` */;

/*!50001 CREATE TABLE  `v_billing_detail`(
 `wo_refno` varchar(20) ,
 `billing_refno` varchar(20) ,
 `billing_date` datetime ,
 `total_amount` decimal(20,2) ,
 `billing_status` enum('0','1') ,
 `estimate_refno` varchar(20) ,
 `payment_id` varchar(20) ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `vehicle_id` varchar(20) ,
 `plate_no` varchar(60) 
)*/;

/*Table structure for table `v_billing_master` */

DROP TABLE IF EXISTS `v_billing_master`;

/*!50001 DROP VIEW IF EXISTS `v_billing_master` */;
/*!50001 DROP TABLE IF EXISTS `v_billing_master` */;

/*!50001 CREATE TABLE  `v_billing_master`(
 `billing_refno` varchar(20) ,
 `billing_date` datetime ,
 `cust_id` varchar(20) ,
 `cust_name` varchar(182) ,
 `cust_addr` varchar(200) ,
 `tel_no` varchar(62) ,
 `total_billing` decimal(42,2) ,
 `payment_id` varchar(20) 
)*/;

/*Table structure for table `v_card_billing` */

DROP TABLE IF EXISTS `v_card_billing`;

/*!50001 DROP VIEW IF EXISTS `v_card_billing` */;
/*!50001 DROP TABLE IF EXISTS `v_card_billing` */;

/*!50001 CREATE TABLE  `v_card_billing`(
 `cust_id` varchar(20) ,
 `custname` varchar(182) ,
 `transaction_counts` bigint(21) ,
 `total_billings` decimal(42,2) 
)*/;

/*Table structure for table `v_cash_billing` */

DROP TABLE IF EXISTS `v_cash_billing`;

/*!50001 DROP VIEW IF EXISTS `v_cash_billing` */;
/*!50001 DROP TABLE IF EXISTS `v_cash_billing` */;

/*!50001 CREATE TABLE  `v_cash_billing`(
 `cust_id` varchar(20) ,
 `custname` varchar(182) ,
 `transaction_counts` bigint(21) ,
 `total_billings` decimal(42,2) 
)*/;

/*Table structure for table `v_color` */

DROP TABLE IF EXISTS `v_color`;

/*!50001 DROP VIEW IF EXISTS `v_color` */;
/*!50001 DROP TABLE IF EXISTS `v_color` */;

/*!50001 CREATE TABLE  `v_color`(
 `color_id` varchar(20) ,
 `color` varchar(20) ,
 `color_created` datetime 
)*/;

/*Table structure for table `v_configuration` */

DROP TABLE IF EXISTS `v_configuration`;

/*!50001 DROP VIEW IF EXISTS `v_configuration` */;
/*!50001 DROP TABLE IF EXISTS `v_configuration` */;

/*!50001 CREATE TABLE  `v_configuration`(
 `id` int(11) ,
 `config_type` varchar(20) ,
 `description` varchar(100) ,
 `remarks` longtext ,
 `value` varchar(20) ,
 `status` int(11) ,
 `created_date` datetime ,
 `created_by` varchar(20) ,
 `modified_date` datetime ,
 `modified_by` varchar(20) 
)*/;

/*Table structure for table `v_controlno` */

DROP TABLE IF EXISTS `v_controlno`;

/*!50001 DROP VIEW IF EXISTS `v_controlno` */;
/*!50001 DROP TABLE IF EXISTS `v_controlno` */;

/*!50001 CREATE TABLE  `v_controlno`(
 `id` int(11) ,
 `control_type` varchar(20) ,
 `digit` int(5) ,
 `lastseqno` int(20) ,
 `control_code` varchar(20) 
)*/;

/*Table structure for table `v_customer` */

DROP TABLE IF EXISTS `v_customer`;

/*!50001 DROP VIEW IF EXISTS `v_customer` */;
/*!50001 DROP TABLE IF EXISTS `v_customer` */;

/*!50001 CREATE TABLE  `v_customer`(
 `cust_id` varchar(20) ,
 `salutation` varchar(60) ,
 `lastname` varchar(60) ,
 `firstname` varchar(60) ,
 `middlename` varchar(60) ,
 `custname` varchar(182) ,
 `address` varchar(200) ,
 `city` varchar(60) ,
 `province` varchar(60) ,
 `zipcode` bigint(10) ,
 `birthday` varchar(30) ,
 `gender` varchar(60) ,
 `tin` varchar(20) ,
 `company` varchar(100) ,
 `source` varchar(60) ,
 `email` varchar(60) ,
 `landline` varchar(20) ,
 `fax` varchar(20) ,
 `mobile` varchar(20) ,
 `cust_created` datetime 
)*/;

/*Table structure for table `v_employee` */

DROP TABLE IF EXISTS `v_employee`;

/*!50001 DROP VIEW IF EXISTS `v_employee` */;
/*!50001 DROP TABLE IF EXISTS `v_employee` */;

/*!50001 CREATE TABLE  `v_employee`(
 `emp_id` varchar(20) ,
 `employee` varchar(60) ,
 `position` varchar(50) ,
 `emp_status` enum('0','1','2') ,
 `emp_image` varchar(100) ,
 `emp_created` datetime ,
 `contactno` varchar(50) ,
 `emailaddress` varchar(100) 
)*/;

/*Table structure for table `v_employeecompensation` */

DROP TABLE IF EXISTS `v_employeecompensation`;

/*!50001 DROP VIEW IF EXISTS `v_employeecompensation` */;
/*!50001 DROP TABLE IF EXISTS `v_employeecompensation` */;

/*!50001 CREATE TABLE  `v_employeecompensation`(
 `estimate_refno` varchar(20) ,
 `workorder_refno` varchar(20) ,
 `emp_id` varchar(20) ,
 `tech_name` varchar(60) ,
 `transaction_date` datetime ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `jobname` varchar(30) ,
 `amount` decimal(30,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_estimate_cost` */

DROP TABLE IF EXISTS `v_estimate_cost`;

/*!50001 DROP VIEW IF EXISTS `v_estimate_cost` */;
/*!50001 DROP TABLE IF EXISTS `v_estimate_cost` */;

/*!50001 CREATE TABLE  `v_estimate_cost`(
 `estimate_refno` varchar(20) ,
 `wo_refno` varchar(20) ,
 `transaction_date` datetime ,
 `customer_id` varchar(20) ,
 `vehicle_id` varchar(20) ,
 `plate_no` varchar(20) ,
 `year` varchar(20) ,
 `make` varchar(20) ,
 `model` varchar(20) ,
 `color` varchar(20) ,
 `engine_no` varchar(100) ,
 `chassis_no` varchar(100) ,
 `total_amount` decimal(20,2) ,
 `trans_status` enum('0','1','2','3') 
)*/;

/*Table structure for table `v_for_billing_detail` */

DROP TABLE IF EXISTS `v_for_billing_detail`;

/*!50001 DROP VIEW IF EXISTS `v_for_billing_detail` */;
/*!50001 DROP TABLE IF EXISTS `v_for_billing_detail` */;

/*!50001 CREATE TABLE  `v_for_billing_detail`(
 `customer_id` varchar(20) ,
 `wo_refno` varchar(20) ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `plate_no` varchar(60) ,
 `total_amount` decimal(20,2) ,
 `payment_id` varchar(20) ,
 `trans_status` enum('0','1','2','3','4','5','6','7','8','9','10') 
)*/;

/*Table structure for table `v_for_billing_master` */

DROP TABLE IF EXISTS `v_for_billing_master`;

/*!50001 DROP VIEW IF EXISTS `v_for_billing_master` */;
/*!50001 DROP TABLE IF EXISTS `v_for_billing_master` */;

/*!50001 CREATE TABLE  `v_for_billing_master`(
 `cust_id` varchar(20) ,
 `custname` varchar(182) ,
 `transaction_counts` bigint(21) ,
 `total_billings` decimal(42,2) 
)*/;

/*Table structure for table `v_idle` */

DROP TABLE IF EXISTS `v_idle`;

/*!50001 DROP VIEW IF EXISTS `v_idle` */;
/*!50001 DROP TABLE IF EXISTS `v_idle` */;

/*!50001 CREATE TABLE  `v_idle`(
 `idle_id` varchar(20) ,
 `idle_name` varchar(60) ,
 `idle_created` datetime 
)*/;

/*Table structure for table `v_idle_time` */

DROP TABLE IF EXISTS `v_idle_time`;

/*!50001 DROP VIEW IF EXISTS `v_idle_time` */;
/*!50001 DROP TABLE IF EXISTS `v_idle_time` */;

/*!50001 CREATE TABLE  `v_idle_time`(
 `drop_id` varchar(20) ,
 `drop_data` varchar(65) ,
 `drop_display` varchar(20) 
)*/;

/*Table structure for table `v_job` */

DROP TABLE IF EXISTS `v_job`;

/*!50001 DROP VIEW IF EXISTS `v_job` */;
/*!50001 DROP TABLE IF EXISTS `v_job` */;

/*!50001 CREATE TABLE  `v_job`(
 `job_id` varchar(20) ,
 `job` varchar(30) ,
 `wocat_id` varchar(20) ,
 `wocat` varchar(100) ,
 `stdhr` int(10) ,
 `stdrate` decimal(30,2) ,
 `flagrate` decimal(30,2) ,
 `job_created` datetime 
)*/;

/*Table structure for table `v_jobclock_checkin_checkout` */

DROP TABLE IF EXISTS `v_jobclock_checkin_checkout`;

/*!50001 DROP VIEW IF EXISTS `v_jobclock_checkin_checkout` */;
/*!50001 DROP TABLE IF EXISTS `v_jobclock_checkin_checkout` */;

/*!50001 CREATE TABLE  `v_jobclock_checkin_checkout`(
 `wo_refno` varchar(20) ,
 `chk_date` datetime ,
 `chk_in` datetime ,
 `chk_out` datetime ,
 `check_date` date ,
 `check_in` time ,
 `check_out` time 
)*/;

/*Table structure for table `v_jobclock_detail` */

DROP TABLE IF EXISTS `v_jobclock_detail`;

/*!50001 DROP VIEW IF EXISTS `v_jobclock_detail` */;
/*!50001 DROP TABLE IF EXISTS `v_jobclock_detail` */;

/*!50001 CREATE TABLE  `v_jobclock_detail`(
 `wo_refno` varchar(20) ,
 `seqno` int(11) ,
 `idle_id` varchar(20) ,
 `idle_name` varchar(60) ,
 `time_start` timestamp ,
 `time_end` timestamp ,
 `remarks` varchar(255) ,
 `NoOfDays` bigint(21) ,
 `NoOfHours` bigint(21) ,
 `NoOfMinutes` bigint(21) 
)*/;

/*Table structure for table `v_jobclock_master` */

DROP TABLE IF EXISTS `v_jobclock_master`;

/*!50001 DROP VIEW IF EXISTS `v_jobclock_master` */;
/*!50001 DROP TABLE IF EXISTS `v_jobclock_master` */;

/*!50001 CREATE TABLE  `v_jobclock_master`(
 `wo_refno` varchar(20) ,
 `job_start` datetime ,
 `job_end` datetime ,
 `job_status` enum('0','1','2') ,
 `std_working_hrs` varchar(20) ,
 `total_idle_hrs` varchar(20) ,
 `total_working_hrs` varchar(20) ,
 `variance` varchar(20) ,
 `jobstatus_desc` varchar(8) ,
 `NoOfDays` bigint(21) ,
 `NoOfHours` bigint(21) ,
 `NoOfMinutes` bigint(21) 
)*/;

/*Table structure for table `v_laborandpartssummary` */

DROP TABLE IF EXISTS `v_laborandpartssummary`;

/*!50001 DROP VIEW IF EXISTS `v_laborandpartssummary` */;
/*!50001 DROP TABLE IF EXISTS `v_laborandpartssummary` */;

/*!50001 CREATE TABLE  `v_laborandpartssummary`(
 `transaction_date` datetime ,
 `estimate_refno` varchar(20) ,
 `wo_refno` varchar(20) ,
 `po_refno` varchar(20) ,
 `labor` decimal(42,2) ,
 `lubricants` decimal(42,2) ,
 `sublet` decimal(42,2) ,
 `parts` decimal(42,2) ,
 `discount` decimal(20,2) ,
 `total_amount` decimal(20,2) ,
 `subtotal_amount` decimal(20,2) ,
 `vat` int(3) 
)*/;

/*Table structure for table `v_lowstock` */

DROP TABLE IF EXISTS `v_lowstock`;

/*!50001 DROP VIEW IF EXISTS `v_lowstock` */;
/*!50001 DROP TABLE IF EXISTS `v_lowstock` */;

/*!50001 CREATE TABLE  `v_lowstock`(
 `parts_id` varchar(20) ,
 `parts` varchar(60) ,
 `parts_discount` decimal(20,2) ,
 `part_srp` decimal(20,2) ,
 `parts_lowstock` decimal(30,0) ,
 `part_onhand` decimal(10,0) ,
 `partstatus` varchar(30) ,
 `part_created` datetime ,
 `is_low` varchar(1) 
)*/;

/*Table structure for table `v_make` */

DROP TABLE IF EXISTS `v_make`;

/*!50001 DROP VIEW IF EXISTS `v_make` */;
/*!50001 DROP TABLE IF EXISTS `v_make` */;

/*!50001 CREATE TABLE  `v_make`(
 `make_id` varchar(20) ,
 `make` varchar(20) ,
 `make_rate` decimal(20,2) ,
 `make_created` datetime 
)*/;

/*Table structure for table `v_material` */

DROP TABLE IF EXISTS `v_material`;

/*!50001 DROP VIEW IF EXISTS `v_material` */;
/*!50001 DROP TABLE IF EXISTS `v_material` */;

/*!50001 CREATE TABLE  `v_material`(
 `material_id` varchar(20) ,
 `material` varchar(60) ,
 `material_disc` decimal(20,2) ,
 `material_srp` decimal(20,2) ,
 `material_lowstock` decimal(30,0) ,
 `material_onhand` decimal(30,0) ,
 `material_status` varchar(30) ,
 `material_created` datetime 
)*/;

/*Table structure for table `v_model` */

DROP TABLE IF EXISTS `v_model`;

/*!50001 DROP VIEW IF EXISTS `v_model` */;
/*!50001 DROP TABLE IF EXISTS `v_model` */;

/*!50001 CREATE TABLE  `v_model`(
 `model_id` varchar(20) ,
 `model` varchar(30) ,
 `variant` varchar(50) ,
 `variantdesc` varchar(100) ,
 `model_created` datetime 
)*/;

/*Table structure for table `v_online_estimate_detail` */

DROP TABLE IF EXISTS `v_online_estimate_detail`;

/*!50001 DROP VIEW IF EXISTS `v_online_estimate_detail` */;
/*!50001 DROP TABLE IF EXISTS `v_online_estimate_detail` */;

/*!50001 CREATE TABLE  `v_online_estimate_detail`(
 `oe_id` varchar(20) ,
 `oed_id` int(20) ,
 `id` varchar(20) ,
 `type` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) ,
 `itemname` varchar(60) 
)*/;

/*Table structure for table `v_online_estimate_master` */

DROP TABLE IF EXISTS `v_online_estimate_master`;

/*!50001 DROP VIEW IF EXISTS `v_online_estimate_master` */;
/*!50001 DROP TABLE IF EXISTS `v_online_estimate_master` */;

/*!50001 CREATE TABLE  `v_online_estimate_master`(
 `oe_id` varchar(20) ,
 `transaction_date` datetime ,
 `customer` varchar(20) ,
 `address` longtext ,
 `contactno` varchar(50) ,
 `emailaddress` varchar(100) ,
 `plateno` varchar(20) ,
 `year` varchar(4) ,
 `make` varchar(20) ,
 `model` varchar(20) ,
 `color` varchar(20) ,
 `variant` varchar(100) ,
 `engineno` varchar(20) ,
 `chassisno` varchar(20) ,
 `serialno` varchar(20) ,
 `remarks` longtext ,
 `recommendation` longtext ,
 `status` enum('0','1','2') ,
 `payment_id` varchar(20) ,
 `payment_mode` varchar(60) ,
 `subtotal_amount` decimal(10,2) ,
 `total_amount` decimal(10,2) ,
 `vat` varchar(5) ,
 `discount` decimal(10,2) ,
 `discounted_price` decimal(10,2) 
)*/;

/*Table structure for table `v_package_detail` */

DROP TABLE IF EXISTS `v_package_detail`;

/*!50001 DROP VIEW IF EXISTS `v_package_detail` */;
/*!50001 DROP TABLE IF EXISTS `v_package_detail` */;

/*!50001 CREATE TABLE  `v_package_detail`(
 `package_id` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `job_rate` decimal(30,2) ,
 `parts_rate` decimal(20,2) ,
 `material_rate` decimal(20,2) ,
 `accessory_rate` decimal(20,2) 
)*/;

/*Table structure for table `v_package_master` */

DROP TABLE IF EXISTS `v_package_master`;

/*!50001 DROP VIEW IF EXISTS `v_package_master` */;
/*!50001 DROP TABLE IF EXISTS `v_package_master` */;

/*!50001 CREATE TABLE  `v_package_master`(
 `package_id` varchar(20) ,
 `package_name` varchar(100) ,
 `status` int(11) ,
 `created_by` varchar(20) ,
 `created_date` datetime ,
 `modified_by` varchar(20) ,
 `modified_date` datetime 
)*/;

/*Table structure for table `v_parts` */

DROP TABLE IF EXISTS `v_parts`;

/*!50001 DROP VIEW IF EXISTS `v_parts` */;
/*!50001 DROP TABLE IF EXISTS `v_parts` */;

/*!50001 CREATE TABLE  `v_parts`(
 `parts_id` varchar(20) ,
 `parts` varchar(60) ,
 `parts_discount` decimal(20,2) ,
 `part_srp` decimal(20,2) ,
 `parts_lowstock` decimal(30,0) ,
 `part_onhand` decimal(10,0) ,
 `partstatus` varchar(30) ,
 `part_created` datetime ,
 `parts_old_price` decimal(20,2) ,
 `new_price_date` datetime ,
 `old_price_date` datetime 
)*/;

/*Table structure for table `v_payment` */

DROP TABLE IF EXISTS `v_payment`;

/*!50001 DROP VIEW IF EXISTS `v_payment` */;
/*!50001 DROP TABLE IF EXISTS `v_payment` */;

/*!50001 CREATE TABLE  `v_payment`(
 `payment_id` varchar(20) ,
 `payment` varchar(60) ,
 `payment_created` datetime 
)*/;

/*Table structure for table `v_po_detail_accessory` */

DROP TABLE IF EXISTS `v_po_detail_accessory`;

/*!50001 DROP VIEW IF EXISTS `v_po_detail_accessory` */;
/*!50001 DROP TABLE IF EXISTS `v_po_detail_accessory` */;

/*!50001 CREATE TABLE  `v_po_detail_accessory`(
 `po_refno` varchar(20) ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_po_detail_job` */

DROP TABLE IF EXISTS `v_po_detail_job`;

/*!50001 DROP VIEW IF EXISTS `v_po_detail_job` */;
/*!50001 DROP TABLE IF EXISTS `v_po_detail_job` */;

/*!50001 CREATE TABLE  `v_po_detail_job`(
 `po_refno` varchar(20) ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_po_detail_make` */

DROP TABLE IF EXISTS `v_po_detail_make`;

/*!50001 DROP VIEW IF EXISTS `v_po_detail_make` */;
/*!50001 DROP TABLE IF EXISTS `v_po_detail_make` */;

/*!50001 CREATE TABLE  `v_po_detail_make`(
 `po_refno` varchar(20) ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_po_detail_material` */

DROP TABLE IF EXISTS `v_po_detail_material`;

/*!50001 DROP VIEW IF EXISTS `v_po_detail_material` */;
/*!50001 DROP TABLE IF EXISTS `v_po_detail_material` */;

/*!50001 CREATE TABLE  `v_po_detail_material`(
 `po_refno` varchar(20) ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_po_detail_parts` */

DROP TABLE IF EXISTS `v_po_detail_parts`;

/*!50001 DROP VIEW IF EXISTS `v_po_detail_parts` */;
/*!50001 DROP TABLE IF EXISTS `v_po_detail_parts` */;

/*!50001 CREATE TABLE  `v_po_detail_parts`(
 `po_refno` varchar(20) ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_po_master` */

DROP TABLE IF EXISTS `v_po_master`;

/*!50001 DROP VIEW IF EXISTS `v_po_master` */;
/*!50001 DROP TABLE IF EXISTS `v_po_master` */;

/*!50001 CREATE TABLE  `v_po_master`(
 `estimate_refno` varchar(20) ,
 `wo_refno` varchar(20) ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `payment_id` varchar(20) ,
 `payment_mode` varchar(60) ,
 `subtotal_amount` decimal(20,2) ,
 `discount` decimal(20,2) ,
 `discounted_price` decimal(20,2) ,
 `vat` int(3) ,
 `total_amount` decimal(20,2) ,
 `remarks` longtext ,
 `created_by` varchar(20) ,
 `trans_status` enum('0','1') 
)*/;

/*Table structure for table `v_sales` */

DROP TABLE IF EXISTS `v_sales`;

/*!50001 DROP VIEW IF EXISTS `v_sales` */;
/*!50001 DROP TABLE IF EXISTS `v_sales` */;

/*!50001 CREATE TABLE  `v_sales`(
 `estimate_refno` varchar(20) ,
 `wo_refno` varchar(20) ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `customer_id` varchar(20) ,
 `customername` varchar(182) ,
 `cust_address` varchar(324) ,
 `landline` varchar(20) ,
 `fax` varchar(20) ,
 `mobile` varchar(20) ,
 `vehicle_id` varchar(20) ,
 `odometer` decimal(10,0) ,
 `wo_trans_date` datetime ,
 `plate_no` varchar(60) ,
 `conduction_sticker` varchar(20) ,
 `make_desc` varchar(20) ,
 `year_desc` bigint(11) ,
 `model_desc` varchar(30) ,
 `color_desc` varchar(20) ,
 `variant` varchar(60) ,
 `engine_no` varchar(60) ,
 `chassis_no` varchar(60) ,
 `serial_no` varchar(60) ,
 `payment_id` varchar(20) ,
 `promise_time` time ,
 `promise_date` date ,
 `payment_mode` varchar(60) ,
 `subtotal_amount` decimal(20,2) ,
 `discount` decimal(20,2) ,
 `discounted_price` decimal(20,2) ,
 `vat` int(3) ,
 `total_amount` decimal(20,2) ,
 `remarks` longtext ,
 `recommendation` longtext ,
 `created_by` varchar(20) ,
 `trans_status` enum('0','1','2','3','4','5','6','7','8','9','10') ,
 `emp_id` varchar(20) ,
 `trans_month` int(2) ,
 `trans_year` int(4) ,
 `tech_name` varchar(60) ,
 `status_desc` varchar(12) ,
 `labor` decimal(42,2) ,
 `lubricants` decimal(42,2) ,
 `sublet` decimal(42,2) ,
 `parts` decimal(42,2) 
)*/;

/*Table structure for table `v_sales_history` */

DROP TABLE IF EXISTS `v_sales_history`;

/*!50001 DROP VIEW IF EXISTS `v_sales_history` */;
/*!50001 DROP TABLE IF EXISTS `v_sales_history` */;

/*!50001 CREATE TABLE  `v_sales_history`(
 `wo_refno` varchar(20) ,
 `estimate_refno` varchar(20) ,
 `transaction_date` datetime ,
 `customer_id` varchar(20) ,
 `custname` varchar(182) ,
 `pleate_no` varchar(60) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `job` varchar(30) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_salesperunit` */

DROP TABLE IF EXISTS `v_salesperunit`;

/*!50001 DROP VIEW IF EXISTS `v_salesperunit` */;
/*!50001 DROP TABLE IF EXISTS `v_salesperunit` */;

/*!50001 CREATE TABLE  `v_salesperunit`(
 `plate_no` varchar(60) ,
 `vehicle_id` varchar(30) ,
 `customer_id` varchar(20) ,
 `wo_refno` varchar(20) ,
 `total_amount` decimal(20,2) ,
 `transaction_date` datetime 
)*/;

/*Table structure for table `v_service_detail` */

DROP TABLE IF EXISTS `v_service_detail`;

/*!50001 DROP VIEW IF EXISTS `v_service_detail` */;
/*!50001 DROP TABLE IF EXISTS `v_service_detail` */;

/*!50001 CREATE TABLE  `v_service_detail`(
 `estimate_refno` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_service_detail_accessory` */

DROP TABLE IF EXISTS `v_service_detail_accessory`;

/*!50001 DROP VIEW IF EXISTS `v_service_detail_accessory` */;
/*!50001 DROP TABLE IF EXISTS `v_service_detail_accessory` */;

/*!50001 CREATE TABLE  `v_service_detail_accessory`(
 `estimate_refno` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `qty` int(12) ,
 `accessory_name` varchar(60) ,
 `amount` decimal(20,2) 
)*/;

/*Table structure for table `v_service_detail_job` */

DROP TABLE IF EXISTS `v_service_detail_job`;

/*!50001 DROP VIEW IF EXISTS `v_service_detail_job` */;
/*!50001 DROP TABLE IF EXISTS `v_service_detail_job` */;

/*!50001 CREATE TABLE  `v_service_detail_job`(
 `estimate_refno` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `qty` int(12) ,
 `job_name` varchar(30) ,
 `amount` decimal(20,2) ,
 `flagrate` decimal(30,2) ,
 `stdworkinghr` int(10) 
)*/;

/*Table structure for table `v_service_detail_make` */

DROP TABLE IF EXISTS `v_service_detail_make`;

/*!50001 DROP VIEW IF EXISTS `v_service_detail_make` */;
/*!50001 DROP TABLE IF EXISTS `v_service_detail_make` */;

/*!50001 CREATE TABLE  `v_service_detail_make`(
 `estimate_refno` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `qty` int(12) ,
 `make_name` varchar(20) ,
 `amount` decimal(20,2) 
)*/;

/*Table structure for table `v_service_detail_material` */

DROP TABLE IF EXISTS `v_service_detail_material`;

/*!50001 DROP VIEW IF EXISTS `v_service_detail_material` */;
/*!50001 DROP TABLE IF EXISTS `v_service_detail_material` */;

/*!50001 CREATE TABLE  `v_service_detail_material`(
 `estimate_refno` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `qty` int(12) ,
 `material_name` varchar(60) ,
 `amount` decimal(20,2) 
)*/;

/*Table structure for table `v_service_detail_parts` */

DROP TABLE IF EXISTS `v_service_detail_parts`;

/*!50001 DROP VIEW IF EXISTS `v_service_detail_parts` */;
/*!50001 DROP TABLE IF EXISTS `v_service_detail_parts` */;

/*!50001 CREATE TABLE  `v_service_detail_parts`(
 `estimate_refno` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `qty` int(12) ,
 `parts_name` varchar(60) ,
 `amount` decimal(20,2) 
)*/;

/*Table structure for table `v_service_master` */

DROP TABLE IF EXISTS `v_service_master`;

/*!50001 DROP VIEW IF EXISTS `v_service_master` */;
/*!50001 DROP TABLE IF EXISTS `v_service_master` */;

/*!50001 CREATE TABLE  `v_service_master`(
 `estimate_refno` varchar(20) ,
 `wo_refno` varchar(20) ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `customer_id` varchar(20) ,
 `customername` varchar(182) ,
 `cust_address` varchar(324) ,
 `landline` varchar(20) ,
 `fax` varchar(20) ,
 `mobile` varchar(20) ,
 `vehicle_id` varchar(20) ,
 `odometer` decimal(10,0) ,
 `wo_trans_date` datetime ,
 `plate_no` varchar(60) ,
 `conduction_sticker` varchar(20) ,
 `make_desc` varchar(20) ,
 `year_desc` bigint(11) ,
 `model_desc` varchar(30) ,
 `color_desc` varchar(20) ,
 `variant` varchar(60) ,
 `engine_no` varchar(60) ,
 `chassis_no` varchar(60) ,
 `serial_no` varchar(60) ,
 `payment_id` varchar(20) ,
 `promise_time` time ,
 `promise_date` date ,
 `committed_date` varbinary(19) ,
 `payment_mode` varchar(60) ,
 `subtotal_amount` decimal(20,2) ,
 `discount` decimal(20,2) ,
 `discounted_price` decimal(20,2) ,
 `vat` int(3) ,
 `total_amount` decimal(20,2) ,
 `remarks` longtext ,
 `recommendation` longtext ,
 `created_by` varchar(20) ,
 `trans_status` enum('0','1','2','3','4','5','6','7','8','9','10') ,
 `emp_id` varchar(20) ,
 `trans_month` int(2) ,
 `trans_year` int(4) ,
 `tech_name` varchar(60) ,
 `status_desc` varchar(12) 
)*/;

/*Table structure for table `v_service_master_approved` */

DROP TABLE IF EXISTS `v_service_master_approved`;

/*!50001 DROP VIEW IF EXISTS `v_service_master_approved` */;
/*!50001 DROP TABLE IF EXISTS `v_service_master_approved` */;

/*!50001 CREATE TABLE  `v_service_master_approved`(
 `estimate_refno` varchar(20) ,
 `wo_refno` varchar(20) ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `customer_id` varchar(20) ,
 `vehicle_id` varchar(20) ,
 `odometer` decimal(10,0) ,
 `payment_id` varchar(20) ,
 `subtotal_amount` decimal(20,2) ,
 `discount` decimal(20,2) ,
 `discounted_price` decimal(20,2) ,
 `vat` int(3) ,
 `total_amount` decimal(20,2) ,
 `remarks` longtext ,
 `created_by` varchar(20) ,
 `trans_status` enum('0','1','2','3','4','5','6','7','8','9','10') 
)*/;

/*Table structure for table `v_technician_performance` */

DROP TABLE IF EXISTS `v_technician_performance`;

/*!50001 DROP VIEW IF EXISTS `v_technician_performance` */;
/*!50001 DROP TABLE IF EXISTS `v_technician_performance` */;

/*!50001 CREATE TABLE  `v_technician_performance`(
 `estimate_refno` varchar(20) ,
 `wo_refno` varchar(20) ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `emp_id` varchar(20) ,
 `tech_name` varchar(60) ,
 `job_start` datetime ,
 `job_end` datetime ,
 `NoOfDays` bigint(21) ,
 `NoOfHours` bigint(21) ,
 `NoOfMinutes` bigint(21) ,
 `committed_date` varbinary(19) ,
 `completion_date` datetime 
)*/;

/*Table structure for table `v_technician_performance1` */

DROP TABLE IF EXISTS `v_technician_performance1`;

/*!50001 DROP VIEW IF EXISTS `v_technician_performance1` */;
/*!50001 DROP TABLE IF EXISTS `v_technician_performance1` */;

/*!50001 CREATE TABLE  `v_technician_performance1`(
 `estimate_refno` varchar(20) ,
 `wo_refno` varchar(20) ,
 `wo_trans_date` datetime ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `technician` varchar(20) ,
 `employeename` varchar(60) ,
 `promise_date` date ,
 `promise_time` time ,
 `committed_date` varbinary(19) ,
 `actual_completed_date` datetime 
)*/;

/*Table structure for table `v_technician_report` */

DROP TABLE IF EXISTS `v_technician_report`;

/*!50001 DROP VIEW IF EXISTS `v_technician_report` */;
/*!50001 DROP TABLE IF EXISTS `v_technician_report` */;

/*!50001 CREATE TABLE  `v_technician_report`(
 `estimate_refno` varchar(20) ,
 `wo_refno` varchar(20) ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `technician` varchar(20) ,
 `employee` varchar(60) ,
 `id` varchar(20) ,
 `flagrate` decimal(30,2) 
)*/;

/*Table structure for table `v_temp_estimate` */

DROP TABLE IF EXISTS `v_temp_estimate`;

/*!50001 DROP VIEW IF EXISTS `v_temp_estimate` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_estimate` */;

/*!50001 CREATE TABLE  `v_temp_estimate`(
 `ses_id` longtext ,
 `estimate_id` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `qty` int(12) ,
 `rate` decimal(20,2) 
)*/;

/*Table structure for table `v_temp_estimate_accessory` */

DROP TABLE IF EXISTS `v_temp_estimate_accessory`;

/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_accessory` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_accessory` */;

/*!50001 CREATE TABLE  `v_temp_estimate_accessory`(
 `ses_id` longtext ,
 `estimate_id` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `accessory` varchar(60) ,
 `rate` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_temp_estimate_job` */

DROP TABLE IF EXISTS `v_temp_estimate_job`;

/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_job` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_job` */;

/*!50001 CREATE TABLE  `v_temp_estimate_job`(
 `ses_id` longtext ,
 `estimate_id` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `job` varchar(30) ,
 `rate` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_temp_estimate_make` */

DROP TABLE IF EXISTS `v_temp_estimate_make`;

/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_make` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_make` */;

/*!50001 CREATE TABLE  `v_temp_estimate_make`(
 `ses_id` longtext ,
 `estimate_id` varchar(20) ,
 `type` varchar(20) ,
 `rate` decimal(20,2) 
)*/;

/*Table structure for table `v_temp_estimate_material` */

DROP TABLE IF EXISTS `v_temp_estimate_material`;

/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_material` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_material` */;

/*!50001 CREATE TABLE  `v_temp_estimate_material`(
 `ses_id` longtext ,
 `estimate_id` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `material` varchar(60) ,
 `rate` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_temp_estimate_parts` */

DROP TABLE IF EXISTS `v_temp_estimate_parts`;

/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_parts` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_parts` */;

/*!50001 CREATE TABLE  `v_temp_estimate_parts`(
 `ses_id` longtext ,
 `estimate_id` varchar(20) ,
 `type` varchar(20) ,
 `id` varchar(20) ,
 `parts` varchar(60) ,
 `rate` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_temp_po_detail` */

DROP TABLE IF EXISTS `v_temp_po_detail`;

/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail` */;

/*!50001 CREATE TABLE  `v_temp_po_detail`(
 `ses_id` longtext ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_temp_po_detail_accessory` */

DROP TABLE IF EXISTS `v_temp_po_detail_accessory`;

/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail_accessory` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail_accessory` */;

/*!50001 CREATE TABLE  `v_temp_po_detail_accessory`(
 `ses_id` longtext ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_temp_po_detail_job` */

DROP TABLE IF EXISTS `v_temp_po_detail_job`;

/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail_job` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail_job` */;

/*!50001 CREATE TABLE  `v_temp_po_detail_job`(
 `ses_id` longtext ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_temp_po_detail_material` */

DROP TABLE IF EXISTS `v_temp_po_detail_material`;

/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail_material` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail_material` */;

/*!50001 CREATE TABLE  `v_temp_po_detail_material`(
 `ses_id` longtext ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_temp_po_detail_parts` */

DROP TABLE IF EXISTS `v_temp_po_detail_parts`;

/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail_parts` */;
/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail_parts` */;

/*!50001 CREATE TABLE  `v_temp_po_detail_parts`(
 `ses_id` longtext ,
 `type` varchar(20) ,
 `description` varchar(20) ,
 `amount` decimal(20,2) ,
 `qty` int(12) 
)*/;

/*Table structure for table `v_user_access` */

DROP TABLE IF EXISTS `v_user_access`;

/*!50001 DROP VIEW IF EXISTS `v_user_access` */;
/*!50001 DROP TABLE IF EXISTS `v_user_access` */;

/*!50001 CREATE TABLE  `v_user_access`(
 `menu_id` varchar(100) ,
 `menu` varchar(100) ,
 `user_id` varchar(100) ,
 `status` enum('0','1') 
)*/;

/*Table structure for table `v_users` */

DROP TABLE IF EXISTS `v_users`;

/*!50001 DROP VIEW IF EXISTS `v_users` */;
/*!50001 DROP TABLE IF EXISTS `v_users` */;

/*!50001 CREATE TABLE  `v_users`(
 `username` varchar(20) ,
 `password` varchar(100) ,
 `name` varchar(100) ,
 `image` text ,
 `user_created` datetime ,
 `user_status` enum('0','1') ,
 `status_desc` varchar(8) 
)*/;

/*Table structure for table `v_vehicleinfo` */

DROP TABLE IF EXISTS `v_vehicleinfo`;

/*!50001 DROP VIEW IF EXISTS `v_vehicleinfo` */;
/*!50001 DROP TABLE IF EXISTS `v_vehicleinfo` */;

/*!50001 CREATE TABLE  `v_vehicleinfo`(
 `vehicle_id` varchar(30) ,
 `customer_id` varchar(20) ,
 `customername` varchar(182) ,
 `address` varchar(255) ,
 `plate_no` varchar(60) ,
 `conduction_sticker` varchar(20) ,
 `make` varchar(60) ,
 `make_desc` varchar(20) ,
 `year` varchar(60) ,
 `year_desc` bigint(11) ,
 `model` varchar(60) ,
 `model_desc` varchar(30) ,
 `color` varchar(60) ,
 `color_desc` varchar(20) ,
 `variant` varchar(60) ,
 `description` varchar(100) ,
 `engine_no` varchar(60) ,
 `chassis_no` varchar(60) ,
 `serial_no` varchar(60) 
)*/;

/*Table structure for table `v_vehiclesummary` */

DROP TABLE IF EXISTS `v_vehiclesummary`;

/*!50001 DROP VIEW IF EXISTS `v_vehiclesummary` */;
/*!50001 DROP TABLE IF EXISTS `v_vehiclesummary` */;

/*!50001 CREATE TABLE  `v_vehiclesummary`(
 `year` int(11) ,
 `january` bigint(21) ,
 `february` bigint(21) ,
 `march` bigint(21) ,
 `april` bigint(21) ,
 `may` bigint(21) ,
 `june` bigint(21) ,
 `july` bigint(21) ,
 `august` bigint(21) ,
 `september` bigint(21) ,
 `october` bigint(21) ,
 `november` bigint(21) ,
 `december` bigint(21) 
)*/;

/*Table structure for table `v_wocat` */

DROP TABLE IF EXISTS `v_wocat`;

/*!50001 DROP VIEW IF EXISTS `v_wocat` */;
/*!50001 DROP TABLE IF EXISTS `v_wocat` */;

/*!50001 CREATE TABLE  `v_wocat`(
 `wocat_id` varchar(20) ,
 `wocat` varchar(100) ,
 `wocat_created` datetime 
)*/;

/*Table structure for table `v_work_order_master` */

DROP TABLE IF EXISTS `v_work_order_master`;

/*!50001 DROP VIEW IF EXISTS `v_work_order_master` */;
/*!50001 DROP TABLE IF EXISTS `v_work_order_master` */;

/*!50001 CREATE TABLE  `v_work_order_master`(
 `wo_refno` varchar(20) ,
 `po_refno` varchar(20) ,
 `transaction_date` datetime ,
 `customer_id` varchar(20) ,
 `customername` varchar(182) ,
 `cust_address` varchar(324) ,
 `landline` varchar(20) ,
 `fax` varchar(20) ,
 `mobile` varchar(20) ,
 `vehicle_id` varchar(20) ,
 `plate_no` varchar(60) ,
 `conduction_sticker` varchar(20) ,
 `make_desc` varchar(20) ,
 `year_desc` bigint(11) ,
 `model_desc` varchar(30) ,
 `color_desc` varchar(20) ,
 `variant` varchar(60) ,
 `engine_no` varchar(60) ,
 `chassis_no` varchar(60) ,
 `serial_no` varchar(60) ,
 `payment_id` varchar(20) ,
 `payment_mode` varchar(60) ,
 `subtotal_amount` decimal(20,2) ,
 `discount` decimal(20,2) ,
 `discounted_price` decimal(20,2) ,
 `vat` int(3) ,
 `total_amount` decimal(20,2) ,
 `remarks` longtext ,
 `recommendation` longtext ,
 `created_by` varchar(20) ,
 `trans_status` enum('0','1','2','3','4','5','6','7','8','9','10') ,
 `emp_id` varchar(20) ,
 `tech_name` varchar(60) ,
 `status_desc` varchar(12) 
)*/;

/*Table structure for table `v_year` */

DROP TABLE IF EXISTS `v_year`;

/*!50001 DROP VIEW IF EXISTS `v_year` */;
/*!50001 DROP TABLE IF EXISTS `v_year` */;

/*!50001 CREATE TABLE  `v_year`(
 `year_id` varchar(20) ,
 `year` int(11) ,
 `created` datetime 
)*/;

/*View structure for view v_accessory */

/*!50001 DROP TABLE IF EXISTS `v_accessory` */;
/*!50001 DROP VIEW IF EXISTS `v_accessory` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_accessory` AS (select `tbl_accessory`.`accessory_id` AS `accessory_id`,`tbl_accessory`.`accessory` AS `accessory`,`tbl_accessory`.`access_disc` AS `access_disc`,`tbl_accessory`.`access_srp` AS `access_srp`,`tbl_accessory`.`access_low` AS `access_low`,`tbl_accessory`.`access_onhand` AS `access_onhand`,`tbl_accessory`.`access_status` AS `access_status`,`tbl_accessory`.`access_created` AS `access_created` from `tbl_accessory`) */;

/*View structure for view v_billing */

/*!50001 DROP TABLE IF EXISTS `v_billing` */;
/*!50001 DROP VIEW IF EXISTS `v_billing` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_billing` AS (select `tbl_billing`.`wo_refno` AS `wo_refno`,`tbl_service_master`.`wo_trans_date` AS `wo_trans_date`,`tbl_billing`.`billing_refno` AS `billing_refno`,`tbl_billing`.`billing_date` AS `billing_date`,`v_customer`.`custname` AS `customername`,`tbl_billing`.`total_amount` AS `total_amount`,`tbl_billing`.`billing_status` AS `billing_status` from ((`tbl_billing` join `tbl_service_master` on((`tbl_service_master`.`wo_refno` = `tbl_billing`.`wo_refno`))) join `v_customer` on((convert(`v_customer`.`cust_id` using latin1) = `tbl_service_master`.`customer_id`))) order by `tbl_billing`.`billing_date` desc) */;

/*View structure for view v_billing_detail */

/*!50001 DROP TABLE IF EXISTS `v_billing_detail` */;
/*!50001 DROP VIEW IF EXISTS `v_billing_detail` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_billing_detail` AS select `tbl_billing`.`wo_refno` AS `wo_refno`,`tbl_billing`.`billing_refno` AS `billing_refno`,`tbl_billing`.`billing_date` AS `billing_date`,`tbl_billing`.`total_amount` AS `total_amount`,`tbl_billing`.`billing_status` AS `billing_status`,`tbl_service_master`.`estimate_refno` AS `estimate_refno`,`tbl_service_master`.`payment_id` AS `payment_id`,(case when (`tbl_service_master`.`po_refno` = _latin1'0') then _utf8'' else `tbl_service_master`.`po_refno` end) AS `po_refno`,`tbl_service_master`.`transaction_date` AS `transaction_date`,`tbl_service_master`.`vehicle_id` AS `vehicle_id`,`tbl_vehicleinfo`.`plate_no` AS `plate_no` from (`tbl_vehicleinfo` left join (`tbl_service_master` left join `tbl_billing` on((`tbl_billing`.`wo_refno` = `tbl_service_master`.`wo_refno`))) on((`tbl_service_master`.`vehicle_id` = `tbl_vehicleinfo`.`vehicle_id`))) */;

/*View structure for view v_billing_master */

/*!50001 DROP TABLE IF EXISTS `v_billing_master` */;
/*!50001 DROP VIEW IF EXISTS `v_billing_master` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_billing_master` AS (select `billing`.`billing_refno` AS `billing_refno`,`billing`.`billing_date` AS `billing_date`,`tbl_customer`.`cust_id` AS `cust_id`,concat(`tbl_customer`.`firstname`,_ascii' ',`tbl_customer`.`middlename`,_ascii' ',`tbl_customer`.`lastname`) AS `cust_name`,`tbl_customer`.`address` AS `cust_addr`,concat(`tbl_customer`.`landline`,_ascii' ',`tbl_customer`.`fax`,_ascii' ',`tbl_customer`.`mobile`) AS `tel_no`,(select sum(`tbl_billing`.`total_amount`) AS `SUM(tbl_billing.total_amount)` from `tbl_billing` where (`tbl_billing`.`billing_refno` = `billing`.`billing_refno`)) AS `total_billing`,`tbl_service_master`.`payment_id` AS `payment_id` from ((`tbl_service_master` left join `tbl_billing` `billing` on((`tbl_service_master`.`wo_refno` = `billing`.`wo_refno`))) left join `tbl_customer` on((convert(`tbl_customer`.`cust_id` using latin1) = `tbl_service_master`.`customer_id`))) where (`tbl_service_master`.`trans_status` = _latin1'6') group by `billing`.`billing_refno`) */;

/*View structure for view v_card_billing */

/*!50001 DROP TABLE IF EXISTS `v_card_billing` */;
/*!50001 DROP VIEW IF EXISTS `v_card_billing` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_card_billing` AS (select `v_customer`.`cust_id` AS `cust_id`,`v_customer`.`custname` AS `custname`,(select count(0) AS `COUNT(*)` from `tbl_service_master` where ((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)) and (`tbl_service_master`.`trans_status` = _latin1'5') and (`tbl_service_master`.`payment_id` = _latin1'PAY00000004'))) AS `transaction_counts`,(select sum(`tbl_service_master`.`total_amount`) AS `SUM(tbl_service_master.total_amount)` from `tbl_service_master` where ((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)) and (`tbl_service_master`.`trans_status` = _latin1'5') and (`tbl_service_master`.`payment_id` = _latin1'PAY00000004'))) AS `total_billings` from `v_customer` where ((select count(0) AS `COUNT(*)` from `tbl_service_master` where ((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)) and (`tbl_service_master`.`trans_status` = _latin1'5') and (`tbl_service_master`.`payment_id` = _latin1'PAY00000004'))) > 0)) */;

/*View structure for view v_cash_billing */

/*!50001 DROP TABLE IF EXISTS `v_cash_billing` */;
/*!50001 DROP VIEW IF EXISTS `v_cash_billing` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_cash_billing` AS (select `v_customer`.`cust_id` AS `cust_id`,`v_customer`.`custname` AS `custname`,(select count(0) AS `COUNT(*)` from `tbl_service_master` where ((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)) and ((`tbl_service_master`.`trans_status` = _latin1'5') or (`tbl_service_master`.`trans_status` = _latin1'6')) and (`tbl_service_master`.`payment_id` = _latin1'PAY00000003'))) AS `transaction_counts`,(select sum(`tbl_service_master`.`total_amount`) AS `SUM(tbl_service_master.total_amount)` from `tbl_service_master` where ((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)) and ((`tbl_service_master`.`trans_status` = _latin1'5') or (`tbl_service_master`.`trans_status` = _latin1'6')) and (`tbl_service_master`.`payment_id` = _latin1'PAY00000003'))) AS `total_billings` from `v_customer` where ((select count(0) AS `COUNT(*)` from `tbl_service_master` where ((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)) and ((`tbl_service_master`.`trans_status` = _latin1'5') or (`tbl_service_master`.`trans_status` = _latin1'6')) and (`tbl_service_master`.`payment_id` = _latin1'PAY00000003'))) > 0)) */;

/*View structure for view v_color */

/*!50001 DROP TABLE IF EXISTS `v_color` */;
/*!50001 DROP VIEW IF EXISTS `v_color` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_color` AS (select `tbl_color`.`color_id` AS `color_id`,`tbl_color`.`color` AS `color`,`tbl_color`.`color_created` AS `color_created` from `tbl_color`) */;

/*View structure for view v_configuration */

/*!50001 DROP TABLE IF EXISTS `v_configuration` */;
/*!50001 DROP VIEW IF EXISTS `v_configuration` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_configuration` AS (select `tbl_configuration`.`id` AS `id`,`tbl_configuration`.`config_type` AS `config_type`,`tbl_configuration`.`description` AS `description`,`tbl_configuration`.`remarks` AS `remarks`,`tbl_configuration`.`value` AS `value`,`tbl_configuration`.`status` AS `status`,`tbl_configuration`.`created_date` AS `created_date`,`tbl_configuration`.`created_by` AS `created_by`,`tbl_configuration`.`modified_date` AS `modified_date`,`tbl_configuration`.`modified_by` AS `modified_by` from `tbl_configuration`) */;

/*View structure for view v_controlno */

/*!50001 DROP TABLE IF EXISTS `v_controlno` */;
/*!50001 DROP VIEW IF EXISTS `v_controlno` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_controlno` AS (select `tbl_controlno`.`id` AS `id`,`tbl_controlno`.`control_type` AS `control_type`,`tbl_controlno`.`digit` AS `digit`,`tbl_controlno`.`lastseqno` AS `lastseqno`,`tbl_controlno`.`control_code` AS `control_code` from `tbl_controlno`) */;

/*View structure for view v_customer */

/*!50001 DROP TABLE IF EXISTS `v_customer` */;
/*!50001 DROP VIEW IF EXISTS `v_customer` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_customer` AS (select `tbl_customer`.`cust_id` AS `cust_id`,`tbl_customer`.`salutation` AS `salutation`,`tbl_customer`.`lastname` AS `lastname`,`tbl_customer`.`firstname` AS `firstname`,`tbl_customer`.`middlename` AS `middlename`,concat(`tbl_customer`.`firstname`,_ascii' ',`tbl_customer`.`middlename`,_ascii' ',`tbl_customer`.`lastname`) AS `custname`,`tbl_customer`.`address` AS `address`,`tbl_customer`.`city` AS `city`,`tbl_customer`.`province` AS `province`,`tbl_customer`.`zipcode` AS `zipcode`,`tbl_customer`.`birthday` AS `birthday`,`tbl_customer`.`gender` AS `gender`,`tbl_customer`.`tin` AS `tin`,`tbl_customer`.`company` AS `company`,`tbl_customer`.`source` AS `source`,`tbl_customer`.`email` AS `email`,`tbl_customer`.`landline` AS `landline`,`tbl_customer`.`fax` AS `fax`,`tbl_customer`.`mobile` AS `mobile`,`tbl_customer`.`cust_created` AS `cust_created` from `tbl_customer`) */;

/*View structure for view v_employee */

/*!50001 DROP TABLE IF EXISTS `v_employee` */;
/*!50001 DROP VIEW IF EXISTS `v_employee` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_employee` AS (select `tbl_employee`.`emp_id` AS `emp_id`,`tbl_employee`.`employee` AS `employee`,`tbl_employee`.`position` AS `position`,`tbl_employee`.`emp_status` AS `emp_status`,`tbl_employee`.`emp_image` AS `emp_image`,`tbl_employee`.`emp_created` AS `emp_created`,`tbl_employee`.`contactno` AS `contactno`,`tbl_employee`.`emailaddress` AS `emailaddress` from `tbl_employee`) */;

/*View structure for view v_employeecompensation */

/*!50001 DROP TABLE IF EXISTS `v_employeecompensation` */;
/*!50001 DROP VIEW IF EXISTS `v_employeecompensation` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_employeecompensation` AS (select `tbl_service_detail`.`estimate_refno` AS `estimate_refno`,`v_service_master`.`wo_refno` AS `workorder_refno`,`v_service_master`.`emp_id` AS `emp_id`,`v_service_master`.`tech_name` AS `tech_name`,`v_service_master`.`transaction_date` AS `transaction_date`,`tbl_service_detail`.`type` AS `type`,`tbl_service_detail`.`id` AS `id`,`v_job`.`job` AS `jobname`,`v_job`.`flagrate` AS `amount`,`tbl_service_detail`.`qty` AS `qty` from ((`tbl_service_detail` join `v_service_master` on((`v_service_master`.`estimate_refno` = `tbl_service_detail`.`estimate_refno`))) join `v_job` on((convert(`v_job`.`job_id` using latin1) = `tbl_service_detail`.`id`))) where ((`tbl_service_detail`.`type` = 'job') and (`v_service_master`.`wo_refno` <> '0'))) */;

/*View structure for view v_estimate_cost */

/*!50001 DROP TABLE IF EXISTS `v_estimate_cost` */;
/*!50001 DROP VIEW IF EXISTS `v_estimate_cost` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_estimate_cost` AS (select `tbl_estimate_cost`.`estimate_refno` AS `estimate_refno`,`tbl_estimate_cost`.`wo_refno` AS `wo_refno`,`tbl_estimate_cost`.`transaction_date` AS `transaction_date`,`tbl_estimate_cost`.`customer_id` AS `customer_id`,`tbl_estimate_cost`.`vehicle_id` AS `vehicle_id`,`tbl_estimate_cost`.`plate_no` AS `plate_no`,`tbl_estimate_cost`.`year` AS `year`,`tbl_estimate_cost`.`make` AS `make`,`tbl_estimate_cost`.`model` AS `model`,`tbl_estimate_cost`.`color` AS `color`,`tbl_estimate_cost`.`engine_no` AS `engine_no`,`tbl_estimate_cost`.`chassis_no` AS `chassis_no`,`tbl_estimate_cost`.`total_amount` AS `total_amount`,`tbl_estimate_cost`.`trans_status` AS `trans_status` from `tbl_estimate_cost` order by `tbl_estimate_cost`.`estimate_refno`,`tbl_estimate_cost`.`trans_status`) */;

/*View structure for view v_for_billing_detail */

/*!50001 DROP TABLE IF EXISTS `v_for_billing_detail` */;
/*!50001 DROP VIEW IF EXISTS `v_for_billing_detail` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_for_billing_detail` AS (select `tbl_service_master`.`customer_id` AS `customer_id`,`tbl_service_master`.`wo_refno` AS `wo_refno`,(case when (`tbl_service_master`.`po_refno` = _latin1'0') then _utf8'' else `tbl_service_master`.`po_refno` end) AS `po_refno`,`tbl_service_master`.`transaction_date` AS `transaction_date`,`tbl_vehicleinfo`.`plate_no` AS `plate_no`,`tbl_service_master`.`total_amount` AS `total_amount`,`tbl_service_master`.`payment_id` AS `payment_id`,`tbl_service_master`.`trans_status` AS `trans_status` from (`tbl_vehicleinfo` join `tbl_service_master` on((`tbl_vehicleinfo`.`vehicle_id` = `tbl_service_master`.`vehicle_id`))) where ((`tbl_service_master`.`trans_status` = _latin1'5') or (`tbl_service_master`.`trans_status` = _latin1'6'))) */;

/*View structure for view v_for_billing_master */

/*!50001 DROP TABLE IF EXISTS `v_for_billing_master` */;
/*!50001 DROP VIEW IF EXISTS `v_for_billing_master` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_for_billing_master` AS (select `v_customer`.`cust_id` AS `cust_id`,`v_customer`.`custname` AS `custname`,(select count(0) AS `COUNT(*)` from `tbl_service_master` where ((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)) and (`tbl_service_master`.`trans_status` = _latin1'5') and ((`tbl_service_master`.`payment_id` = _latin1'PAY00000001') or (`tbl_service_master`.`payment_id` = _latin1'PAY00000002')))) AS `transaction_counts`,(select sum(`tbl_service_master`.`total_amount`) AS `SUM(tbl_service_master.total_amount)` from `tbl_service_master` where ((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)) and (`tbl_service_master`.`trans_status` = _latin1'5') and ((`tbl_service_master`.`payment_id` = _latin1'PAY00000001') or (`tbl_service_master`.`payment_id` = _latin1'PAY00000002')))) AS `total_billings` from `v_customer` where ((select count(0) AS `COUNT(*)` from `tbl_service_master` where ((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)) and (`tbl_service_master`.`trans_status` = _latin1'5') and ((`tbl_service_master`.`payment_id` = _latin1'PAY00000001') or (`tbl_service_master`.`payment_id` = _latin1'PAY00000002')))) > 0)) */;

/*View structure for view v_idle */

/*!50001 DROP TABLE IF EXISTS `v_idle` */;
/*!50001 DROP VIEW IF EXISTS `v_idle` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_idle` AS (select `tbl_idle`.`idle_id` AS `idle_id`,`tbl_idle`.`idle_name` AS `idle_name`,`tbl_idle`.`idle_created` AS `idle_created` from `tbl_idle`) */;

/*View structure for view v_idle_time */

/*!50001 DROP TABLE IF EXISTS `v_idle_time` */;
/*!50001 DROP VIEW IF EXISTS `v_idle_time` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_idle_time` AS (select `tbl_drop_data`.`drop_id` AS `drop_id`,`tbl_drop_data`.`drop_data` AS `drop_data`,`tbl_drop_data`.`drop_display` AS `drop_display` from `tbl_drop_data` where (`tbl_drop_data`.`drop_display` = _latin1'idle_time') order by `tbl_drop_data`.`drop_id`) */;

/*View structure for view v_job */

/*!50001 DROP TABLE IF EXISTS `v_job` */;
/*!50001 DROP VIEW IF EXISTS `v_job` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_job` AS (select `tbl_job`.`job_id` AS `job_id`,`tbl_job`.`job` AS `job`,`tbl_job`.`wocat_id` AS `wocat_id`,`tbl_wocat`.`wocat` AS `wocat`,`tbl_job`.`stdhr` AS `stdhr`,`tbl_job`.`stdrate` AS `stdrate`,`tbl_job`.`flagrate` AS `flagrate`,`tbl_job`.`job_created` AS `job_created` from (`tbl_job` join `tbl_wocat` on((`tbl_wocat`.`wocat_id` = `tbl_job`.`wocat_id`)))) */;

/*View structure for view v_jobclock_checkin_checkout */

/*!50001 DROP TABLE IF EXISTS `v_jobclock_checkin_checkout` */;
/*!50001 DROP VIEW IF EXISTS `v_jobclock_checkin_checkout` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_jobclock_checkin_checkout` AS (select `tbl_jobclock_checkin_checkout`.`wo_refno` AS `wo_refno`,`tbl_jobclock_checkin_checkout`.`check_date` AS `chk_date`,`tbl_jobclock_checkin_checkout`.`check_in` AS `chk_in`,`tbl_jobclock_checkin_checkout`.`check_out` AS `chk_out`,cast(`tbl_jobclock_checkin_checkout`.`check_date` as date) AS `check_date`,cast(`tbl_jobclock_checkin_checkout`.`check_in` as time) AS `check_in`,cast(`tbl_jobclock_checkin_checkout`.`check_out` as time) AS `check_out` from `tbl_jobclock_checkin_checkout`) */;

/*View structure for view v_jobclock_detail */

/*!50001 DROP TABLE IF EXISTS `v_jobclock_detail` */;
/*!50001 DROP VIEW IF EXISTS `v_jobclock_detail` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_jobclock_detail` AS (select `tbl_jobclock_detail`.`wo_refno` AS `wo_refno`,`tbl_jobclock_detail`.`seqno` AS `seqno`,`tbl_jobclock_detail`.`idle_id` AS `idle_id`,(select `tbl_idle`.`idle_name` AS `idle_name` from `tbl_idle` where (convert(`tbl_idle`.`idle_id` using latin1) = `tbl_jobclock_detail`.`idle_id`)) AS `idle_name`,`tbl_jobclock_detail`.`time_start` AS `time_start`,`tbl_jobclock_detail`.`time_end` AS `time_end`,`tbl_jobclock_detail`.`remarks` AS `remarks`,timestampdiff(DAY,`tbl_jobclock_detail`.`time_start`,`tbl_jobclock_detail`.`time_end`) AS `NoOfDays`,(timestampdiff(HOUR,`tbl_jobclock_detail`.`time_start`,`tbl_jobclock_detail`.`time_end`) % 24) AS `NoOfHours`,(timestampdiff(MINUTE,`tbl_jobclock_detail`.`time_start`,`tbl_jobclock_detail`.`time_end`) % 60) AS `NoOfMinutes` from `tbl_jobclock_detail`) */;

/*View structure for view v_jobclock_master */

/*!50001 DROP TABLE IF EXISTS `v_jobclock_master` */;
/*!50001 DROP VIEW IF EXISTS `v_jobclock_master` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_jobclock_master` AS (select `tbl_jobclock_master`.`wo_refno` AS `wo_refno`,`tbl_jobclock_master`.`job_start` AS `job_start`,`tbl_jobclock_master`.`job_end` AS `job_end`,`tbl_jobclock_master`.`job_status` AS `job_status`,`tbl_jobclock_master`.`std_working_hrs` AS `std_working_hrs`,`tbl_jobclock_master`.`total_idle_hrs` AS `total_idle_hrs`,`tbl_jobclock_master`.`total_working_hrs` AS `total_working_hrs`,`tbl_jobclock_master`.`variance` AS `variance`,(case when (`tbl_jobclock_master`.`job_status` = _latin1'1') then _utf8'ON-GOING' when (`tbl_jobclock_master`.`job_status` = _latin1'2') then _utf8'FINISHED' end) AS `jobstatus_desc`,timestampdiff(DAY,`tbl_jobclock_master`.`job_start`,`tbl_jobclock_master`.`job_end`) AS `NoOfDays`,(timestampdiff(HOUR,`tbl_jobclock_master`.`job_start`,`tbl_jobclock_master`.`job_end`) % 24) AS `NoOfHours`,(timestampdiff(MINUTE,`tbl_jobclock_master`.`job_start`,`tbl_jobclock_master`.`job_end`) % 60) AS `NoOfMinutes` from `tbl_jobclock_master`) */;

/*View structure for view v_laborandpartssummary */

/*!50001 DROP TABLE IF EXISTS `v_laborandpartssummary` */;
/*!50001 DROP VIEW IF EXISTS `v_laborandpartssummary` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_laborandpartssummary` AS (select `tbl_service_master`.`transaction_date` AS `transaction_date`,`tbl_service_master`.`estimate_refno` AS `estimate_refno`,`tbl_service_master`.`wo_refno` AS `wo_refno`,`tbl_service_master`.`po_refno` AS `po_refno`,(select sum(`tbl_service_detail`.`amount`) AS `sum(tbl_service_detail.amount)` from `tbl_service_detail` where ((`tbl_service_detail`.`estimate_refno` = `tbl_service_master`.`estimate_refno`) and (`tbl_service_detail`.`type` = 'job'))) AS `labor`,(select sum(`tbl_service_detail`.`amount`) AS `SUM(tbl_service_detail.amount)` from `tbl_service_detail` where ((`tbl_service_detail`.`estimate_refno` = `tbl_service_master`.`estimate_refno`) and (`tbl_service_detail`.`type` = 'accessory'))) AS `lubricants`,(select sum(`tbl_service_detail`.`amount`) AS `SUM(tbl_service_detail.amount)` from `tbl_service_detail` where ((`tbl_service_detail`.`estimate_refno` = `tbl_service_master`.`estimate_refno`) and (`tbl_service_detail`.`type` = 'material'))) AS `sublet`,(select sum(`tbl_service_detail`.`amount`) AS `SUM(tbl_service_detail.amount)` from `tbl_service_detail` where ((`tbl_service_detail`.`estimate_refno` = `tbl_service_master`.`estimate_refno`) and (`tbl_service_detail`.`type` = 'parts'))) AS `parts`,`tbl_service_master`.`discount` AS `discount`,`tbl_service_master`.`total_amount` AS `total_amount`,`tbl_service_master`.`subtotal_amount` AS `subtotal_amount`,`tbl_service_master`.`vat` AS `vat` from `tbl_service_master` where (`tbl_service_master`.`trans_status` in ('7','10'))) */;

/*View structure for view v_lowstock */

/*!50001 DROP TABLE IF EXISTS `v_lowstock` */;
/*!50001 DROP VIEW IF EXISTS `v_lowstock` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_lowstock` AS (select `tbl_parts`.`parts_id` AS `parts_id`,`tbl_parts`.`parts` AS `parts`,`tbl_parts`.`parts_discount` AS `parts_discount`,`tbl_parts`.`part_srp` AS `part_srp`,`tbl_parts`.`parts_lowstock` AS `parts_lowstock`,`tbl_parts`.`part_onhand` AS `part_onhand`,`tbl_parts`.`partstatus` AS `partstatus`,`tbl_parts`.`part_created` AS `part_created`,(case when (`tbl_parts`.`part_onhand` < `tbl_parts`.`parts_lowstock`) then '1' else '0' end) AS `is_low` from `tbl_parts`) */;

/*View structure for view v_make */

/*!50001 DROP TABLE IF EXISTS `v_make` */;
/*!50001 DROP VIEW IF EXISTS `v_make` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_make` AS (select `tbl_make`.`make_id` AS `make_id`,`tbl_make`.`make` AS `make`,`tbl_make`.`make_rate` AS `make_rate`,`tbl_make`.`make_created` AS `make_created` from `tbl_make`) */;

/*View structure for view v_material */

/*!50001 DROP TABLE IF EXISTS `v_material` */;
/*!50001 DROP VIEW IF EXISTS `v_material` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_material` AS (select `tbl_material`.`material_id` AS `material_id`,`tbl_material`.`material` AS `material`,`tbl_material`.`material_disc` AS `material_disc`,`tbl_material`.`material_srp` AS `material_srp`,`tbl_material`.`material_lowstock` AS `material_lowstock`,`tbl_material`.`material_onhand` AS `material_onhand`,`tbl_material`.`material_status` AS `material_status`,`tbl_material`.`material_created` AS `material_created` from `tbl_material`) */;

/*View structure for view v_model */

/*!50001 DROP TABLE IF EXISTS `v_model` */;
/*!50001 DROP VIEW IF EXISTS `v_model` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_model` AS (select `tbl_model`.`model_id` AS `model_id`,`tbl_model`.`model` AS `model`,`tbl_model`.`variant` AS `variant`,`tbl_model`.`variantdesc` AS `variantdesc`,`tbl_model`.`model_created` AS `model_created` from `tbl_model`) */;

/*View structure for view v_online_estimate_detail */

/*!50001 DROP TABLE IF EXISTS `v_online_estimate_detail` */;
/*!50001 DROP VIEW IF EXISTS `v_online_estimate_detail` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_online_estimate_detail` AS (select `oe_dtl`.`oe_id` AS `oe_id`,`oe_dtl`.`oed_id` AS `oed_id`,`oe_dtl`.`id` AS `id`,`oe_dtl`.`type` AS `type`,`oe_dtl`.`amount` AS `amount`,`oe_dtl`.`qty` AS `qty`,(case when (`oe_dtl`.`type` = 'accessory') then (select `v_accessory`.`accessory` AS `accessory` from `v_accessory` where (convert(`v_accessory`.`accessory_id` using latin1) = `oe_dtl`.`id`)) when (`oe_dtl`.`type` = 'job') then (select `v_job`.`job` AS `job` from `v_job` where (convert(`v_job`.`job_id` using latin1) = `oe_dtl`.`id`)) when (`oe_dtl`.`type` = 'material') then (select `v_material`.`material` AS `material` from `v_material` where (convert(`v_material`.`material_id` using latin1) = `oe_dtl`.`id`)) when (`oe_dtl`.`type` = 'parts') then (select `v_parts`.`parts` AS `parts` from `v_parts` where (convert(`v_parts`.`parts_id` using latin1) = `oe_dtl`.`id`)) end) AS `itemname` from `tbl_online_estimate_detail` `oe_dtl`) */;

/*View structure for view v_online_estimate_master */

/*!50001 DROP TABLE IF EXISTS `v_online_estimate_master` */;
/*!50001 DROP VIEW IF EXISTS `v_online_estimate_master` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_online_estimate_master` AS (select `tbl_online_estimate_master`.`oe_id` AS `oe_id`,`tbl_online_estimate_master`.`transaction_date` AS `transaction_date`,`tbl_online_estimate_master`.`customer` AS `customer`,`tbl_online_estimate_master`.`address` AS `address`,`tbl_online_estimate_master`.`contactno` AS `contactno`,`tbl_online_estimate_master`.`emailaddress` AS `emailaddress`,`tbl_online_estimate_master`.`plateno` AS `plateno`,`tbl_online_estimate_master`.`year` AS `year`,`tbl_online_estimate_master`.`make` AS `make`,`tbl_online_estimate_master`.`model` AS `model`,`tbl_online_estimate_master`.`color` AS `color`,`tbl_online_estimate_master`.`variant` AS `variant`,`tbl_online_estimate_master`.`engineno` AS `engineno`,`tbl_online_estimate_master`.`chassisno` AS `chassisno`,`tbl_online_estimate_master`.`serialno` AS `serialno`,`tbl_online_estimate_master`.`remarks` AS `remarks`,`tbl_online_estimate_master`.`recommendation` AS `recommendation`,`tbl_online_estimate_master`.`status` AS `status`,`tbl_online_estimate_master`.`payment_id` AS `payment_id`,(select `tbl_payment`.`payment` AS `payment` from `tbl_payment` where (convert(`tbl_payment`.`payment_id` using latin1) = `tbl_online_estimate_master`.`payment_id`)) AS `payment_mode`,`tbl_online_estimate_master`.`subtotal_amount` AS `subtotal_amount`,`tbl_online_estimate_master`.`total_amount` AS `total_amount`,`tbl_online_estimate_master`.`vat` AS `vat`,`tbl_online_estimate_master`.`discount` AS `discount`,`tbl_online_estimate_master`.`discounted_price` AS `discounted_price` from `tbl_online_estimate_master`) */;

/*View structure for view v_package_detail */

/*!50001 DROP TABLE IF EXISTS `v_package_detail` */;
/*!50001 DROP VIEW IF EXISTS `v_package_detail` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_package_detail` AS (select `tbl_package_detail`.`package_id` AS `package_id`,`tbl_package_detail`.`type` AS `type`,`tbl_package_detail`.`id` AS `id`,(select `tbl_job`.`stdrate` AS `stdrate` from `tbl_job` where (convert(`tbl_job`.`job_id` using latin1) = `tbl_package_detail`.`id`)) AS `job_rate`,(select `tbl_parts`.`part_srp` AS `part_srp` from `tbl_parts` where (convert(`tbl_parts`.`parts_id` using latin1) = `tbl_package_detail`.`id`)) AS `parts_rate`,(select `tbl_material`.`material_srp` AS `material_srp` from `tbl_material` where (convert(`tbl_material`.`material_id` using latin1) = `tbl_package_detail`.`id`)) AS `material_rate`,(select `tbl_accessory`.`access_srp` AS `access_srp` from `tbl_accessory` where (convert(`tbl_accessory`.`accessory_id` using latin1) = `tbl_package_detail`.`id`)) AS `accessory_rate` from `tbl_package_detail`) */;

/*View structure for view v_package_master */

/*!50001 DROP TABLE IF EXISTS `v_package_master` */;
/*!50001 DROP VIEW IF EXISTS `v_package_master` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_package_master` AS (select `tbl_package_master`.`package_id` AS `package_id`,`tbl_package_master`.`package_name` AS `package_name`,`tbl_package_master`.`status` AS `status`,`tbl_package_master`.`created_by` AS `created_by`,`tbl_package_master`.`created_date` AS `created_date`,`tbl_package_master`.`modified_by` AS `modified_by`,`tbl_package_master`.`modified_date` AS `modified_date` from `tbl_package_master`) */;

/*View structure for view v_parts */

/*!50001 DROP TABLE IF EXISTS `v_parts` */;
/*!50001 DROP VIEW IF EXISTS `v_parts` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parts` AS (select `tbl_parts`.`parts_id` AS `parts_id`,`tbl_parts`.`parts` AS `parts`,`tbl_parts`.`parts_discount` AS `parts_discount`,`tbl_parts`.`part_srp` AS `part_srp`,`tbl_parts`.`parts_lowstock` AS `parts_lowstock`,`tbl_parts`.`part_onhand` AS `part_onhand`,`tbl_parts`.`partstatus` AS `partstatus`,`tbl_parts`.`part_created` AS `part_created`,`tbl_parts`.`parts_old_price` AS `parts_old_price`,`tbl_parts`.`new_price_date` AS `new_price_date`,`tbl_parts`.`old_price_date` AS `old_price_date` from `tbl_parts`) */;

/*View structure for view v_payment */

/*!50001 DROP TABLE IF EXISTS `v_payment` */;
/*!50001 DROP VIEW IF EXISTS `v_payment` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_payment` AS (select `tbl_payment`.`payment_id` AS `payment_id`,`tbl_payment`.`payment` AS `payment`,`tbl_payment`.`payment_created` AS `payment_created` from `tbl_payment`) */;

/*View structure for view v_po_detail_accessory */

/*!50001 DROP TABLE IF EXISTS `v_po_detail_accessory` */;
/*!50001 DROP VIEW IF EXISTS `v_po_detail_accessory` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po_detail_accessory` AS (select `tbl_po_detail`.`po_refno` AS `po_refno`,`tbl_po_detail`.`type` AS `type`,`tbl_po_detail`.`description` AS `description`,`tbl_po_detail`.`amount` AS `amount`,`tbl_po_detail`.`qty` AS `qty` from `tbl_po_detail` where (`tbl_po_detail`.`type` = _latin1'accessory')) */;

/*View structure for view v_po_detail_job */

/*!50001 DROP TABLE IF EXISTS `v_po_detail_job` */;
/*!50001 DROP VIEW IF EXISTS `v_po_detail_job` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po_detail_job` AS (select `tbl_po_detail`.`po_refno` AS `po_refno`,`tbl_po_detail`.`type` AS `type`,`tbl_po_detail`.`description` AS `description`,`tbl_po_detail`.`amount` AS `amount`,`tbl_po_detail`.`qty` AS `qty` from `tbl_po_detail` where (`tbl_po_detail`.`type` = _latin1'job')) */;

/*View structure for view v_po_detail_make */

/*!50001 DROP TABLE IF EXISTS `v_po_detail_make` */;
/*!50001 DROP VIEW IF EXISTS `v_po_detail_make` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po_detail_make` AS (select `tbl_po_detail`.`po_refno` AS `po_refno`,`tbl_po_detail`.`type` AS `type`,`tbl_po_detail`.`description` AS `description`,`tbl_po_detail`.`amount` AS `amount`,`tbl_po_detail`.`qty` AS `qty` from `tbl_po_detail` where (`tbl_po_detail`.`type` = _latin1'make')) */;

/*View structure for view v_po_detail_material */

/*!50001 DROP TABLE IF EXISTS `v_po_detail_material` */;
/*!50001 DROP VIEW IF EXISTS `v_po_detail_material` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po_detail_material` AS (select `tbl_po_detail`.`po_refno` AS `po_refno`,`tbl_po_detail`.`type` AS `type`,`tbl_po_detail`.`description` AS `description`,`tbl_po_detail`.`amount` AS `amount`,`tbl_po_detail`.`qty` AS `qty` from `tbl_po_detail` where (`tbl_po_detail`.`type` = _latin1'material')) */;

/*View structure for view v_po_detail_parts */

/*!50001 DROP TABLE IF EXISTS `v_po_detail_parts` */;
/*!50001 DROP VIEW IF EXISTS `v_po_detail_parts` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po_detail_parts` AS (select `tbl_po_detail`.`po_refno` AS `po_refno`,`tbl_po_detail`.`type` AS `type`,`tbl_po_detail`.`description` AS `description`,`tbl_po_detail`.`amount` AS `amount`,`tbl_po_detail`.`qty` AS `qty` from `tbl_po_detail` where (`tbl_po_detail`.`type` = _latin1'parts')) */;

/*View structure for view v_po_master */

/*!50001 DROP TABLE IF EXISTS `v_po_master` */;
/*!50001 DROP VIEW IF EXISTS `v_po_master` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po_master` AS (select `tbl_po_master`.`estimate_refno` AS `estimate_refno`,`tbl_po_master`.`wo_refno` AS `wo_refno`,`tbl_po_master`.`po_refno` AS `po_refno`,`tbl_po_master`.`transaction_date` AS `transaction_date`,`tbl_po_master`.`payment_id` AS `payment_id`,(select `tbl_payment`.`payment` AS `payment` from `tbl_payment` where (convert(`tbl_payment`.`payment_id` using latin1) = `tbl_po_master`.`payment_id`)) AS `payment_mode`,`tbl_po_master`.`subtotal_amount` AS `subtotal_amount`,`tbl_po_master`.`discount` AS `discount`,`tbl_po_master`.`discounted_price` AS `discounted_price`,`tbl_po_master`.`vat` AS `vat`,`tbl_po_master`.`total_amount` AS `total_amount`,`tbl_po_master`.`remarks` AS `remarks`,`tbl_po_master`.`created_by` AS `created_by`,`tbl_po_master`.`trans_status` AS `trans_status` from `tbl_po_master` where (`tbl_po_master`.`trans_status` = _latin1'1')) */;

/*View structure for view v_sales */

/*!50001 DROP TABLE IF EXISTS `v_sales` */;
/*!50001 DROP VIEW IF EXISTS `v_sales` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_sales` AS (select `v_service_master`.`estimate_refno` AS `estimate_refno`,`v_service_master`.`wo_refno` AS `wo_refno`,`v_service_master`.`po_refno` AS `po_refno`,`v_service_master`.`transaction_date` AS `transaction_date`,`v_service_master`.`customer_id` AS `customer_id`,`v_service_master`.`customername` AS `customername`,`v_service_master`.`cust_address` AS `cust_address`,`v_service_master`.`landline` AS `landline`,`v_service_master`.`fax` AS `fax`,`v_service_master`.`mobile` AS `mobile`,`v_service_master`.`vehicle_id` AS `vehicle_id`,`v_service_master`.`odometer` AS `odometer`,`v_service_master`.`wo_trans_date` AS `wo_trans_date`,`v_service_master`.`plate_no` AS `plate_no`,`v_service_master`.`conduction_sticker` AS `conduction_sticker`,`v_service_master`.`make_desc` AS `make_desc`,`v_service_master`.`year_desc` AS `year_desc`,`v_service_master`.`model_desc` AS `model_desc`,`v_service_master`.`color_desc` AS `color_desc`,`v_service_master`.`variant` AS `variant`,`v_service_master`.`engine_no` AS `engine_no`,`v_service_master`.`chassis_no` AS `chassis_no`,`v_service_master`.`serial_no` AS `serial_no`,`v_service_master`.`payment_id` AS `payment_id`,`v_service_master`.`promise_time` AS `promise_time`,`v_service_master`.`promise_date` AS `promise_date`,`v_service_master`.`payment_mode` AS `payment_mode`,`v_service_master`.`subtotal_amount` AS `subtotal_amount`,`v_service_master`.`discount` AS `discount`,`v_service_master`.`discounted_price` AS `discounted_price`,`v_service_master`.`vat` AS `vat`,`v_service_master`.`total_amount` AS `total_amount`,`v_service_master`.`remarks` AS `remarks`,`v_service_master`.`recommendation` AS `recommendation`,`v_service_master`.`created_by` AS `created_by`,`v_service_master`.`trans_status` AS `trans_status`,`v_service_master`.`emp_id` AS `emp_id`,`v_service_master`.`trans_month` AS `trans_month`,`v_service_master`.`trans_year` AS `trans_year`,`v_service_master`.`tech_name` AS `tech_name`,`v_service_master`.`status_desc` AS `status_desc`,(select sum(`tbl_service_detail`.`amount`) AS `sum(tbl_service_detail.amount)` from `tbl_service_detail` where ((`tbl_service_detail`.`estimate_refno` = `v_service_master`.`estimate_refno`) and (`tbl_service_detail`.`type` = 'job'))) AS `labor`,(select sum(`tbl_service_detail`.`amount`) AS `SUM(tbl_service_detail.amount)` from `tbl_service_detail` where ((`tbl_service_detail`.`estimate_refno` = `v_service_master`.`estimate_refno`) and (`tbl_service_detail`.`type` = 'accessory'))) AS `lubricants`,(select sum(`tbl_service_detail`.`amount`) AS `SUM(tbl_service_detail.amount)` from `tbl_service_detail` where ((`tbl_service_detail`.`estimate_refno` = `v_service_master`.`estimate_refno`) and (`tbl_service_detail`.`type` = 'material'))) AS `sublet`,(select sum(`tbl_service_detail`.`amount`) AS `SUM(tbl_service_detail.amount)` from `tbl_service_detail` where ((`tbl_service_detail`.`estimate_refno` = `v_service_master`.`estimate_refno`) and (`tbl_service_detail`.`type` = 'parts'))) AS `parts` from `v_service_master` where (`v_service_master`.`trans_status` = '7')) */;

/*View structure for view v_sales_history */

/*!50001 DROP TABLE IF EXISTS `v_sales_history` */;
/*!50001 DROP VIEW IF EXISTS `v_sales_history` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_sales_history` AS (select `tbl_service_master`.`wo_refno` AS `wo_refno`,`tbl_service_detail`.`estimate_refno` AS `estimate_refno`,`tbl_service_master`.`transaction_date` AS `transaction_date`,`tbl_service_master`.`customer_id` AS `customer_id`,`v_customer`.`custname` AS `custname`,`tbl_vehicleinfo`.`plate_no` AS `pleate_no`,`tbl_service_detail`.`type` AS `type`,`tbl_service_detail`.`id` AS `id`,`tbl_job`.`job` AS `job`,`tbl_service_detail`.`amount` AS `amount`,`tbl_service_detail`.`qty` AS `qty` from ((((`tbl_service_detail` join `tbl_service_master` on((`tbl_service_detail`.`estimate_refno` = `tbl_service_master`.`estimate_refno`))) join `tbl_job` on((`tbl_service_detail`.`id` = convert(`tbl_job`.`job_id` using latin1)))) join `v_customer` on((`tbl_service_master`.`customer_id` = convert(`v_customer`.`cust_id` using latin1)))) join `tbl_vehicleinfo` on((`tbl_vehicleinfo`.`vehicle_id` = `tbl_service_master`.`vehicle_id`))) where ((`tbl_service_detail`.`type` = 'job') and (`tbl_service_master`.`trans_status` = '7'))) */;

/*View structure for view v_salesperunit */

/*!50001 DROP TABLE IF EXISTS `v_salesperunit` */;
/*!50001 DROP VIEW IF EXISTS `v_salesperunit` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_salesperunit` AS (select `tbl_vehicleinfo`.`plate_no` AS `plate_no`,`tbl_vehicleinfo`.`vehicle_id` AS `vehicle_id`,`tbl_service_master`.`customer_id` AS `customer_id`,`tbl_service_master`.`wo_refno` AS `wo_refno`,`tbl_service_master`.`total_amount` AS `total_amount`,`tbl_service_master`.`transaction_date` AS `transaction_date` from (`tbl_service_master` left join `tbl_vehicleinfo` on((`tbl_service_master`.`vehicle_id` = `tbl_vehicleinfo`.`vehicle_id`))) where (`tbl_service_master`.`trans_status` in ('7','10'))) */;

/*View structure for view v_service_detail */

/*!50001 DROP TABLE IF EXISTS `v_service_detail` */;
/*!50001 DROP VIEW IF EXISTS `v_service_detail` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_service_detail` AS (select `tbl_service_detail`.`estimate_refno` AS `estimate_refno`,`tbl_service_detail`.`type` AS `type`,`tbl_service_detail`.`id` AS `id`,`tbl_service_detail`.`amount` AS `amount`,`tbl_service_detail`.`qty` AS `qty` from `tbl_service_detail` order by `tbl_service_detail`.`id`) */;

/*View structure for view v_service_detail_accessory */

/*!50001 DROP TABLE IF EXISTS `v_service_detail_accessory` */;
/*!50001 DROP VIEW IF EXISTS `v_service_detail_accessory` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_service_detail_accessory` AS (select `tbl_service_detail`.`estimate_refno` AS `estimate_refno`,`tbl_service_detail`.`type` AS `type`,`tbl_service_detail`.`id` AS `id`,`tbl_service_detail`.`qty` AS `qty`,(select `tbl_accessory`.`accessory` AS `accessory` from `tbl_accessory` where (convert(`tbl_accessory`.`accessory_id` using latin1) = `tbl_service_detail`.`id`)) AS `accessory_name`,`tbl_service_detail`.`amount` AS `amount` from `tbl_service_detail` where (`tbl_service_detail`.`type` = _latin1'accessory')) */;

/*View structure for view v_service_detail_job */

/*!50001 DROP TABLE IF EXISTS `v_service_detail_job` */;
/*!50001 DROP VIEW IF EXISTS `v_service_detail_job` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_service_detail_job` AS (select `tbl_service_detail`.`estimate_refno` AS `estimate_refno`,`tbl_service_detail`.`type` AS `type`,`tbl_service_detail`.`id` AS `id`,`tbl_service_detail`.`qty` AS `qty`,`tbl_job`.`job` AS `job_name`,`tbl_service_detail`.`amount` AS `amount`,`tbl_job`.`flagrate` AS `flagrate`,`tbl_job`.`stdhr` AS `stdworkinghr` from (`tbl_service_detail` join `tbl_job`) where ((`tbl_service_detail`.`type` = _latin1'job') and (`tbl_service_detail`.`id` = convert(`tbl_job`.`job_id` using latin1)))) */;

/*View structure for view v_service_detail_make */

/*!50001 DROP TABLE IF EXISTS `v_service_detail_make` */;
/*!50001 DROP VIEW IF EXISTS `v_service_detail_make` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_service_detail_make` AS (select `tbl_service_detail`.`estimate_refno` AS `estimate_refno`,`tbl_service_detail`.`type` AS `type`,`tbl_service_detail`.`id` AS `id`,`tbl_service_detail`.`qty` AS `qty`,(select `tbl_make`.`make` AS `make` from `tbl_make` where (convert(`tbl_make`.`make_id` using latin1) = `tbl_service_detail`.`id`)) AS `make_name`,`tbl_service_detail`.`amount` AS `amount` from `tbl_service_detail` where (`tbl_service_detail`.`type` = _latin1'make')) */;

/*View structure for view v_service_detail_material */

/*!50001 DROP TABLE IF EXISTS `v_service_detail_material` */;
/*!50001 DROP VIEW IF EXISTS `v_service_detail_material` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_service_detail_material` AS (select `tbl_service_detail`.`estimate_refno` AS `estimate_refno`,`tbl_service_detail`.`type` AS `type`,`tbl_service_detail`.`id` AS `id`,`tbl_service_detail`.`qty` AS `qty`,(select `tbl_material`.`material` AS `material` from `tbl_material` where (convert(`tbl_material`.`material_id` using latin1) = `tbl_service_detail`.`id`)) AS `material_name`,`tbl_service_detail`.`amount` AS `amount` from `tbl_service_detail` where (`tbl_service_detail`.`type` = _latin1'material')) */;

/*View structure for view v_service_detail_parts */

/*!50001 DROP TABLE IF EXISTS `v_service_detail_parts` */;
/*!50001 DROP VIEW IF EXISTS `v_service_detail_parts` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_service_detail_parts` AS (select `tbl_service_detail`.`estimate_refno` AS `estimate_refno`,`tbl_service_detail`.`type` AS `type`,`tbl_service_detail`.`id` AS `id`,`tbl_service_detail`.`qty` AS `qty`,(select `tbl_parts`.`parts` AS `parts` from `tbl_parts` where (convert(`tbl_parts`.`parts_id` using latin1) = `tbl_service_detail`.`id`)) AS `parts_name`,`tbl_service_detail`.`amount` AS `amount` from `tbl_service_detail` where (`tbl_service_detail`.`type` = _latin1'parts')) */;

/*View structure for view v_service_master */

/*!50001 DROP TABLE IF EXISTS `v_service_master` */;
/*!50001 DROP VIEW IF EXISTS `v_service_master` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_service_master` AS (select `tbl_service_master`.`estimate_refno` AS `estimate_refno`,`tbl_service_master`.`wo_refno` AS `wo_refno`,`tbl_service_master`.`po_refno` AS `po_refno`,`tbl_service_master`.`transaction_date` AS `transaction_date`,`tbl_service_master`.`customer_id` AS `customer_id`,(select concat(`tbl_customer`.`firstname`,_ascii' ',`tbl_customer`.`middlename`,_ascii' ',`tbl_customer`.`lastname`) AS `custname` from `tbl_customer` where (convert(`tbl_customer`.`cust_id` using latin1) = `tbl_service_master`.`customer_id`)) AS `customername`,concat(`tbl_customer`.`address`,_ascii', ',`tbl_customer`.`city`,_ascii', ',`tbl_customer`.`province`) AS `cust_address`,`tbl_customer`.`landline` AS `landline`,`tbl_customer`.`fax` AS `fax`,`tbl_customer`.`mobile` AS `mobile`,`tbl_service_master`.`vehicle_id` AS `vehicle_id`,`tbl_service_master`.`odometer` AS `odometer`,`tbl_service_master`.`wo_trans_date` AS `wo_trans_date`,`v_vehicleinfo`.`plate_no` AS `plate_no`,`v_vehicleinfo`.`conduction_sticker` AS `conduction_sticker`,`v_vehicleinfo`.`make_desc` AS `make_desc`,`v_vehicleinfo`.`year_desc` AS `year_desc`,`v_vehicleinfo`.`model_desc` AS `model_desc`,`v_vehicleinfo`.`color_desc` AS `color_desc`,`v_vehicleinfo`.`variant` AS `variant`,`v_vehicleinfo`.`engine_no` AS `engine_no`,`v_vehicleinfo`.`chassis_no` AS `chassis_no`,`v_vehicleinfo`.`serial_no` AS `serial_no`,`tbl_service_master`.`payment_id` AS `payment_id`,`tbl_service_master`.`promise_time` AS `promise_time`,`tbl_service_master`.`promise_date` AS `promise_date`,concat(`tbl_service_master`.`promise_date`,' ',`tbl_service_master`.`promise_time`) AS `committed_date`,(select `tbl_payment`.`payment` AS `payment` from `tbl_payment` where (convert(`tbl_payment`.`payment_id` using latin1) = `tbl_service_master`.`payment_id`)) AS `payment_mode`,`tbl_service_master`.`subtotal_amount` AS `subtotal_amount`,`tbl_service_master`.`discount` AS `discount`,`tbl_service_master`.`discounted_price` AS `discounted_price`,`tbl_service_master`.`vat` AS `vat`,`tbl_service_master`.`total_amount` AS `total_amount`,`tbl_service_master`.`remarks` AS `remarks`,`tbl_service_master`.`recommendation` AS `recommendation`,`tbl_service_master`.`created_by` AS `created_by`,`tbl_service_master`.`trans_status` AS `trans_status`,`tbl_service_master`.`technician` AS `emp_id`,month(`tbl_service_master`.`transaction_date`) AS `trans_month`,year(`tbl_service_master`.`transaction_date`) AS `trans_year`,(select `tbl_employee`.`employee` AS `employee` from `tbl_employee` where (convert(`tbl_employee`.`emp_id` using latin1) = `tbl_service_master`.`technician`)) AS `tech_name`,(case when (`tbl_service_master`.`trans_status` = _latin1'0') then _utf8'PENDING' when (`tbl_service_master`.`trans_status` = _latin1'1') then _utf8'APPROVED' when (`tbl_service_master`.`trans_status` = _latin1'2') then _utf8'DISAPPROVED' when (`tbl_service_master`.`trans_status` = _latin1'3') then _utf8'CANCELLED' when (`tbl_service_master`.`trans_status` = _latin1'4') then _utf8'FOR REPAIR' when (`tbl_service_master`.`trans_status` = _latin1'5') then _utf8'FINISHED' when (`tbl_service_master`.`trans_status` = _latin1'6') then _utf8'FOR BILLING' when (`tbl_service_master`.`trans_status` = _latin1'7') then _utf8'BILLED' when (`tbl_service_master`.`trans_status` = _latin1'8') then _utf8'ON-GOING' when (`tbl_service_master`.`trans_status` = _latin1'9') then _utf8'FOR APPROVAL' when (`tbl_service_master`.`trans_status` = _latin1'10') then _utf8'CLOSED' end) AS `status_desc` from ((`tbl_service_master` join `v_vehicleinfo`) join `tbl_customer`) where ((`tbl_service_master`.`vehicle_id` = `v_vehicleinfo`.`vehicle_id`) and (`tbl_service_master`.`customer_id` = convert(`tbl_customer`.`cust_id` using latin1))) order by `tbl_service_master`.`trans_status`,`tbl_service_master`.`transaction_date` desc) */;

/*View structure for view v_service_master_approved */

/*!50001 DROP TABLE IF EXISTS `v_service_master_approved` */;
/*!50001 DROP VIEW IF EXISTS `v_service_master_approved` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_service_master_approved` AS (select `tbl_service_master`.`estimate_refno` AS `estimate_refno`,`tbl_service_master`.`wo_refno` AS `wo_refno`,`tbl_service_master`.`po_refno` AS `po_refno`,`tbl_service_master`.`transaction_date` AS `transaction_date`,`tbl_service_master`.`customer_id` AS `customer_id`,`tbl_service_master`.`vehicle_id` AS `vehicle_id`,`tbl_service_master`.`odometer` AS `odometer`,`tbl_service_master`.`payment_id` AS `payment_id`,`tbl_service_master`.`subtotal_amount` AS `subtotal_amount`,`tbl_service_master`.`discount` AS `discount`,`tbl_service_master`.`discounted_price` AS `discounted_price`,`tbl_service_master`.`vat` AS `vat`,`tbl_service_master`.`total_amount` AS `total_amount`,`tbl_service_master`.`remarks` AS `remarks`,`tbl_service_master`.`created_by` AS `created_by`,`tbl_service_master`.`trans_status` AS `trans_status` from `tbl_service_master` where (`tbl_service_master`.`trans_status` = _latin1'1')) */;

/*View structure for view v_technician_performance */

/*!50001 DROP TABLE IF EXISTS `v_technician_performance` */;
/*!50001 DROP VIEW IF EXISTS `v_technician_performance` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_technician_performance` AS (select `v_service_master`.`estimate_refno` AS `estimate_refno`,`v_service_master`.`wo_refno` AS `wo_refno`,`v_service_master`.`po_refno` AS `po_refno`,`v_service_master`.`transaction_date` AS `transaction_date`,`v_service_master`.`emp_id` AS `emp_id`,`v_service_master`.`tech_name` AS `tech_name`,`v_jobclock_master`.`job_start` AS `job_start`,`v_jobclock_master`.`job_end` AS `job_end`,`v_jobclock_master`.`NoOfDays` AS `NoOfDays`,`v_jobclock_master`.`NoOfHours` AS `NoOfHours`,`v_jobclock_master`.`NoOfMinutes` AS `NoOfMinutes`,concat(`v_service_master`.`promise_date`,' ',`v_service_master`.`promise_time`) AS `committed_date`,`v_jobclock_master`.`job_end` AS `completion_date` from (`v_service_master` join `v_jobclock_master` on((`v_jobclock_master`.`wo_refno` = `v_service_master`.`wo_refno`))) where (`v_service_master`.`trans_status` = '7')) */;

/*View structure for view v_technician_performance1 */

/*!50001 DROP TABLE IF EXISTS `v_technician_performance1` */;
/*!50001 DROP VIEW IF EXISTS `v_technician_performance1` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_technician_performance1` AS (select `tbl_service_master`.`estimate_refno` AS `estimate_refno`,`tbl_service_master`.`wo_refno` AS `wo_refno`,`tbl_service_master`.`wo_trans_date` AS `wo_trans_date`,`tbl_service_master`.`po_refno` AS `po_refno`,`tbl_service_master`.`transaction_date` AS `transaction_date`,`tbl_service_master`.`technician` AS `technician`,`v_employee`.`employee` AS `employeename`,`tbl_service_master`.`promise_date` AS `promise_date`,`tbl_service_master`.`promise_time` AS `promise_time`,concat(`tbl_service_master`.`promise_date`,' ',`tbl_service_master`.`promise_time`) AS `committed_date`,`tbl_jobclock_master`.`job_end` AS `actual_completed_date` from ((`tbl_service_master` join `v_employee` on((convert(`v_employee`.`emp_id` using latin1) = `tbl_service_master`.`technician`))) join `tbl_jobclock_master` on((`tbl_jobclock_master`.`wo_refno` = `tbl_service_master`.`wo_refno`))) where (`tbl_service_master`.`trans_status` = '6')) */;

/*View structure for view v_technician_report */

/*!50001 DROP TABLE IF EXISTS `v_technician_report` */;
/*!50001 DROP VIEW IF EXISTS `v_technician_report` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_technician_report` AS (select `tbl_service_master`.`estimate_refno` AS `estimate_refno`,`tbl_service_master`.`wo_refno` AS `wo_refno`,`tbl_service_master`.`po_refno` AS `po_refno`,`tbl_service_master`.`transaction_date` AS `transaction_date`,`tbl_service_master`.`technician` AS `technician`,`tbl_employee`.`employee` AS `employee`,`tbl_service_detail`.`id` AS `id`,`tbl_job`.`flagrate` AS `flagrate` from (((`tbl_service_master` join `tbl_employee`) join `tbl_service_detail`) join `tbl_job`) where ((`tbl_service_master`.`technician` = convert(`tbl_employee`.`emp_id` using latin1)) and (`tbl_service_master`.`trans_status` = _latin1'7') and (`tbl_service_detail`.`id` = convert(`tbl_job`.`job_id` using latin1)) and (`tbl_service_master`.`estimate_refno` = `tbl_service_detail`.`estimate_refno`))) */;

/*View structure for view v_temp_estimate */

/*!50001 DROP TABLE IF EXISTS `v_temp_estimate` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_estimate` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_estimate` AS (select `tbl_temp_estimate`.`ses_id` AS `ses_id`,`tbl_temp_estimate`.`estimate_id` AS `estimate_id`,`tbl_temp_estimate`.`type` AS `type`,`tbl_temp_estimate`.`id` AS `id`,`tbl_temp_estimate`.`qty` AS `qty`,`tbl_temp_estimate`.`rate` AS `rate` from `tbl_temp_estimate`) */;

/*View structure for view v_temp_estimate_accessory */

/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_accessory` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_accessory` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_estimate_accessory` AS (select `tbl_temp_estimate`.`ses_id` AS `ses_id`,`tbl_temp_estimate`.`estimate_id` AS `estimate_id`,`tbl_temp_estimate`.`type` AS `type`,`tbl_temp_estimate`.`id` AS `id`,`tbl_accessory`.`accessory` AS `accessory`,`tbl_temp_estimate`.`rate` AS `rate`,`tbl_temp_estimate`.`qty` AS `qty` from (`tbl_temp_estimate` join `tbl_accessory`) where ((`tbl_temp_estimate`.`type` = _latin1'accessory') and (`tbl_temp_estimate`.`id` = convert(`tbl_accessory`.`accessory_id` using latin1)))) */;

/*View structure for view v_temp_estimate_job */

/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_job` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_job` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_estimate_job` AS (select `tbl_temp_estimate`.`ses_id` AS `ses_id`,`tbl_temp_estimate`.`estimate_id` AS `estimate_id`,`tbl_temp_estimate`.`type` AS `type`,`tbl_temp_estimate`.`id` AS `id`,`tbl_job`.`job` AS `job`,`tbl_temp_estimate`.`rate` AS `rate`,`tbl_temp_estimate`.`qty` AS `qty` from (`tbl_temp_estimate` join `tbl_job`) where ((`tbl_temp_estimate`.`type` = _latin1'job') and (`tbl_temp_estimate`.`id` = convert(`tbl_job`.`job_id` using latin1)))) */;

/*View structure for view v_temp_estimate_make */

/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_make` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_make` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_estimate_make` AS (select `tbl_temp_estimate`.`ses_id` AS `ses_id`,`tbl_temp_estimate`.`estimate_id` AS `estimate_id`,`tbl_temp_estimate`.`type` AS `type`,`tbl_temp_estimate`.`rate` AS `rate` from `tbl_temp_estimate` where (`tbl_temp_estimate`.`type` = _latin1'make')) */;

/*View structure for view v_temp_estimate_material */

/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_material` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_material` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_estimate_material` AS (select `tbl_temp_estimate`.`ses_id` AS `ses_id`,`tbl_temp_estimate`.`estimate_id` AS `estimate_id`,`tbl_temp_estimate`.`type` AS `type`,`tbl_temp_estimate`.`id` AS `id`,`tbl_material`.`material` AS `material`,`tbl_temp_estimate`.`rate` AS `rate`,`tbl_temp_estimate`.`qty` AS `qty` from (`tbl_temp_estimate` join `tbl_material`) where ((`tbl_temp_estimate`.`type` = _latin1'material') and (`tbl_temp_estimate`.`id` = convert(`tbl_material`.`material_id` using latin1)))) */;

/*View structure for view v_temp_estimate_parts */

/*!50001 DROP TABLE IF EXISTS `v_temp_estimate_parts` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_estimate_parts` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_estimate_parts` AS (select `tbl_temp_estimate`.`ses_id` AS `ses_id`,`tbl_temp_estimate`.`estimate_id` AS `estimate_id`,`tbl_temp_estimate`.`type` AS `type`,`tbl_temp_estimate`.`id` AS `id`,`tbl_parts`.`parts` AS `parts`,`tbl_temp_estimate`.`rate` AS `rate`,`tbl_temp_estimate`.`qty` AS `qty` from (`tbl_temp_estimate` join `tbl_parts`) where ((`tbl_temp_estimate`.`type` = _latin1'parts') and (`tbl_temp_estimate`.`id` = convert(`tbl_parts`.`parts_id` using latin1)))) */;

/*View structure for view v_temp_po_detail */

/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_po_detail` AS (select `tbl_temp_po_detail`.`ses_id` AS `ses_id`,`tbl_temp_po_detail`.`type` AS `type`,`tbl_temp_po_detail`.`description` AS `description`,`tbl_temp_po_detail`.`amount` AS `amount`,`tbl_temp_po_detail`.`qty` AS `qty` from `tbl_temp_po_detail`) */;

/*View structure for view v_temp_po_detail_accessory */

/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail_accessory` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail_accessory` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_po_detail_accessory` AS (select `tbl_temp_po_detail`.`ses_id` AS `ses_id`,`tbl_temp_po_detail`.`type` AS `type`,`tbl_temp_po_detail`.`description` AS `description`,`tbl_temp_po_detail`.`amount` AS `amount`,`tbl_temp_po_detail`.`qty` AS `qty` from `tbl_temp_po_detail` where (`tbl_temp_po_detail`.`type` = _latin1'accessory')) */;

/*View structure for view v_temp_po_detail_job */

/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail_job` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail_job` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_po_detail_job` AS (select `tbl_temp_po_detail`.`ses_id` AS `ses_id`,`tbl_temp_po_detail`.`type` AS `type`,`tbl_temp_po_detail`.`description` AS `description`,`tbl_temp_po_detail`.`amount` AS `amount`,`tbl_temp_po_detail`.`qty` AS `qty` from `tbl_temp_po_detail` where (`tbl_temp_po_detail`.`type` = _latin1'job')) */;

/*View structure for view v_temp_po_detail_material */

/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail_material` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail_material` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_po_detail_material` AS (select `tbl_temp_po_detail`.`ses_id` AS `ses_id`,`tbl_temp_po_detail`.`type` AS `type`,`tbl_temp_po_detail`.`description` AS `description`,`tbl_temp_po_detail`.`amount` AS `amount`,`tbl_temp_po_detail`.`qty` AS `qty` from `tbl_temp_po_detail` where (`tbl_temp_po_detail`.`type` = _latin1'material')) */;

/*View structure for view v_temp_po_detail_parts */

/*!50001 DROP TABLE IF EXISTS `v_temp_po_detail_parts` */;
/*!50001 DROP VIEW IF EXISTS `v_temp_po_detail_parts` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_temp_po_detail_parts` AS (select `tbl_temp_po_detail`.`ses_id` AS `ses_id`,`tbl_temp_po_detail`.`type` AS `type`,`tbl_temp_po_detail`.`description` AS `description`,`tbl_temp_po_detail`.`amount` AS `amount`,`tbl_temp_po_detail`.`qty` AS `qty` from `tbl_temp_po_detail` where (`tbl_temp_po_detail`.`type` = _latin1'parts')) */;

/*View structure for view v_user_access */

/*!50001 DROP TABLE IF EXISTS `v_user_access` */;
/*!50001 DROP VIEW IF EXISTS `v_user_access` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_access` AS (select `tbl_user_access`.`menu_id` AS `menu_id`,`tbl_menus`.`menu` AS `menu`,`tbl_user_access`.`user_id` AS `user_id`,`tbl_menus`.`status` AS `status` from (`tbl_user_access` join `tbl_menus`) where (`tbl_user_access`.`menu_id` = `tbl_menus`.`id`)) */;

/*View structure for view v_users */

/*!50001 DROP TABLE IF EXISTS `v_users` */;
/*!50001 DROP VIEW IF EXISTS `v_users` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_users` AS (select `tbl_users`.`username` AS `username`,`tbl_users`.`password` AS `password`,`tbl_users`.`name` AS `name`,`tbl_users`.`image` AS `image`,`tbl_users`.`user_created` AS `user_created`,`tbl_users`.`user_status` AS `user_status`,(case when (`tbl_users`.`user_status` = _latin1'0') then _utf8'INACTIVE' when (`tbl_users`.`user_status` = _latin1'1') then _utf8'ACTIVE' end) AS `status_desc` from `tbl_users` order by `tbl_users`.`username`) */;

/*View structure for view v_vehicleinfo */

/*!50001 DROP TABLE IF EXISTS `v_vehicleinfo` */;
/*!50001 DROP VIEW IF EXISTS `v_vehicleinfo` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_vehicleinfo` AS (select `tbl_vehicleinfo`.`vehicle_id` AS `vehicle_id`,`tbl_vehicleinfo`.`customer_id` AS `customer_id`,(select concat(`tbl_customer`.`firstname`,_ascii' ',`tbl_customer`.`middlename`,_ascii' ',`tbl_customer`.`lastname`) AS `custname` from `tbl_customer` where (convert(`tbl_customer`.`cust_id` using latin1) = `tbl_vehicleinfo`.`customer_id`)) AS `customername`,`tbl_vehicleinfo`.`address` AS `address`,`tbl_vehicleinfo`.`plate_no` AS `plate_no`,`tbl_vehicleinfo`.`conduction_sticker` AS `conduction_sticker`,`tbl_vehicleinfo`.`make` AS `make`,(select `tbl_make`.`make` AS `make` from `tbl_make` where (convert(`tbl_make`.`make_id` using latin1) = `tbl_vehicleinfo`.`make`)) AS `make_desc`,`tbl_vehicleinfo`.`year` AS `year`,(select `tbl_year`.`year` AS `year` from `tbl_year` where (convert(`tbl_year`.`year_id` using latin1) = `tbl_vehicleinfo`.`year`)) AS `year_desc`,`tbl_vehicleinfo`.`model` AS `model`,(select `tbl_model`.`model` AS `model` from `tbl_model` where (convert(`tbl_model`.`model_id` using latin1) = `tbl_vehicleinfo`.`model`)) AS `model_desc`,`tbl_vehicleinfo`.`color` AS `color`,(select `tbl_color`.`color` AS `color` from `tbl_color` where (convert(`tbl_color`.`color_id` using latin1) = `tbl_vehicleinfo`.`color`)) AS `color_desc`,`tbl_vehicleinfo`.`variant` AS `variant`,`tbl_vehicleinfo`.`description` AS `description`,`tbl_vehicleinfo`.`engine_no` AS `engine_no`,`tbl_vehicleinfo`.`chassis_no` AS `chassis_no`,`tbl_vehicleinfo`.`serial_no` AS `serial_no` from `tbl_vehicleinfo`) */;

/*View structure for view v_vehiclesummary */

/*!50001 DROP TABLE IF EXISTS `v_vehiclesummary` */;
/*!50001 DROP VIEW IF EXISTS `v_vehiclesummary` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_vehiclesummary` AS (select `v_year`.`year` AS `year`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '1'))) AS `january`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '2'))) AS `february`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '3'))) AS `march`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '4'))) AS `april`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '5'))) AS `may`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '6'))) AS `june`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '7'))) AS `july`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '8'))) AS `august`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '9'))) AS `september`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '10'))) AS `october`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '11'))) AS `november`,(select count(0) AS `COUNT(*)` from `v_service_master` where ((`v_service_master`.`trans_year` = `v_year`.`year`) and (`v_service_master`.`trans_month` = '12'))) AS `december` from `v_year`) */;

/*View structure for view v_wocat */

/*!50001 DROP TABLE IF EXISTS `v_wocat` */;
/*!50001 DROP VIEW IF EXISTS `v_wocat` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_wocat` AS (select `tbl_wocat`.`wocat_id` AS `wocat_id`,`tbl_wocat`.`wocat` AS `wocat`,`tbl_wocat`.`wocat_created` AS `wocat_created` from `tbl_wocat`) */;

/*View structure for view v_work_order_master */

/*!50001 DROP TABLE IF EXISTS `v_work_order_master` */;
/*!50001 DROP VIEW IF EXISTS `v_work_order_master` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_work_order_master` AS (select `tbl_service_master`.`wo_refno` AS `wo_refno`,`tbl_service_master`.`po_refno` AS `po_refno`,`tbl_service_master`.`transaction_date` AS `transaction_date`,`tbl_service_master`.`customer_id` AS `customer_id`,(select concat(`tbl_customer`.`firstname`,_ascii' ',`tbl_customer`.`middlename`,_ascii' ',`tbl_customer`.`lastname`) AS `custname` from `tbl_customer` where (convert(`tbl_customer`.`cust_id` using latin1) = `tbl_service_master`.`customer_id`)) AS `customername`,concat(`tbl_customer`.`address`,_ascii', ',`tbl_customer`.`city`,_ascii', ',`tbl_customer`.`province`) AS `cust_address`,`tbl_customer`.`landline` AS `landline`,`tbl_customer`.`fax` AS `fax`,`tbl_customer`.`mobile` AS `mobile`,`tbl_service_master`.`vehicle_id` AS `vehicle_id`,`v_vehicleinfo`.`plate_no` AS `plate_no`,`v_vehicleinfo`.`conduction_sticker` AS `conduction_sticker`,`v_vehicleinfo`.`make_desc` AS `make_desc`,`v_vehicleinfo`.`year_desc` AS `year_desc`,`v_vehicleinfo`.`model_desc` AS `model_desc`,`v_vehicleinfo`.`color_desc` AS `color_desc`,`v_vehicleinfo`.`variant` AS `variant`,`v_vehicleinfo`.`engine_no` AS `engine_no`,`v_vehicleinfo`.`chassis_no` AS `chassis_no`,`v_vehicleinfo`.`serial_no` AS `serial_no`,`tbl_service_master`.`payment_id` AS `payment_id`,(select `tbl_payment`.`payment` AS `payment` from `tbl_payment` where (convert(`tbl_payment`.`payment_id` using latin1) = `tbl_service_master`.`payment_id`)) AS `payment_mode`,`tbl_service_master`.`subtotal_amount` AS `subtotal_amount`,`tbl_service_master`.`discount` AS `discount`,`tbl_service_master`.`discounted_price` AS `discounted_price`,`tbl_service_master`.`vat` AS `vat`,`tbl_service_master`.`total_amount` AS `total_amount`,`tbl_service_master`.`remarks` AS `remarks`,`tbl_service_master`.`recommendation` AS `recommendation`,`tbl_service_master`.`created_by` AS `created_by`,`tbl_service_master`.`trans_status` AS `trans_status`,`tbl_service_master`.`technician` AS `emp_id`,(select `tbl_employee`.`employee` AS `employee` from `tbl_employee` where (convert(`tbl_employee`.`emp_id` using latin1) = `tbl_service_master`.`technician`)) AS `tech_name`,(case when (`tbl_service_master`.`trans_status` = _latin1'0') then _utf8'PENDING' when (`tbl_service_master`.`trans_status` = _latin1'1') then _utf8'APPROVED' when (`tbl_service_master`.`trans_status` = _latin1'2') then _utf8'DISAPPROVED' when (`tbl_service_master`.`trans_status` = _latin1'3') then _utf8'CANCELLED' when (`tbl_service_master`.`trans_status` = _latin1'4') then _utf8'FOR REPAIR' when (`tbl_service_master`.`trans_status` = _latin1'5') then _utf8'FINISHED' when (`tbl_service_master`.`trans_status` = _latin1'6') then _utf8'FOR BILLING' when (`tbl_service_master`.`trans_status` = _latin1'7') then _utf8'BILLED' when (`tbl_service_master`.`trans_status` = _latin1'8') then _utf8'ON-GOING' when (`tbl_service_master`.`trans_status` = _latin1'9') then _utf8'FOR APPROVAL' when (`tbl_service_master`.`trans_status` = _latin1'10') then _utf8'CLOSED' end) AS `status_desc` from ((`tbl_service_master` join `v_vehicleinfo`) join `tbl_customer`) where ((`tbl_service_master`.`wo_refno` is not null) and (`tbl_service_master`.`vehicle_id` = `v_vehicleinfo`.`vehicle_id`) and (`tbl_service_master`.`customer_id` = convert(`tbl_customer`.`cust_id` using latin1))) order by `tbl_service_master`.`trans_status`,`tbl_service_master`.`transaction_date` desc) */;

/*View structure for view v_year */

/*!50001 DROP TABLE IF EXISTS `v_year` */;
/*!50001 DROP VIEW IF EXISTS `v_year` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_year` AS (select `tbl_year`.`year_id` AS `year_id`,`tbl_year`.`year` AS `year`,`tbl_year`.`created` AS `created` from `tbl_year`) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
