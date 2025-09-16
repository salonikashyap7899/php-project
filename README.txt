Setup Instructions:

1. Create a MySQL database named 'ecommerce'.
2. Run the following SQL to create tables:

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

3. Insert sample products:

INSERT INTO products (name, description, price, image) VALUES
('T-Shirt', 'Comfortable cotton t-shirt', 499.00, 'tshirt.jpg'),
('Jeans', 'Blue denim jeans', 1299.00, 'jeans.jpg'),
('Sneakers', 'Running sneakers', 1999.00, 'sneakers.jpg');

4. Update your MySQL username and password in db.php.

5. Place this folder in your web server root (e.g., htdocs for XAMPP).

6. Access the site via http://localhost/ecommerce-website/index.php

7. Register, login, browse products, add to cart, and checkout.

---

This is a basic demo. For production, add security, validation, and better UI.