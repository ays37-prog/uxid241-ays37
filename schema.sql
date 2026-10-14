CREATE TABLE recipes (
--tells mySQL

  id INT AUTO_INCREMENT PRIMARY KEY,
  --ID aka columns name with a number 
  --will go up to 3 in this case 
  title VARCHAR(120) NOT NULL,
  --cretes title column. text up to 120 chars. every recipe must have a title
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  --creates desc column. allows longer text.
  --desciption is not required 
);

INSERT INTO recipes (title, description)
VALUES
  ('Chicken Tacos', 'Chicken tacos with fresh toppings.'),
  ('Pancakes', 'Fluffy pancakes for breakfast.'),
  ('Spaghetti', 'Classic spaghetti with tomato sauce.');