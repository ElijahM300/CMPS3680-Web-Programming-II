DROP TABLE IF EXISTS account;
CREATE TABLE account (
    username varchar(25) primary key not null check (username != ''),
    user_password varchar(60) not null check (user_password != '')  
);

DROP TABLE IF EXISTS category;
CREATE TABLE category (
    categoryid integer primary key auto_increment,
    categoryname text not null
);

DROP TABLE IF EXISTS product;
CREATE TABLE product (
    productid integer primary key auto_increment,
    productname text not null,
    price decimal(10, 2) not null default 99.99,
    stock integer not null default 0
);

DROP TABLE IF EXISTS productcategory;
CREATE TABLE productcategory (
    productid integer,
    categoryid integer,
    primary key (productid, categoryid),
    foreign key (productid) references product(productid),
    foreign key (categoryid) references category(categoryid)
);

DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
    username varchar(25),
    productid integer,
    date_written datetime not null default current_timestamp,
    reviewid integer primary key auto_increment,
    review_title varchar(100) not null check (review_title != ''),
    review_content text not null check (review_content != ''),
    rating integer not null default 0,
    foreign key (username) references account(username), 
    foreign key (productid) references product(productid)
);

