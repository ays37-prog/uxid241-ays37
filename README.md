# UXID 241 Cookbook

This repository contains my quarter-long web cookbook for UXID 241. The project will be developed throughout the term as I learn and apply PHP and other web development concepts.

## Running the project locally

1. Clone this repository into your `htdocs` folder,  inside your installed MAMP or XAMPP folder. In this project I used MAMP.

2. Start the local Apache and MySQL servers. If a different browser opens, then it's running.

3. Create a MySQL database named `entire_recipe_db`.

4. Import my `schema.sql` code into `entire_recipe_db` SQL tab, then hit go, to create the required tables and seed data.

5. Copy `config.example.php` and rename the copy to `config.php`. 

6. Update `config.php` with your database credentials for your local environment.

7. Open the project through `localhost` in a web browser. Do not open `index.php` directly from your htdocs folder.

## AI use
All AI-generated code in this project will be cited inline, according to the course AI policy with comments.

Week 2 set up: I used ChatGPT Pro to help me understand the project setup instructions, configure MAMP, and set up my PHP structure. This portion of code may not exist anymore upon further development throughout weekly projects.

Week 4 set up: I used ChatGPT to set up my schema.sql file to import seed data in phpMyAdmin, setting up my recipe categories and id, title, description, servings, prep time, category id, and date created. 