-- AI-assisted: schema structure developed with ChatGPT
-- based on the UXID 241 Alpha requirements and Week 4 lesson.
-- "PROMPT: can you guide me in setting up my schema.sql file so that it 
-- categorizes my seed data recipes into phpMyAdmin"

DROP TABLE IF EXISTS recipes;
DROP TABLE IF EXISTS categories;


-- CATEGORIES TABLE

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);


-- RECIPES TABLE

CREATE TABLE recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(120) NOT NULL,
  description TEXT,
  servings INT,
  prep_time INT,
  category_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (category_id) REFERENCES categories(id)
);


-- CATEGORY SEED DATA

INSERT INTO categories (name)
VALUES
  ('Chicken'),
  ('Beef'),
  ('Vegetarian'),
  ('Fish & Seafood'),
  ('Pork');


-- RECIPE SEED DATA

INSERT INTO recipes
  (title, description, servings, prep_time, category_id)
VALUES
  (
    'Ancho-Orange Chicken',
    'Chicken breasts with a smoky ancho chile paste and fresh orange glaze.',
    4,
    30,
    1
  ),
  (
    'Beef Medallions & Mushroom Sauce',
    'Beef medallions with mushroom sauce and mashed potatoes.',
    2,
    35,
    2
  ),
  (
    'Broccoli & Basil Pesto Sandwiches',
    'Italian focaccia sandwiches with broccoli, cheeses, and basil pesto.',
    4,
    25,
    3
  );