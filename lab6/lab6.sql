DROP TABLE IF EXISTS lab6;
CREATE TABLE lab6 (
    blog_id int primary key auto_increment,
    written_on datetime not null default current_timestamp,
    blog_title varchar(100) not null check (blog_title != ''),
    blog_content text not null check (blog_content != '')
);

DROP TABLE IF EXISTS lab6_credentials;
CREATE TABLE lab6_credentials (
    blog_password varchar(60) not null check (blog_password != '')
);

INSERT INTO lab6_credentials VALUES ('$2y$10$NjtLNxeb1Jq0FsY8VYCDfuVhd5IkteEWIkCdynOAFNR5Y6r6ucsrq');