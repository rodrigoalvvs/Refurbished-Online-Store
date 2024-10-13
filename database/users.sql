PRAGMA FOREIGN_KEYS = ON;

DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS conditions;
DROP TABLE IF EXISTS sales;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    userid INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR NOT NULL,
    email VARCHAR UNIQUE NOT NULL,
    password VARCHAR NOT NULL,
    address VARCHAR(30) DEFAULT "",
    postalCode VARCHAR(10) DEFAULT "",
    phoneNumber VARCHAR(20) DEFAULT "",
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE categories (
    categoryId INTEGER PRIMARY KEY AUTOINCREMENT,
    categoryName VARCHAR NOT NULL,
    sizes VARCHAR DEFAULT ""
);

CREATE TABLE conditions(
    conditionId INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR UNIQUE NOT NULL,
    description VARCHAR NOT NULL
);


CREATE TABLE messages(
    messageId INTEGER PRIMARY KEY AUTOINCREMENT,
    senderId INTEGER NOT NULL,
    receiverId INTEGER NOT NULL,
    productId INTEGER NOT NULL,
    date DATE NOT NULL,
    time VARCHAR NOT NULL,
    content VARCHAR NOT NULL,

    FOREIGN KEY (senderId) REFERENCES users(userid),
    FOREIGN KEY (receiverId) REFERENCES users(userid),
    FOREIGN KEY (productId) REFERENCES products(productId)
);

CREATE TABLE products (
    productId INTEGER PRIMARY KEY AUTOINCREMENT,
    sellerId INTEGER,
    categoryId INTEGER,
    conditionId INTEGER,
    size VARCHAR NOT NULL,
    gender VARCHAR DEFAULT "unisex",
    name VARCHAR NOT NULL,
    description VARCHAR NOT NULL,
    basePrice FLOAT NOT NULL,
    discount FLOAT DEFAULT 0,
    visible BOOLEAN DEFAULT TRUE,
    sold BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (sellerId) REFERENCES users(userid),
    FOREIGN KEY (categoryId) REFERENCES categories(categoryId),
    FOREIGN KEY (conditionId) REFERENCES condition(conditionId)
);

CREATE TABLE sales (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customerId INTEGER NOT NULL,
    productId INTEGER NOT NULL,
    customer_name TEXT NOT NULL,
    email TEXT NOT NULL,
    delivery_address TEXT NOT NULL,
    city TEXT NOT NULL,
    zip_code TEXT NOT NULL,
    card_number TEXT NOT NULL,
    cardholder_name TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (customerId) REFERENCES users(userid),
    FOREIGN KEY (productId) REFERENCES products(productId)
);



INSERT INTO categories (categoryName, sizes) VALUES ('T-Shirts', 'S,M,L,XL,XXL');
INSERT INTO categories (categoryName, sizes) VALUES ('Jeans', '28,30,32,34,36,38,40,42,44,46,48,50');
INSERT INTO categories (categoryName, sizes) VALUES ('Dresses', 'XS,S,M,L,XL');
INSERT INTO categories (categoryName, sizes) VALUES ('Shoes', '30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50');

INSERT INTO conditions (name, description) VALUES ("Factory New", "Items in Factory New condition are pristine, exhibiting no signs of wear or damage. They appear as if they were just manufactured, with all components intact and no visible flaws.");
INSERT INTO conditions (name, description) VALUES ("Minimal Wear", "Items categorized as Minimal Wear show minimal signs of wear and tear, with minor imperfections visible only upon close inspection. They maintain a high level of quality, with no significant damage affecting their appearance or functionality.");
INSERT INTO conditions (name, description) VALUES ("Field-Tested", "Field-Tested items have undergone moderate wear from extended use in various conditions. While they may display visible signs of wear, such as slight fading or scratches, they remain durable and functional for everyday use.");
INSERT INTO conditions (name, description) VALUES ("Well-Worn", "Items categorized as Well-Worn have experienced considerable wear and tear, showing noticeable signs of use and age. Despite aesthetic imperfections such as fading or fraying, they still offer functionality and comfort for the wearer.");



