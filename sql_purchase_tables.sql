-- Table to store user purchases (cart or completed orders)
CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES produits(Id_P)
);

-- Table to store purchase statistics (optional, can also be computed from purchases)
-- If you want to store aggregated stats, otherwise use queries on purchases table
-- Example: Most purchased products, total purchases, etc.
-- You can use queries like:
-- SELECT product_id, COUNT(*) as total_purchased FROM purchases WHERE status='completed' GROUP BY product_id ORDER BY total_purchased DESC;