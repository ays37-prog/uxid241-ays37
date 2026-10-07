# UXID 241 Cookbook

This repository contains my quarter-long web cookbook for UXID 241. The project will be developed throughout the term as I learn and apply PHP and other web development concepts.

## AI use

I used ChatGPT Pro to help me understand the project setup instructions, configure MAMP, and set up my PHP structure. 
All AI-generated code in this project will be cited inline, according to the course AI policy with comments.


  <!-- RECIPE SUBMISSION -->

  <h2>Recipe Submission</h2>


  <!-- Show validation errors -->

  <?php if (!empty($errors)) : ?>

    <ul>
      <?php foreach ($errors as $error) : ?>
        <li><?= e($error); ?></li>
      <?php endforeach; ?>
    </ul>

  <?php endif; ?>


  <!-- Show successful submission -->

  <?php if ($success) : ?>

    <p>Recipe submitted successfully!</p>

    <p>
      Recipe: <?= e($recipe_name); ?>
    </p>

    <p>
      Email: <?= e($email); ?>
    </p>

  <?php endif; ?>


  <!-- Recipe submission form -->

  <form action="index.php" method="POST">

    <label for="name">Recipe Name:</label>

    <input
      type="text"
      id="name"
      name="name"
      value="<?= e($recipe_name); ?>"
    >

    <br>

    <label for="email">Your Email:</label>

    <input
      type="email"
      id="email"
      name="email"
      value="<?= e($email); ?>"
    >

    <br>

    <button type="submit">Submit Recipe</button>

  </form>

</body>

</html>