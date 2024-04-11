CREATE TABLE PAdmin(
    admin_id INT AUTO_INCREMENT PRIMARY KEY, Email VARCHAR(75), Password VARCHAR(25), FirstName VARCHAR(50), LastName VARCHAR(50)
);

CREATE TABLE PEmployee(
    emp_id INT AUTO_INCREMENT PRIMARY KEY, Email VARCHAR(75), Password VARCHAR(25), FirstName VARCHAR(50), LastName VARCHAR(50)
);

CREATE TABLE PInventory(
    inv_id int primary key,
    quan_in_inv int
);

CREATE TABLE ShipAndHand(
   weight_bracket int primary key,
   price float(6,2)
);

CREATE TABLE PCart(

    cart_id int(5) primary key,
    total_weight float(6,2) not null,
    total_price float(8,2) not null,
    weight_bracket int, 
    foreign key(weight_bracket) references ShipAndHand(weight_bracket)
);

CREATE TABLE POrders(
   order_num BIGINT(10) primary key,
   date_placed date not null,
   cust_name varchar(50) not null,
   email char(100) not null,
   order_status char(10) not null,
   shipping_addr varchar(50) not null,
   total_price float(8,2) not null,
   total_weight float(6,2) not null,
   cart_id int(5),
   foreign key(cart_id) references PCart(cart_id)
);

CREATE TABLE PProdInCart(
    inv_id int,
    cart_id int(5),
    quan_in_order int,
    primary key(inv_id, cart_id),
    foreign key(inv_id) references PInventory(inv_id),
    foreign key(cart_id) references PCart(cart_id)
);