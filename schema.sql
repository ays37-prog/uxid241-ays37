CREATE TABLE recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(120) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO recipes (title, description)
VALUES
  ('Chicken Tacos', 'Chicken tacos with fresh toppings.'),
  ('Pancakes', 'Fluffy pancakes for breakfast.'),
  ('Spaghetti', 'Classic spaghetti with tomato sauce.');