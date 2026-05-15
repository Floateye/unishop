## Current Plan
- I have a laravel backend that i want to use, with a blade template and vanilla js on the front end. The database is local. I have this already setup in xampp control panel. The database itself is empty (no tables), so we need to make migrations and models for users and products. You shall not update any other files that do not need changing, do not assume things that are not there. And keep the style as close to how things currently are as possible. Do not make up things or go too crazy. We like the simplicity. Just implement the features and updates needed minimally unless told for other things. Do not make any changes that are not needed.

## Profile Updates
- We want to add a profile "popup" on the top right UniShop with a User Icon that also works as a button, shows user first name and last name and profile picture and address. Past orders if any.

 ## Product Grid on the main Page
 - We want the grid that we already have to display real data from the database, and not dummy data. So we need to migrate laravel and javascript and make sure things work together.

 - We also want to add the ability for users to add reviews to the products and ratings in stars, and then so when a user enters and sees the product they can see the ratings and reviews, make it sorted by most recent review (via date of review).

 ## (Next phase only after verifying the review adding works) Admin Dashboard
 - We want to utilize an LLM which takes in the reviews as text in arabic, and returns the sentiment whether it was negative, positive, or mixed (where they might like something and dislike another in the same review), and there's neutral. The AI model is a multi-class classifier. 
 - We want to display a simple graph or indication of the sentiment of the reviews for each product. If the admin clicks on a product, they should be able to see the reviews for that product and the sentiment of each review, and they should be able to ban or delete a review if they want to.
- We also want to implement a system that goes along with the graph/dashboard where we basically can have google's model "Gemma" which basically will take all the reviews of a product and will go over the reviews of a product, let's say there were 10 reviews, 5 of them are bad, 2 mention that the color of the item fades away quickly, and 3 mention that smells tend to stick. So what it'd do is it would basically sum and say "5 negative reviews, color fades away and smell sticks" or something of the sort. Vice versa for positive reviews too, like if there were 3 reviews that say it's very comfy, and 2 reviews say it's true to size, it would sum and say "5 positive reviews, comfy and true to size". 