<?php
if(!is_dir(__DIR__.'./db'))
    mkdir(__DIR__.'./db');
if(!defined('db_file')) define('db_file',__DIR__.'./db/grading_db.db');
function my_udf_md5($string) {
    return md5($string);
}

Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){
        $this->open(db_file);
        $this->createFunction('md5', 'my_udf_md5');
        $this->exec("PRAGMA foreign_keys = ON;");

        $this->exec("CREATE TABLE IF NOT EXISTS `admin_list` (
            `admin_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 
        $this->exec("CREATE TABLE IF NOT EXISTS `subjects` (
            `subject_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `name` TEXT NOT NULL
        )");
        
        $this->exec("CREATE TABLE IF NOT EXISTS `grading_components` (
            `component_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `name` TEXT NOT NULL
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `component_subject_percentage` (
            `csp_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `subject_id` INTEGER NOT NULL,
            `component_id` INTEGER NOT NULL,
            `percentage` TEXT NOT NULL,
            FOREIGN KEY(`subject_id`) REFERENCES `subjects`(`subject_id`) ON DELETE CASCADE,
            FOREIGN KEY(`component_id`) REFERENCES `grading_components`(`component_id`) ON DELETE CASCADE
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `class_list` (
            `class_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `subject_id` INTEGER NOT NULL,
            `grade` TEXT NOT NULL,
            `section` TEXT NOT NULL,
            FOREIGN KEY(`subject_id`) REFERENCES `subjects`(`subject_id`) ON DELETE CASCADE
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `student_list` (
            `student_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `class_id` INTEGER NOT NULL,
            `name` TEXT NOT NULL,
            FOREIGN KEY(`class_id`) REFERENCES `class_list`(`class_id`) ON DELETE CASCADE
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `assessment_list` (
            `assessment_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `class_id` INTEGER NOT NULL,
            `component_id` INTEGER NOT NULL,
            `quarter` INTEGER NOT NULL,
            `name` TEXT NOT NULL,
            `hps` REAL NOT NULL,
            FOREIGN KEY(`class_id`) REFERENCES `class_list`(`class_id`) ON DELETE CASCADE,
            FOREIGN KEY(`component_id`) REFERENCES `grading_components`(`component_id`) ON DELETE CASCADE
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `mark_list` (
            `assessment_id` INTEGER NOT NULL,
            `student_id` INTEGER NOT NULL,
            `mark` REAL NOT NULL,
            FOREIGN KEY(`assessment_id`) REFERENCES `assessment_list`(`assessment_id`) ON DELETE CASCADE,
            FOREIGN KEY(`student_id`) REFERENCES `student_list`(`student_id`) ON DELETE CASCADE
        )");
         $this->exec("CREATE TABLE IF NOT EXISTS `transmutation_table` (
            `trans_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `from` REAL NOT NULL,
            `to` REAL NOT NULL,
            `grade` REAL NOT NULL
        )");
        // $this->exec("CREATE TRIGGER IF NOT EXISTS updatedTime_prod AFTER UPDATE on `product_list`
        // BEGIN
        //     UPDATE `product_list` SET date_updated = CURRENT_TIMESTAMP where product_id = product_id;
        // END
        // ");

        $this->exec("INSERT or IGNORE INTO `admin_list` VALUES (1,'Administrator','admin',md5('admin123'),1, CURRENT_TIMESTAMP)");
        $this->exec("INSERT or IGNORE INTO `transmutation_table` VALUES 
            (1,100.00,100,100),
            (2,0.00,3.99,60),
            (3,12.00,15.99,63),
            (4,16.00,19.99,64),
            (5,20.00,23.99,65),
            (6,24.00,27.99,66),
            (7,28.00,31.99,67),
            (8,32.00,35.99,68),
            (9,36.00,39.99,69),
            (10,4.00,7.99,61),
            (11,40.00,43.99,70),
            (12,44.00,47.99,71),
            (13,48.00,51.99,72),
            (14,52.00,55.99,73),
            (15,56.00,59.99,74),
            (16,60.00,61.59,75),
            (17,61.60,63.19,76),
            (18,63.20,64.79,77),
            (19,64.80,66.39,78),
            (20,66.40,67.99,79),
            (21,68.00,69.59,80),
            (22,69.60,71.19,81),
            (23,71.20,72.79,82),
            (24,72.80,74.39,83),
            (25,74.40,75.99,84),
            (26,76.00,77.59,85),
            (27,77.60,79.19,86),
            (28,79.20,80.79,87),
            (29,8.00,11.99,62),
            (30,80.80,82.39,88),
            (31,82.40,83.99,89),
            (32,84.00,85.59,90),
            (33,85.60,87.19,91),
            (34,87.20,88.79,92),
            (35,88.80,90.39,93),
            (36,90.40,91.99,94),
            (37,92.00,93.59,95),
            (38,93.60,95.19,96),
            (39,95.20,96.79,97),
            (40,96.80,98.39,98),
            (41,98.40,99.99,99)
        ");

    }
    function __destruct(){
         $this->close();
    }
}

$conn = new DBConnection();