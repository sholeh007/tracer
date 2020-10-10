# Host: localhost  (Version 5.5.5-10.4.6-MariaDB)
# Date: 2020-10-10 19:10:03
# Generator: MySQL-Front 6.1  (Build 1.26)


#
# Structure for table "jenis_kelamin"
#

DROP TABLE IF EXISTS `jenis_kelamin`;
CREATE TABLE `jenis_kelamin` (
  `idkelamin` int(11) NOT NULL AUTO_INCREMENT,
  `jenisKelamin` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idkelamin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Data for table "jenis_kelamin"
#

INSERT INTO `jenis_kelamin` VALUES (1,'Pria'),(2,'Wanita');

#
# Structure for table "jurusan"
#

DROP TABLE IF EXISTS `jurusan`;
CREATE TABLE `jurusan` (
  `idjurusan` int(11) NOT NULL AUTO_INCREMENT,
  `Jurusan` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idjurusan`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Data for table "jurusan"
#

INSERT INTO `jurusan` VALUES (1,'Teknik Informatika'),(2,'Sistem Informasi');

#
# Structure for table "pertanyaan"
#

DROP TABLE IF EXISTS `pertanyaan`;
CREATE TABLE `pertanyaan` (
  `idpertanyaan` int(11) NOT NULL AUTO_INCREMENT,
  `Pertanyaan` varchar(100) DEFAULT NULL,
  `isi1` varchar(40) DEFAULT NULL,
  `isi2` varchar(40) DEFAULT NULL,
  `isi3` varchar(40) DEFAULT NULL,
  `isi4` varchar(45) DEFAULT NULL,
  `isi5` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idpertanyaan`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

#
# Data for table "pertanyaan"
#

INSERT INTO `pertanyaan` VALUES (2,'Nama lengkap',NULL,'',NULL,NULL,NULL),(3,'Jenis kelamin',NULL,'',NULL,NULL,NULL),(4,'Tahun lulus',NULL,'',NULL,NULL,NULL),(5,'Jurusan',NULL,'',NULL,NULL,NULL),(6,'Apakah anda sudah bekerja?','sudah','belum',NULL,NULL,NULL),(7,'Nama instansi bekerja (apabila sudah bekerja)','','',NULL,NULL,NULL),(8,'Berapa lama anda menunggu sampai mendapatkan pekerjaan?','sebelum wisuda','< 3 bulan','3 s.d 6 bulan ','> 6 bulan',''),(9,'Berapa gaji pertama dari instansi saudara bekerja ?','< Rp. 1.000.000','Rp. 1.000.000 s.d 3.000.000','Rp. 3.000.000 s.d 5.000.000','> Rp. 5.000.000',''),(10,'Bagaimana tingkat kesesuaian bidang pekerjaan anda saat ini dengan materi kuliah?','rendah','sedang','tinggi','',''),(11,'Apakah dengan materi kuliah (kurikulum) sudah mendukung pekerjaan saudara?','sudah','belum','','',''),(12,'Apakah ada hambatan yang Anda hadapi pada saat mencari pekerjaan ?','ada','tidak ada','','',''),(13,'Kompetensi atau skill apa yang seharusnya diperkuat untuk menunjang pekerjaan?','hardware','software','jaringan','',''),(14,'Saran - saran','','','','','');

#
# Structure for table "user_role"
#

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `iduser_role` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`iduser_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Data for table "user_role"
#

INSERT INTO `user_role` VALUES (1,'Admin'),(2,'Alumni');

#
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `gambar` varchar(50) DEFAULT NULL,
  `status_isi` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`iduser`,`role_id`),
  KEY `fk_user_user_role1_idx` (`role_id`),
  CONSTRAINT `fk_user_user_role1` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`iduser_role`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

#
# Data for table "user"
#

INSERT INTO `user` VALUES (29,'admin','','$2y$10$mUd2W4FsTxC3RKd152dkHuwGMW8AO6Grc1G3oJCc0iJvzbsUaDce6',1,'default.jpg',0);

#
# Structure for table "alumni"
#

DROP TABLE IF EXISTS `alumni`;
CREATE TABLE `alumni` (
  `idalumni` int(11) NOT NULL AUTO_INCREMENT,
  `user_iduser` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `gender` int(11) NOT NULL,
  `jurusan` int(11) NOT NULL,
  `thn_lulus` year(4) DEFAULT NULL,
  PRIMARY KEY (`idalumni`,`user_iduser`,`gender`,`jurusan`),
  KEY `fk_alumni_jenis_kelamin1_idx` (`gender`),
  KEY `fk_alumni_jurusan1_idx` (`jurusan`),
  KEY `fk_alumni_user1_idx` (`user_iduser`),
  CONSTRAINT `fk_alumni_jenis_kelamin1` FOREIGN KEY (`gender`) REFERENCES `jenis_kelamin` (`idkelamin`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_alumni_jurusan1` FOREIGN KEY (`jurusan`) REFERENCES `jurusan` (`idjurusan`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_alumni_user1` FOREIGN KEY (`user_iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

#
# Data for table "alumni"
#


#
# Structure for table "kuesioner"
#

DROP TABLE IF EXISTS `kuesioner`;
CREATE TABLE `kuesioner` (
  `idkuesioner` int(11) NOT NULL AUTO_INCREMENT,
  `alumni_iduser` int(11) NOT NULL,
  `jawaban1` varchar(100) DEFAULT NULL,
  `jawaban2` varchar(100) DEFAULT NULL,
  `jawaban3` varchar(100) DEFAULT NULL,
  `jawaban4` varchar(100) DEFAULT NULL,
  `jawaban5` varchar(100) DEFAULT NULL,
  `jawaban6` varchar(100) DEFAULT NULL,
  `jawaban7` varchar(100) DEFAULT NULL,
  `jawaban8` varchar(100) DEFAULT NULL,
  `jawaban9` varchar(100) DEFAULT NULL,
  `jawaban10` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idkuesioner`,`alumni_iduser`),
  KEY `fk_kuesioner_alumni1_idx` (`alumni_iduser`),
  CONSTRAINT `fk_kuesioner_alumni1` FOREIGN KEY (`alumni_iduser`) REFERENCES `alumni` (`user_iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

#
# Data for table "kuesioner"
#


#
# Structure for table "user_token"
#

DROP TABLE IF EXISTS `user_token`;
CREATE TABLE `user_token` (
  `idtoken` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `date_created` int(11) DEFAULT NULL,
  PRIMARY KEY (`idtoken`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

#
# Data for table "user_token"
#

INSERT INTO `user_token` VALUES (10,'nasigoreng92@outlook.com','cj7r45qPw8QegB6RAr7G4xMkTd+aX4V3NcwcVI7FjyE=',1599731078),(11,'nasigoreng92@outlook.com','/I0x8Yb2L+RLlHJhrAdFHaPue8RkQTfsE212z0Uuc9Y=',1599737815);
