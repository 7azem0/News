<h1>READ ALL</h1>

<h2>Workflow: Cloning the Repository, Creating a Branch, and Pushing Changes </h2>

Follow these steps to properly clone the repository, create your own working branch, and push updates.

</br>

<b>1. Clone the repository</b><br>
Run this command on your machine:

<pre> git clone https://github.com/7azem0/News </pre> </br>

<b>2. Open VS Code and create a new terminal then navigate to the project root folder</b>

<b>3. Create a new branch for your work</b><br>
Replace <i>your-branch-name</i> with a meaningful name (example: <code>feature-login</code>).
<pre>1 - git branch your-branch-name </pre> </br><b>Switch to your branch</b>
<pre>2 - git checkout your-branch-name </pre> </br>

<b>4. Make your changes in the project</b><br>
Edit code, add features, fix bugs, etc.

</br>

<b>5. Stage your changes</b>

<pre> git add . </pre> </br>

<b>6. Commit your changes</b><br>
Write a meaningful commit message.

<pre> git commit -m "Describe what you changed" </pre> </br>

<b>7. Push your branch to GitHub</b>

<pre> git push --set-upstream origin your-branch-name </pre> </br>

<b>8. Open a Pull Request</b><br>
Go to the GitHub repository:<br>
<code>https://github.com/7azem0/News
</code><br><br>
You will see a message prompting you to create a Pull Request for your new branch.
Click <b>"Compare & Pull Request"</b>, write a description, and submit it.

</br> </br>

<b>Note:</b> Never push directly to the <code>main</code> branch. Always work through your own branch and open a Pull Request.

</br> </br>

<h2> Ensure Docker is installed. </h2>

- After getting your clone, CREATE the working environment through:<br>
<pre>
"docker-compose up -d --build" OR "docker compose up -d --build"
</pre>
NOTE : This command will run only one time After clonning the repo.
<br>
- You are free to change the yml file to fit your device.
- Commit frequently and describe any changes to the core env.
<br>
- To stop the Docker containers :
<pre>
"docker-compose stop"
</pre>
- To start them again:<br>
<pre>
"docker-compose start"</h2>
</pre>
</br>

<h2> Accessing the MySQL Container & Using the MySQL CLI </h2>

- After starting the Docker environment, you may need to access the MySQL container
  to run queries, inspect tables, or troubleshoot issues.

</br>

<b>1. Enter the MySQL container</b><br>
Run this command from the project root directory:
<pre>
docker exec -it mysql_db bash
</pre>

</br>

<b>2. Login to the MySQL server inside the container</b><br>
Once inside the container, run:
<pre>
mysql -u root -p
</pre>
Password (from <b>docker-compose.yml</b>): <b>root</b>

</br>

<b>3. Select the project database</b><br>
If you have already imported the SQL dump, switch to the project database:
<pre>
USE News;
</pre>

</br>

<b>4. Basic MySQL commands you can use</b>

- Show all databases:
<pre>
SHOW DATABASES;
</pre>

- Show all tables in the current database:
<pre>
SHOW TABLES;
</pre>

- View the structure of a table:
<pre>
DESCRIBE table_name;
</pre>
</br>

<b>5. Exit MySQL and the container</b><br>
To exit the MySQL CLI:
<pre>
EXIT;
</pre>
</br>

<b>Note:</b> These steps allow you to interact directly with your MySQL database for debugging, testing, and verifying application behaviour.
</br>
<h2> Database Import Instructions </h2>

- Inside the project, there is a file named <b>news_dump.sql</b> containing the SQL dump.<br>
  This file contains the <b>tables + sample data</b> used by the project.

- After running the Docker environment for the first time, IMPORT the database through the following steps:

</br>

<b>1. Copy the SQL dump into the MySQL container</b><br>
Use this command (from the project root folder):
<pre>
docker cp news_dump.sql mysql_db:/tmp/news_dump.sql
</pre>

</br>

<b>2. Enter the MySQL container</b>
<pre>
docker exec -it mysql_db bash
</pre>

</br>

<b>3. Login into MySQL</b>
<pre>
mysql -u root -p
</pre>
Password (from docker-compose): <b>root</b>

</br>

<b>4. Create the database and switch to it</b>
<pre>
DROP DATABASE IF EXISTS News;
CREATE DATABASE News;
USE News;
</pre>

</br>

<b>5. Import the SQL dump</b>
<pre>
SOURCE /tmp/news_dump.sql;
</pre>

</br>

<b>6. Verification (optional)</b><br>
After importing, you can check that everything is correct:
<pre>
SHOW TABLES;
</pre>

You should see the list of tables (articles, users, categories, subscriptions, etc.)

</br>

<b>Note:</b> Repeat this process ONLY when a new SQL dump is pushed to the repository.

</br>
</br>

<h2> Database Export Instructions </h2>

When you update the database structure or sample data, you need to export a fresh SQL dump and include it in the repository.

Follow the steps below to generate a new <b>news_dump.sql</b> file from the MySQL Docker container:

</br>

<b>1. Enter the MySQL container</b>

<pre> docker exec -it mysql_db bash </pre> </br>

<b>2. Export the News database using mysqldump</b>

<pre> mysqldump -u root -p News > /tmp/news_dump.sql </pre>

Password (from docker-compose): <b>root</b>

</br>

<b>3. Copy the exported SQL file from the container to your host machine</b><br>
Run this command outside the container(In the VS terminal in the project root):

<pre> docker cp mysql_db:/tmp/news_dump.sql ./news_dump.sql </pre> </br>

<b>4. Commit the new SQL dump to the repository</b>

<pre> git add news_dump.sql git commit -m "Updated database dump" git push </pre> </br>

<b>Note:</b> Perform the export ONLY when database changes must be shared with the team.

</br> </br>



<h1>Responsabilies :</h1> 
</br>

<h2>1: Content Delivery & Reading Experience</h2>

- Focus: Everything related to articles, magazines, languages, and how users read/view them.

- Main Responsibilities

- Handle loading/displaying newspapers & magazines.

- Support original print view and web-optimised text view.

- Implement translation (up to 16 languages).

- Implement listening mode (text-to-speech for articles).

- Display article content cleanly (titles, images, paragraphs, etc.).

- Provide save-for-later functionality.

- Handle article sharing to social platforms.

- Display interactive graphics & data visualizations inside articles.

- Support viewing full issues (downloadable magazines/newspapers).

</br>
</br>

<h2>2: Discovery, Search & Personalization</h2>

- Focus: How users find content, how content is organized, and how content is recommended.

- Main Responsibilities

- Categorize content into sections (business, tech, arts, breaking news…).

- Build a search system (by language, journalist, topic, keywords, etc.).

- Implement recommendations such as:

- “For You” page

- Trending articles

- Suggested topics & personalized content

- Build the Home sections:

- Morning Briefing

- Live Briefings

- Widgets for quick news glance

- Manage tags, metadata, and filtering logic.

- Implement user “favorites” system for saved articles.

</br>
</br>

<h2>3: User Accounts & Subscription System</h2>

- Focus: Everything involving user management, subscription plans, and permissions.

- Main Responsibilities

- User registration, login, logout.

- Manage subscription plans:

- Premium (unlimited access)

- Single-issue purchase

- Single-title subscription

- Control what each user can access (free vs premium).

- Subscription renewal management:

- Auto-renew on/off

- Payment status handling

- User profile page:

- Account info

- Subscriptions overview

- Saved articles

- Sync user data across devices (saved articles, preferences).

</br>
</br>

<h2>4: Backend Infrastructure, Database & API Integration</h2>

- Focus: The foundation — servers, databases, APIs, performance, and content sources.

- Main Responsibilities

- Design and maintain the database (articles, categories, users, subscriptions).

- Build backend APIs for:

   - Articles

   - Categories

   - Search

   - User management

   - Subscription validation

- Integrate external content/news sources if required.

- Handle downloading & storing newspaper/magazine issues.

- Handle comments system (dynamic commenting).

- Manage performance optimization (caching, CDNs, fast loading).

- Manage push notifications or alerts (optional).

</h2>
