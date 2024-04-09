CREATE TABLE PAdmin(
    admin_id varchar(5) primary key
);

CREATE TABLE PEmployee(
    emp_id varchar(5) primary key
);

CREATE TABLE PInventory(
    inv_id int primary key,
    quan_in_inv int
);

CREATE TABLE ShipAndHand(
   weight_bracket int primary key,
   price float(6,2)
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
   cc_num bigint(16) not null,
   cc_exp varchar(5) not null,
   weight_bracket int,
   foreign key(weight_bracket) references ShipAndHand(weight_bracket)
);

CREATE TABLE PProdInOrder(
    inv_id int,
    order_num bigint(10),
    quan_in_order int,
    primary key(inv_id, order_num),
    foreign key(inv_id) references PInventory(inv_id),
    foreign key(order_num) references POrders(order_num)
);