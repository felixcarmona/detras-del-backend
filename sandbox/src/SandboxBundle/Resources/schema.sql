CREATE DATABASE IF NOT EXISTS sandbox;

CREATE TABLE IF NOT EXISTS sandbox.users (
user_id INT(32),
name VARCHAR(32),
RID int(11) NOT NULL auto_increment,
primary KEY (RID));